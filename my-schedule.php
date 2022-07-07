<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Schedule</title>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php 
        $active_menu="schedule";
        include "includes/customer-menu.php";
    ?>


    <div class="content">
        <h1>My Schedule</h1>
        <table class="order-list">
            <thead>
                <tr>
                    <th style="width:10%">Day</th>
                    <th style="width:10%">Time</th>
                    <th>Stylist</th>
                    <th>Services</th>
                    <!-- <th style="width:8%">Status</th> -->
                    <th>Reschedule</th>
                    <th style="width:10%">Cancel</th>
                    <th style="width:10%">Payment Ref</th>
                </tr>
            </thead>
            <tbody>
            <?php 

                require_once("includes/database.php");

                //$_SESSION["userid"]=1;
                $sqlStatement=
                    'SELECT appointment.transactionid,appointmentdate,start_time,concat(barber.first_name," ",barber.last_name) AS stylist,transactions.paymentid
                    FROM appointment,payment,transactions,barber
                    WHERE transactions.transactionid=appointment.transactionid
                    AND transactions.paymentid=payment.paymentid
                    AND barber.barberid=appointment.barberid
                    AND cast(concat(appointmentdate," ",start_time) as datetime)>=NOW()
                    AND transactions.is_cancelled = 0
                    AND appointment.no_show=1
                    AND transactions.customerid='.$conn->quote($_SESSION["customer_userid"]).';';
                    // GROUP BY appointment.transactionid;';

                
                $Results=$conn->query($sqlStatement);
                $numRows=$Results->rowCount();

                
                if ($numRows==0) echo "<td colspan=\"100%\" style=\"text-align: center;background-color:#F2F2F2\"> No upcoming appointment </td>";
                else{
                    $i=0;
                    while($row=$Results->fetch()){
                        if($i==0){
                            echo "<tr class=\"even\">";
                            $i=1;
                        }
                        else{
                            echo "<tr>";
                            $i=0;
                        }
                        echo "<td>".date("D",strtotime($row["appointmentdate"]))." ".$row["appointmentdate"]."</td>";
                        echo "<td>".date("H:i",strtotime($row["start_time"]))." GMT+4</td>";
                        echo "<td>".$row["stylist"]."</td>";

                        
                        
                        $serviceSQL=
                        "SELECT service.name
                        FROM appointmentdetails,service
                        WHERE service.serviceid=appointmentdetails.serviceid
                        AND  transactionid=".$row["transactionid"].";";
            
                        $serviceResults=$conn->query($serviceSQL);//USE PREPARED STATEMENTS AFTERWARDS
                        $services=$serviceResults->fetchall();
                        
                        $string="";
                        for($j=0;$j<sizeof($services)-1;$j++){
                            $string.=$services[$j]["name"].", ";
                        }
                        $string.=$services[sizeof($services)-1]["name"];
                        // echo $string;                    
                        echo "<td>".$string."</td>";

                        echo "<td><span style=\"cursor:pointer; color:#003f87;font-weight: 500;\">Reschedule</span></td>";
                        echo "<td><span style=\"cursor:pointer; color:#003f87;font-weight: 500;\" class=\"cancel\" id='".$row["transactionid"]."'>Cancel</span></td>";
                        echo "<td>#".$row["paymentid"]."</td>";
                    }
                }
                    
            ?>

            </tbody>
        </table>
    </div>
</body>
<script>
    $(document).ready(function(){
        $(".cancel").click(function(event){

            const transactionid = $(this).attr("id")
            const row = $(this).parents("tr");
            const adjacent_TR_Length = $(this).parents("tr").siblings().length; 

            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, go ahead!'
            }).then((result) => {
            if (result.isConfirmed) {

                $.post(
                    "cancelTransaction.php",
                    {transactionid: transactionid, option: "appointment"},
                    function(data){
                        if (data=="success"){

                            if (adjacent_TR_Length==0){
                                $(row).parent().html(`<td colspan=\"100%\" style=\"text-align: center;background-color:#F2F2F2\"> No upcoming appointment </td>`)
                            }else{
                                row.remove()
                            }

                            Swal.fire(
                                'Cancellation successful!',
                                'Your appointment has been cancelled',
                                'success'
                            )
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                            })
                        }
                    }
                );
            }
            })

            

        })
    })
</script>

</html>