<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Holiday-Application</title>

    <style>
        .datepicker {
            padding: 1rem;
        }

        .start-date,
        .end-date {
            padding: 1rem;
            display: inline-block;
            border: 1.5px solid #666666;
            border-radius: 10px;
            margin-right: 2rem;
        }

        .desc {
            padding: 1rem;
            margin-top: 2rem;
            border-radius: 10px;
            border: 1.5px solid #666666;
        }

        .Apply-btn {
            margin-top: 2rem;
            padding: 0.4rem 1rem;
            border: 2px solid rgb(0, 0, 0.5 / 50%);
            border-radius: 10px;
            background-color: rgb(0, 0, 0, 0.6);
            color: white;
            float: right;
        }

        .Apply-btn:hover {
            box-shadow: 0px 5px 10px 0px rgba(0, 0, 0, 0.5);
        }
    </style>
    <?php
    $active = "barberapply";
    include "includes/barber-menu.php";
    require_once "includes/database.php";

    $startdateErr = $enddateErr = $descErr = $error = "";
    $startdate = $enddate = $desc = $Msg = "";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {



        if (empty($_POST['startdate'])) {
            $startdateErr = "A start date is required!";
        } else {
            $startdate = test_input($_POST['startdate']);
            $today = date("Y-m-d");
            if ($startdate <= $today) {
                $startdateErr = "Invalid Start date!";
            }
        }

        if (empty($_POST['enddate'])) {
            $enddateErr = "An end date is required!";
        } else {
            $enddate = test_input($_POST['enddate']);
            if ($enddate < $startdate) {
                $enddateErr = "Invalid End date!";;
            }
            $today = date("Y-m-d");
            if ($enddate <= $today) {
                $enddateErr = "Invalid End date!";;
            }
        }
        $sql = "SELECT * FROM appointment 
                WHERE appointmentdate >= '" . $startdate . "' 
                and appointmentdate <= '" . $enddate . "' 
                and barberid = " . $conn->quote($_SESSION['barber_userid']) . ";";
        //echo $sql;
        $result = $conn->query($sql);
        $rowcount = $result->rowCount();
        if ($rowcount > 0) {
            $error = "You have appointments in between!";
        }
        // echo $startdateErr."<br>";
        // echo $enddateErr."<br>";

        if (empty($_POST['description'])) {
            $descErr = "A description is required";
        } else {
            $desc = test_input($_POST['description']);
        }
        if ($startdateErr == "" && $enddateErr == "" && $descErr == ""  && $error == "") {

            // $_SESSION['barberid'] = 2;

            $sInsert = "INSERT INTO holiday (start_date,end_date,description,barberid,is_approved,responded) VALUES ("
                . $conn->quote($startdate) . ","
                . $conn->quote($enddate) . ","
                . $conn->quote($desc) . ","
                . $conn->quote($_SESSION['barber_userid']) . ","
                . "'0','0')";

            // echo $sInsert;
            $result = $conn->exec($sInsert);
            if ($result) {
                $Msg = "Application Successful";
                header("Location: barber-holidayinfo.php");
            } else {
                $Msg = "An error occured! Please try again later.";
            }
            // echo $Msg;
        }
    }
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    ?>
    <div class="content">
        <h1>Holiday Application</h1>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="datepicker">
            <div class="start-date" <?php if ($startdateErr != "" || $error != "") echo "style = \"border: 2px solid red;\""; ?>>
                <label for="startdate">Start Date</label>
                <input type="date" name="startdate" value="<?php if ($startdateErr == "") echo $startdate; ?>" title="Enter a valid start date" required>
            </div>
            <div class="end-date" <?php if ($enddateErr != "" || $error != "") echo "style = \"border: 2px solid red;\""; ?>>
                <label for="enddate">End Date</label>
                <input type="date" name="enddate" value="<?php if ($enddateErr == "") echo $enddate; ?>" title="Enter a valid end date" required>
                <span style="color:red;"><?php if ($error != "") echo $error ?></span>
            </div>
            <div class="desc" <?php if ($descErr != "") echo "style = \"border: 2px solid red;\""; ?>>
                <label for="description">Reason</label><br>
                <textarea id="description" name="description" rows="4" cols="50" title="Enter your holiday reason" required><?php if ($descErr == "") echo $desc; ?></textarea>
            </div>
            <input type="submit" value="Apply" class="Apply-btn">
        </form>
    </div>
    </body>

</html>