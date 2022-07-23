<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book appointment</title>
    <style>
    </style>
    <?php
    require_once "includes/database.php";
    
    $active_menu = "step";
    include "includes/customer-menu.php";
    $barberErr = $serviceErr =  $yearErr = $monthErr = $dayErr = $timeErr = "";
    $barber = "";
    $services = array();


    $sSelect = 'SELECT barberid, concat(first_name," ",last_name) AS barbername from barber';

    $SQL = "SELECT * FROM service";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty($_POST['txt_barber'])) {
            $barberErr = "A barber name is required!";
        } else {
            $barber = $_POST['txt_barber'];
        }

        $flag = false;
        $index = array();
        $result = $conn->query($SQL);

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $pieces = explode(" ", $row["name"]);
            $name = "";

            for ($i = 0; $i < sizeof($pieces) - 1; $i++) {
                $name .= $pieces[$i] . "_";
            }

            $name .= $pieces[sizeof($pieces) - 1];
            array_push($index, $name);
        }

        $checkboxEmpty = true;

        for ($i = 0; $i < sizeof($index); $i++) {
            if (isset($_POST[$index[$i]])) $checkboxEmpty = false;
        }
        if ($checkboxEmpty) $serviceErr = "Atleast a service is required!";


        if (empty($_POST['year'])) {
            $yearErr = "Year is required!";
        } 
        if (empty($_POST['month'])) {
            $monthErr = "Month is required!";
        } 
        if (empty($_POST['day'])) {
            $dayErr = "Day is required!";
        } 
        if (empty($_POST['time'])) {
            $timeErr = "Time is required!";
        } 
        
        // If there is no error, insert to database and then redirect to my-schedule.php
        if( $barberErr == "" && $serviceErr == ""  && $yearErr == "" && $monthErr == "" && $dayErr == ""  && $timeErr == "" ){

            // date_parse converts month to array 
            // array["month"] gives month number
            $date = $_POST["year"]."-".date_parse($_POST["month"])["month"]."-".$_POST["day"];
            
            // insert into payment
            $insertPayment = $conn->prepare("insert into payment(paymentdate) VALUES (CURDATE());");
            
            
            // insert into transactions
            $insertTransaction = $conn->prepare("insert into transactions (customerid,paymentid) VALUES(?, LAST_INSERT_ID());");
            
            // insert into appointment
            $insertAppointment = $conn->prepare("insert into appointment (transactionid,appointmentdate, start_time,barberid) VALUES (LAST_INSERT_ID(),?,?,(SELECT barberid FROM barber WHERE CONCAT(first_name,' ',last_name)=?));");
            
            // Fetch service id and price 
            $getService = $conn->prepare("select serviceid, price from service where name=?");
            
            // insert into appointmentdetails
            $insertAppointmentDetails = $conn->prepare("insert into appointmentdetails(transactionid,serviceid, price) VALUES (?,?,?);");

            // get transactionid
            $getTransactionIDStatement = "select LAST_INSERT_ID() as transactionid"; 


            try{
                $conn->beginTransaction();

                $insertPayment->execute();
                $insertTransaction->execute(array($_SESSION["customer_userid"]));
                $insertAppointment->execute(array($date,$_POST["time"],$_POST["txt_barber"]));
                
                foreach($conn->query($getTransactionIDStatement) as $row){
                    $transactionID = $row["transactionid"];
                }

                // Checking which services are checked and inserting them to database
                for ($j = 0; $j < sizeof($index); $j++) {  

                    if (isset($_POST[$index[$j]])){
                        // fetch service id and price
                        $getService->execute(array($_POST[$index[$j]]));
                        $row= $getService->fetch();
                        
                        $insertAppointmentDetails->execute(array($transactionID,$row["serviceid"],$row["price"])); 
                    }
                }

                $conn->commit();
                header('Location: http://localhost/Barber-Web-App/my-schedule.php');

            }catch(\PDOException $e){
                $conn->rollBack();
                echo "failure";

                // show the error message
                die($e->getMessage());
            }


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

    <script src="js/book.js"></script>

    <div class="content" style="margin-bottom:5rem;margin-top:5rem">
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">

            <table class="firstForm">
                <h1 class="FormHeader">Choose your barber and services</h1>
                <tbody>
                    <tr>
                        <td><label for="barber-choice" style="font-size: 14px;">Choose your barber: </label><?php if ($barberErr != "") { ?><span class="error"> <?php echo $barberErr ?> </span><?php } ?></td>
                        <td>
                            <?php
                            $result = $conn->query($sSelect);
                            $rowcount = $result->rowcount();
                            if ($rowcount == 0) {
                                echo "No barber available right now!";
                            } else {
                            ?>
                                <div class="select-style">
                                    <select name="txt_barber" id="barber">
                                        <option value="" selected>Please select a barber</option>
                                        <?php 
                                            while($row=$result->fetch()){
                                                echo "<option id=".$row["barberid"]." value='".$row["barbername"]."'";
                                                if ($barber==$row["barbername"]) echo "selected";
                                                echo ">".$row['barbername']."</option>";
                                
                                            }
                                        ?>                                        
                                    </select>
                                </div>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table><br>

            <table class="firstForm">
                <tbody>
                    <tr>
                        <td><label for="service-choice" style="font-size: 14px;">Choose your services: </label><?php if ($serviceErr != "") { ?><span class="error"> <?php echo $serviceErr ?> </span><?php } ?></td>
                    </tr>
                </tbody>
            </table><br>

            <?php
            $result = $conn->query($SQL);
            $rowcount = $result->rowCount();
            $SQL = "SELECT * FROM service";
            if ($rowcount == 0) {
                echo "No services available right now!";
            } else {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $pieces = explode(" ", $row["name"]);
                    $name = "";
                    for ($i = 0; $i < sizeof($pieces) - 1; $i++) {
                        $name .= $pieces[$i] . "_";
                    }
                    $name .= $pieces[sizeof($pieces) - 1];
                    //names having two or more words are separated by underscore in $_POST index 
                    //for example 'beard_trim' becomes 'beard_trim' and should be accessed as $POST["beard_trim"]
            ?>

                    <div class="select-services firstForm">
                        <input class="service" duration="<?php echo $row["duration"] ?>" type="checkbox" <?php
                                                echo "name='" . $row["name"] . "' ";
                                                echo "id='" . $row["serviceid"] . "' ";
                                                echo "value='" . $row["name"] . "' ";
                                                echo "class='firstForm'";
                                                if (isset($_POST[$name])) echo "checked";
                                                ?>>
                        <label for="<?php echo $row["serviceid"]; ?>"><?php echo $row["name"]; ?></label><br>
                    </div>
            <?php
                    if (isset($_POST[$name])) array_push($services, $row["name"]);
                } //end while
            }
            ?>
            <br>
            <div style="font-size:14px;margin-bottom:0rem">
                Choose date and time of your appointment: <span class="error"> <?php echo $yearErr. " " .$monthErr. " ".$dayErr ." ". $timeErr ?> </span>
            </div>
            <div class="" style="display:grid;grid-template-columns: 1fr 1fr 1fr 1fr;margin-bottom:">
                <select name="year" id="year" class="timeForm"></select>
                <select name="month" id="month" class="timeForm"></select>
                <select name="day" id="day" class="timeForm"></select>
                <select name="time" id="time" class="timeForm"></select>
            </div>
            <input type="submit" value="Confirm" class="Submit-btn">

        </form>
    </div>
    </body>

</html>