<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Calendar</title>

    <?php
    $active = "barbercalendar";
    include "includes/barber-menu.php";
    ?>
    <div class="content">
        <h1>Working hours</h1>
        <table class="order-list">
            <thead>
                <tr>
                    <th width=30%>Customer</th>
                    <th style="width:12%">Phone Number</th>
                    <th style="width:12%">Day</th>
                    <th style="width:12%">Time</th>
                    <th>Services</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once("includes/database.php");

                // $name= $_SESSION["username"];
                $sqlStatement = "SELECT appointment.transactionid,CONCAT(customer.first_name,\" \",customer.last_name) as custname,customer.phone,appointmentdate,start_time,transactions.paymentid
                                    FROM appointment,payment,transactions,barber,customer
                                    WHERE transactions.transactionid=appointment.transactionid
                                    AND transactions.paymentid=payment.paymentid
                                    AND barber.barberid=appointment.barberid
                                    AND customer.customerid = transactions.customerid
                                    AND transactions.is_cancelled=0
                                    AND appointmentdate>=CURDATE()
                                    AND barber.barberid = " . $conn->quote($_SESSION['barber_userid']) . ";";

                $Results = $conn->query($sqlStatement);
                $numRows = $Results->rowCount();


                if ($numRows == 0) echo "<td colspan=\"100%\" style=\"text-align: center;background-color:#F2F2F2\"> No Appointment information on file </td>";
                else {
                    $i = 0;
                    while ($row = $Results->fetch()) {
                        if ($i == 0) {
                            echo "<tr class=\"even\">";
                            $i = 1;
                        } else {
                            echo "<tr>";
                            $i = 0;
                        }

                        echo "<td>" . $row["custname"] . "</td>";
                        echo "<td>" . $row["phone"] . "</td>";
                        echo "<td>" . date("D", strtotime($row["appointmentdate"])) . " " . $row["appointmentdate"] . "</td>";
                        echo "<td>" . date("H:i", strtotime($row["start_time"])) . " GMT+4</td>";



                        $serviceSQL =
                            "SELECT service.name
                            FROM appointmentdetails,service
                            WHERE service.serviceid=appointmentdetails.serviceid
                            AND  transactionid=" . $row["transactionid"] . ";";

                        $serviceResults = $conn->query($serviceSQL); //USE PREPARED STATEMENTS AFTERWARDS
                        $services = $serviceResults->fetchall();

                        $string = "";
                        for ($j = 0; $j < sizeof($services) - 1; $j++) {
                            $string .= $services[$j]["name"] . ", ";
                        }
                        $string .= $services[sizeof($services) - 1]["name"];
                        // echo $string;                    
                        echo "<td>" . $string . "</td>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    </body>

</html>