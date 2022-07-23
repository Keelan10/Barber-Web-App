
$(document).ready(function(){
    const month = ["January","February","March","April","May","June","July","August","September","October","November","December"];
    
    // load year options 
    var yearOptions="";
    yearOptions+=`<option>${new Date().getFullYear()}</option>`
    yearOptions+=`<option>${new Date().getFullYear()+1}</option>`
    $("select#year").html(yearOptions)

    // load month options
    var monthOptions=""
    for (let i = new Date().getMonth(); i < month.length; i++) {
        monthOptions+=`<option>${month[i]}</option>`
    }
    $("select#month").html(monthOptions)

    // display the dates available for initially loaded barber, year and month
    displayAvailableDates()

    // display the dates available for initially loaded barber, year,  month and date
    displayTimeAvailableOnThatDay()


    
    function datesOfDayInMonth(day, month, year){
        // Function returns all the dates of that day
        // For instance datesOfDayInMonth("Monday",7,2022) returns all the dates of Monday in July 2022

        // getMonth() returns the month (0 to 11) of a date.
        // January = 0, February = 1, etc

        // getDay() method returns the day of the week (0 to 6) of a date.
        // Sunday = 0, Monday = 1, etc

        // get last date of month. 0-31
        var days = new Date(year,month,0).getDate();

        // get first day index of month
        var firstDayIndex =  new Date(month +'/01/'+ year).getDay();

        switch(day) {
        case "Monday":
            // get first monday date of month
            if(firstDayIndex != 1) firstDayIndex = 2 - firstDayIndex;
            else firstDayIndex=1;
            break;
        case "Tuesday":
            // get first tuesday date of month
            if(firstDayIndex != 2) firstDayIndex = 3 - firstDayIndex;
            else firstDayIndex=1;
            break;

        case "Wednesday":
            // get first wednesday date of month
            if(firstDayIndex != 3) firstDayIndex = 4 - firstDayIndex;
            else firstDayIndex=1;
            break;

        case "Thursday":
            // get first thursday date of month
            if(firstDayIndex != 4) firstDayIndex = 5 - firstDayIndex;
            else firstDayIndex=1;
            break;

        case "Friday":
            // get first friday date of month
            if(firstDayIndex != 5) firstDayIndex = 6 - firstDayIndex;
            else firstDayIndex=1;
            break;

        case "Saturday":
            // get first saturday date of month
            if(firstDayIndex != 6) firstDayIndex = 7 - firstDayIndex;
            else firstDayIndex=1;
            break;

        case "Sunday":
            // get first sunday date of month
            if (firstDayIndex!=0) firstDayIndex =  8 - firstDayIndex;
            else if(firstDayIndex==0) firstDayIndex=1
            break;


        default:
            console.log("Invalid day!")
            return
        }

        if(firstDayIndex<=0)firstDayIndex+=7

        // stores the dates of the respective day
        var dates = [firstDayIndex];

        for (var i = dates[0] + 7; i <= days; i += 7) {
            dates.push(i);
        }

        // console.log(dates)
        return dates;
    }

    function getScheduleWorkingDates(barberid,month,year){
        // Function returns the dates of the month a barber works
        
        var calendar=[];

        // Get the days of the week a barber works. For example Monday, Tuesday
        // And translate the days to the dates in that month
        $.ajax({
            url:"getScheduleDays.php",
            method:"post",
            dataType:"json",
            data: {barberid:barberid},
            async: false,
            success: function(data){
                $.each(data,function(i){
                    var dates = datesOfDayInMonth(this.day,month,year)
                    
                    for (let j = 0; j < dates.length; j++) {
                        dates[j]={date:dates[j],time:data[i].start_time}                      
                    }

                    $.merge(calendar,dates)
                })

                calendar.sort(function(a,b){return a.date-b.date})
            }
        })
        return calendar
    }

    function getHolidaysInMonth(barberid,month,year){
        // Function returns the dates the barber is in holiday for the specific month in a specific year

        var holidayDates = []

        // AJAX call to fetch the holidays
        // Fetched data is processed and only holiday dates affecting that specific month in that year is returned
        $.ajax({
            url:"getHolidays.php",
            method:"post",
            dataType:"json",
            data: {barberid:barberid},
            async: false,
            success: function(data){
                $(data).each(function(index,date){
                    const start_date=new Date(date.start_date);
                    const end_date=new Date(date.end_date);

                    // if holiday started the year before and ends this month
                    // not catering for holidays which last over 1 year since it probably does not reflect real life
                    if (start_date.getFullYear()<year && end_date.getMonth()+1==month && end_date.getFullYear()==year){
                        for (let i = 1; i <= end_date.getDate(); i++) {
                            // if not in array, push
                            if(holidayDates.indexOf(i)==-1)
                                holidayDates.push(i)
                        }

                    }
                    else if(start_date.getFullYear()<year && end_date.getFullYear()==year && end_date.getMonth()+1>month)
                    // if holiday started a previous year and ends after selected month in the same year
                    // full month is holiday
                    {
                        lastDateOfMonth = new Date(year,month,0).getDate()

                            for (let i = 1; i <= lastDateOfMonth; i++) {
                                // if not in array, push
                                if(holidayDates.indexOf(i)==-1)
                                    holidayDates.push(i)
                            }
                    }
                    else if(start_date.getFullYear()==year && end_date.getFullYear()==year)
                        // if holiday starts and ends in the same year
                    {  
                        
                        // if holiday starts before that month and ends in that month
                        if((start_date.getMonth()+1)<month && (end_date.getMonth()+1)==month){

                            for (let i = 1; i <= end_date.getDate(); i++) {
                                // if not in array, push
                                if(holidayDates.indexOf(i)==-1)
                                    holidayDates.push(i)
                            }
                            
                        }else if(start_date.getMonth()+1<month && end_date.getMonth()+1>month)
                        // holiday starts in an earlier month and ends in a future month
                        // whole month is a holiday then
                        {
                            lastDateOfMonth = new Date(year,month,0).getDate()

                            for (let i = 1; i <= lastDateOfMonth; i++) {
                                // if not in array, push
                                if(holidayDates.indexOf(i)==-1)
                                    holidayDates.push(i)
                            }

                        }
                        else if((start_date.getMonth()+1)==month && (end_date.getMonth()+1)==month)
                        // if holiday starts in that month and ends in the same month
                        {
                            for (let i = start_date.getDate(); i <= end_date.getDate(); i++) {
                                // if not in array, push
                                if(holidayDates.indexOf(i)==-1)
                                    holidayDates.push(i)
                            }

                        }else if(start_date.getMonth()+1==month && end_date.getMonth()+1>month)
                        // if holiday starts in that month and ends in a future month
                        {
                            lastDateOfMonth = new Date(year,month,0).getDate()
                            for (let i = start_date.getDate(); i <= lastDateOfMonth; i++) {
                                // if not in array, push
                                if(holidayDates.indexOf(i)==-1)
                                    holidayDates.push(i)
                            }
                        }

                    }else if(start_date.getFullYear()==year && end_date.getFullYear()>year)
                    // if holiday starts this year and ends the next year
                    {
                        lastDateOfMonth = new Date(year,month,0).getDate()
                            for (let i = start_date.getDate(); i <= lastDateOfMonth; i++) {
                                // if not in array, push
                                if(holidayDates.indexOf(i)==-1)
                                    holidayDates.push(i)
                            }
                    }

                    
                    // sort array
                    holidayDates.sort(function(a, b){return a-b})
                    
                })
            }
        })
        return holidayDates
    }

    function getWorkDates(barberid, month, year){
        // return the dates the barber works (and has no holiday)
        // remove holidays from dates the barber should work according to schedule

        var schedule = getScheduleWorkingDates(barberid,month,year)
        const holidays  = getHolidaysInMonth(barberid, month, year) 
        var workDates=[]

        for( var i = 0; i < schedule.length; i++){ 
            
            // if schedule date does not match with holiday date, add to work dates
            if (holidays.indexOf(schedule[i].date)==-1) { 
                workDates.push(schedule[i])
            }
        }

        // console.log(schedule)
        // console.log(holidays)
        // console.log(workDates)
        return workDates;

    }

    function getAppointmentsOnDay(){
        // function to return appointments of selected barber on a specific date

        const barberid= $("#barber :selected").attr("id");
        const month_selected = month.indexOf($("#month :selected").val())+1
        const year = $("#year :selected").val()
        const day=$("#day :selected").val()

        var appointments=[]
        $.ajax({
            url:"getAppointmentsOnDay.php",
            method:"post",
            dataType:"json",
            data: {barberid:barberid,day:day,month:month_selected,year:year},
            async: false,
            success: function(data){
                appointments=data
            }
        })

        return appointments

    }

    function displayTimeAvailableOnThatDay(){
        // function that loads the available booking slots
        // Removes appointment time from working hours

        const barberid= $("#barber :selected").attr("id");
        const month_selected = month.indexOf($("#month :selected").val())+1
        const year = $("#year :selected").val()
        const day=$("#day :selected").val()
        const start_time =$("#day :selected").attr("start_time")

        // if barber has not yet been assigned a schedule
        if(start_time==null){
            $("#time").html("")
            return
        }

        var hour = parseInt(start_time.split(":")[0])
        var min = parseInt(start_time.split(":")[1])


        const appointments =getAppointmentsOnDay()

        // if there is appointment on that day
        if (appointments.length!=0){
            // increment in units of 30 mins until appointment time
            // a barber works 8 hour a day

            const end_hour = hour+ 8
            const end_min = min

            var timeOptions=""
                
                for (let i = 0; i < appointments.length; i++) {
                    const appointment_startHour = parseInt(appointments[i].start_time.split(":")[0])
                    const appointment_startMin = parseInt(appointments[i].start_time.split(":")[1])

                    // increment in units of 30 until appointment time
                    while(hour<appointment_startHour|| min<appointment_startMin){

                        timeOptions+=`<option>${hour<10?"0"+hour:hour}:${min<10?"0"+min:min}</option>`
                        min+=30;
                        if (min>=60){
                            min=60-min
                            hour++
                        }

                    }

                    // set hour and min to end time of appointments
                    hour= parseInt(appointments[i].end_time.split(":")[0])
                    min= parseInt(appointments[i].end_time.split(":")[1])

                    // if min is not a multiple of 30, make adjustments
                    if (min%30!=0) min+= 30 - min%30
                    if (min>=60){
                        min=60-min
                        hour++
                    }
                    
                }

                // display remaining time slots if any
                while (hour<end_hour || min<end_min){
                    timeOptions+=`<option>${hour<10?"0"+hour:hour}:${min<10?"0"+min:min}</option>`

                    min+=30;
                    if (min>=60){
                        min=60-min
                        hour++
                    }
                }

                if(timeOptions=="") timeOptions="All slots are booked for that day!"
                $("#time").html(timeOptions)
                
            }else
        // otherwise available whole day
        {
            // increments of 30
            // a barber works for 8 hours a day
            var timeOptions=""
            for (let i = 0; i < 8*60/30; i++) {
                timeOptions+=`<option>${hour<10?"0"+hour:hour}:${min<10?"0"+min:min}</option>`
                min+=30;
                if (min>=60){
                    min=60-min
                    hour++
                }
            }
            $("#time").html(timeOptions)
        }
    }

    function displayAvailableDates(){
        // function to display available working dates of month for selected barber

        const barberid= $("#barber :selected").attr("id");
        const month_selected = month.indexOf($("#month :selected").val())+1
        const year = $("#year :selected").val();

        const dates = getWorkDates(barberid,month_selected,year)
        var dateOptions = ""
        $(dates).each(function(){
            // if month == current month and year == current year, display only days which has not passed
            // otherwise display all appropriate dates
            if(month_selected == new Date().getMonth()+1 && year==new Date().getFullYear()){
                if(this.date>new Date().getDate())
                    dateOptions+=`<option start_time="${this.time}">${this.date}</option>`

            }else{
                dateOptions+=`<option start_time="${this.time}">${this.date}</option>`
            }
        })
        // if(dateOptions==""){
            
        //     dateOptions="<option>No date available!</option>"
        // }
        
        $("select#day").html(dateOptions)
        console.log(dateOptions)
    }

    $("select#month").change(function(){
        // when month changes, update date and time available
        displayAvailableDates()
        displayTimeAvailableOnThatDay()
        checkDurationIfPossible()

    })

    $("select#year").change(function(){
        // when year changes, update month, date and time available

        // if year > current year, refresh month options with all months
        // else if year == current year, show only months > current month
        if ($(this).val()>new Date().getFullYear()){

            var monthOptions=""
            for (let i = 0; i < month.length; i++) {
                monthOptions+=`<option>${month[i]}</option>`
            }
            $("select#month").html(monthOptions)
            
        }else{
            var monthOptions=""
            for (let i = new Date().getMonth(); i < month.length; i++) {
                monthOptions+=`<option>${month[i]}</option>`
            }
            $("select#month").html(monthOptions)
        }
        displayAvailableDates()
        displayTimeAvailableOnThatDay()
        checkDurationIfPossible()


    })

    $("select#day").change(function(){
        // when day changes, update time available
        displayTimeAvailableOnThatDay()
        checkDurationIfPossible()

    })

    $("select#barber").change(function(){
        // When barber changes, update dates and time available
        displayAvailableDates()
        displayTimeAvailableOnThatDay()
        checkDurationIfPossible()


    })

    $("select#time").change(function(){
        checkDurationIfPossible()
    })

    $(".service").change(function(){
        checkDurationIfPossible()
    })

    function checkDurationIfPossible(){
        // apply function when checkbox is changed

        const appointments = getAppointmentsOnDay()
        if (appointments.length==0){
            $(".Submit-btn").prop("disabled",false)
            return
        } 

        if ($("#time").val()==null) return


        // calculate duration
        var duration=0
        $(".service").each(function(){
            if($(this).is(":checked")) duration+= parseInt($(this).attr("duration"))
        })
        // console.log(duration)

        // new appointment end time
        var hour = parseInt($("#time").val().split(":")[0])
        var min = parseInt($("#time").val().split(":")[1])

        const startHour = hour
        const startMin = min

        min+=duration
        if(min>=60){
            min=min-60
            hour++
        }
        
        // console.log(hour+":"+min)

        // we just need to compare the time
        const newAppointment_starttime= new Date(0,0,0,startHour,startMin)
        const newAppointment_endtime= new Date(0,0,0,hour,min)
        // console.log(newAppointment_starttime)
        // console.log(newAppointment_endtime)


        
        $(".Submit-btn").prop("disabled",false)
        

        for (let i = 0; i < appointments.length; i++) {
            const appointment_startTime= new Date(0,0,0,appointments[i].start_time.split(":")[0],appointments[i].start_time.split(":")[1])
            const appointment_endTime= new Date(0,0,0,appointments[i].end_time.split(":")[0],appointments[i].end_time.split(":")[1])
            // console.log(appointment_startTime)
            // console.log(appointment_endTime)

            // IF NEW APPOINTMENT END TIME FALLS DURING ANOTHER APPOINTMENT REJECT
            if(newAppointment_starttime<appointment_startTime &&  appointment_startTime<newAppointment_endtime){
                $(".Submit-btn").prop("disabled",true)
                alert("Your appointment takes too long. Consider reducing the number of services")
                return;
            }
        }

    }

})
