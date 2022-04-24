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
                    require_once("includes/database.php");

                    // $name= $_SESSION["username"];
                    $sqlStatement="SELECT * FROM appointment WHERE customerid=1";
                ?>
                <tr class="even">
                    <td>Kezhilen Motean</td>   
                    <td>57655508</td>                 
                    <td>Tue 2/12/2021</td>
                    <td>10:00 GMT+4</td>
                    <td>Cut, Faire moi beau</td>
                </tr>
                <tr class="odd">
                    <td>Kenylen Motean</td>
                    <td>58253488</td>
                    <td>Fri 2/1/2022</td>
                    <td>14:30 GMT+4</td>
                    <td>Bowl cut, Beard Trim</td>
                </tr>
                <tr class="even">
                    <td>Keelan Motean</td>
                    <td>59294259</td>
                    <td>Mon 2/5/2022</td>
                    <td>18:30 GMT+4</td>
                    <td>Beard Trim</td>
                </tr>
                <tr style="background-color: #F2F2F2">
                    <!-- <td colspan="100%" style="text-align: center;background-color:#F2F2F2"> No visit information on file </td> -->
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>