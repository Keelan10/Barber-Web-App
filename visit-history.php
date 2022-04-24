<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visit History</title>
    <?php 
        $active_menu="visit";
        include "includes/customer-menu.php";
    ?>
    <!-- <div class="fake-menu" style="width:80%;height:6rem;"></div> -->

    <div class="content">
        <h1>Visit History</h1>
        <table class="order-list">
            <thead>
                <tr>
                    <th style="width:10%">Day</th>
                    <th style="width:10%">Time</th>
                    <th>Stylist</th>
                    <th>Services</th>
                    <th style="width:8%">Status</th>
                    <th style="width:10%">Payment Ref</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    require_once("includes/database.php");

                    // $_SESSION["userid"]=1;

                    $sqlStatement=
                    'SELECT appointment.transactionid,is_cancelled,appointmentdate,start_time,concat(barber.first_name," ",barber.last_name) AS stylist,transactions.paymentid
                    FROM appointment,payment,transactions,barber
                    WHERE transactions.transactionid=appointment.transactionid
                    AND transactions.paymentid=payment.paymentid
                    AND barber.barberid=appointment.barberid
                    AND transactions.customerid='.$conn->quote($_SESSION["customer_userid"]).';';

                    $Results=$conn->query($sqlStatement);
                    $numRows=$Results->rowCount();

                    
                    if ($numRows==0) echo "<td colspan=\"100%\" style=\"text-align: center;background-color:#F2F2F2\"> No visit information on file </td>";
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
                            if($row["is_cancelled"]){
                                echo "<td style=\"color:red;;text-transform: capitalize;font-weight: 700;\">Cancelled</td>";
                            }
                            else{
                                echo "<td style=\"color:#8AAD25;text-transform: capitalize;font-weight: 700;\">Confirmed</td>";
                            }
                            echo "<td>#".$row["paymentid"]."</td>";
                        }
                    }
                    
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>