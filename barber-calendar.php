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
                    <th>Customer</th>
                    <th style="width:10%">Phone Number</th>
                    <th style="width:10%">Day</th>
                    <th style="width:10%">Time</th>
                    <th>Services</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $url = "http://localhost/Barber-Web-App/getbarbercalendar.php";
                $json = file_get_contents($url);
                // echo $json;
                $obj = json_decode($json, false);
                if (count($obj) == 0) {
                    echo "<td colspan=\"100%\" style=\"text-align: center;background-color:#F2F2F2\"> No Appointment information on file </td>";
                } else {
                    $i = 0;
                    foreach ($obj as $calendardata) {
                        if ($i == 0) {
                            echo "<tr class=\"even\">";
                            $i = 1;
                        } else {
                            echo "<tr>";
                            $i = 0;
                        }
                        if ($calendardata->barberid == $_SESSION['barber_userid']) {
                            echo "<td>" . $calendardata->custname . "</td>";
                            echo "<td>" . $calendardata->phone . "</td>";
                            echo "<td>" . date("D", strtotime($calendardata->appointmentdate)) . " " . $calendardata->appointmentdate . "</td>";
                            echo "<td>" . date("H:i", strtotime($calendardata->start_time)) . " GMT+4</td>";
                            echo "<td>" . $calendardata->services . "</td>";
                        }
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    </body>

</html>