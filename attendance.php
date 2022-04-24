<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Document</title>
<style>
    .attendance{
    padding-right: 1rem;
    font-size: 14px;
    }
    .response-btn{
        background-color: var(--bs-body-color);
        color: white;
        border-radius: 10px;
        border: 2px solid var(--bs-body-color);
    }
    .response-btn:hover{
        box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;
        background-color: rgb(80,100,100);
    }
    .error{
        display: inline;
        color: red;
    }
</style>

<?php
    $activeMenu="attendance";
    include_once("includes/admin-menu.php");
    require_once("includes/database.php");

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $cust_id="";
        foreach($_POST as $key=>$value){
            $cust_id = $key;

            $sUpdate = "UPDATE customer
                        SET no_show = ".$conn->quote($value)
                        ."WHERE customerid = ".$conn->quote($cust_id);

            echo $sUpdate;
            // $UpdateResult = $conn->exec($sUpdate);
        }
    }
?>
    <main class="content">
        <div class="container-fluid p-0">

        <div class="mb-3">
                <h1 class="h3 d-inline align-middle">View Attendance Of Customer</h1>
            </div>
            <div class="card flex-fill">
                <div class="card-header">
                    <h5 class="card-title mb-0">Attendance Sheet</h5>
                </div>
            
            <table class="table table-hover my-0">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Appointment Date</th>
                        <th>Services</th>
                        <th>Stylist</th>
                        <th>Attendance</th>      
                    </tr>
                </thead>
                <tbody>
                    <?php 

                        $sSelect = 'SELECT customer.customerid,concat(customer.first_name," ",customer.last_name) AS Customername,concat(barber.first_name," ",barber.last_name) AS Stylist,transactions.transactionid,appointment.appointmentdate
                                    FROM appointment,transactions,barber,customer
                                    WHERE transactions.transactionid = appointment.transactionid
                                    AND appointment.barberid = barber.barberid
                                    AND transactions.customerid = customer.customerid
                                    AND appointment.appointmentdate = CURRENT_DATE
                                    AND TIMEDIFF(appointment.start_time,CURRENT_TIME) > 0;';

                        $result = $conn->query($sSelect);
                        $rowcount = $result->rowcount();
                        //$rowcount = 0;
                        if ($rowcount == 0){
                            echo "<td colspan=\"100%\" style=\"text-align: center;\"> No appointment on file </td>";
                        }
                        else{
                            while($row = $result->fetch()){
                                echo "<tr>";
                                $customerid = $row['customerid'];

                                echo "<td>".$row['Customername']."</td>";
                                echo "<td>".$row['appointmentdate']."</td>";

                                $serviceSQL="   SELECT service.name
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

                                echo "<td>".$row['Stylist']."</td>";

                                echo '<td>
                                        <form action="attendance.php" method="post" >
                                        <input type="radio" id="show" name="'. "$customerid" .'" value="0" >
                                        <label for="show" class = "attendance">Present</label>
                                        <input type="radio" id="no_show" name="'. "$customerid" .'" value="1" >
                                        <label for="no_show" class = "attendance">Absent</label>
                                    </td>';
                                
                                echo "</tr>";
                            }
                        }

                    ?>

                    <!-- <tr>
                        <td>Kezhilen Motean</td>
                        <td>2022-03-25</td>
                        <td>Shave,Long Cut</td>
                        <td>Keelan Cannoo</td>
                        <td>
                            <form action="attendance.php" method="get" >
                            <input type="radio" id="show" name="attendance" value="0" >
                            <label for="show" class = "attendance">Present</label>
                            <input type="radio" id="no_show" name="attendance" value="1" >
                            <label for="no_show" class = "attendance">Absent</label>
                        </td>
                    </tr> -->
                </tbody>
            </table>
            <div class="btn-containers">
                <input type="submit" value = "Confirm" class="btn btn-secondary" style="width: 7%; margin: 1rem; padding: 0.1rem;"> 
                <input type="Reset" value = "Reset" class="btn btn-secondary" style="width: 7%; margin: 1rem; padding: 0.1rem;"> 
            </div>
            </form>

        </div>
    </main>

        </div><!--end of main div-->
    </div><!--end of wrapper div-->
</body>

</html>