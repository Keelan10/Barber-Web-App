<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Book appointment</title>
        <?php 
            require_once "includes/database.php";

            $active_menu = "step";
            include "includes/customer-menu.php";
            $barberErr = $serviceErr = "";
            $barber = "";
            $services=array();

        
            $sSelect = 'SELECT concat(first_name," ",last_name) AS barbername from barber';

            $SQL= "SELECT * FROM service";

            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                
                    if (empty($_POST['txt_barber'])){
                        $barberErr = "A barber name is required!";
                    }
                    else{
                        $barber = $_POST['txt_barber'];
                    }
                    
                $flag=false;
                $index=array();
                $result=$conn->query($SQL);

                while($row=$result->fetch(PDO::FETCH_ASSOC)){

                    $pieces=explode(" ",$row["name"]);
                    $name="";

                    for($i=0;$i<sizeof($pieces)-1;$i++){
                        $name.=$pieces[$i]."_";
                    }

                    $name.=$pieces[sizeof($pieces)-1];
                    array_push($index,$name);
                }

                //print_r($index);
                $checkboxEmpty=true;

                for($i=0;$i<sizeof($index);$i++){
                    if(isset($_POST[$index[$i]])) $checkboxEmpty=false;
                }
                if ($checkboxEmpty) $serviceErr="Atleast a service is required!";


                    // echo $barber. "<br>";
                    // list($fname,$lname)=explode(" ",$barber);
                    // echo $fname. "<br>";
                    // echo $lname. "<br>";  
                
            }

            function test_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }  

        ?>
        
        <div class="content">
            <form action="<?php echo $_SERVER["PHP_SELF"];?>" method = "post" >
                <h1>Choose your barber and services</h1>
                <table>
                    <tbody>
                        <tr>
                            <td><label for="barber-choice" style="font-size: 14px;">Choose your barber: </label><?php if ($barberErr != ""){?><span class = "error"> <?php echo $barberErr ?> </span><?php } ?></td>
                            <td>     
                                <?php             
                                    $result = $conn->query($sSelect);
                                    $rowcount = $result->rowcount();
                                    if ($rowcount == 0){
                                        echo "No barber available right now!";
                                    }
                                    else {
                                ?>       
                                    <div class="select-style">
                                        <select name="txt_barber" id="barber-choice" >
                                            <option value= "" selected >Please select a barber</option>
                                            <?php while ($row = $result->fetch()){ ?>
                                                <option value="<?php echo $row['barbername'] ?>" <?php if ($barber == $row['barbername']){echo "selected";} ?>><?php echo $row['barbername'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                <?php 
                                    }
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table><br>
                
                <table>
                    <tbody>
                        <tr>
                            <td><label for="service-choice" style="font-size: 14px;">Choose your services: </label><?php if ($serviceErr != ""){?><span class = "error"> <?php echo $serviceErr ?> </span><?php } ?></td>
                        </tr>
                    </tbody>
                </table><br>

                <?php 
                    $result=$conn->query($SQL);
                    $rowcount = $result->rowCount();$SQL= "SELECT * FROM service";
                    if ($rowcount == 0){
                        echo "No services available right now!";
                    }
                    else{
                        while($row=$result->fetch(PDO::FETCH_ASSOC)){
                            $pieces=explode(" ",$row["name"]);
                            $name="";
                            for($i=0;$i<sizeof($pieces)-1;$i++){
                                $name.=$pieces[$i]."_";
                            }
                            $name.=$pieces[sizeof($pieces)-1];
                            //names having two or more words are separated by underscore in $_POST index 
                            //for example 'beard_trim' becomes 'beard_trim' and should be accessed as $POST["beard_trim"]
                ?>

                    <div class="select-services">
                        <input type="checkbox" 
                            <?php
                                echo "name='".$row["name"]."' ";
                                echo "id='".$row["serviceid"]."' ";
                                echo "value='".$row["name"]."' ";
                                if(isset($_POST[$name])) echo "checked";
                            ?>
                        >
                        <label for="<?php echo $row["serviceid"];?>"><?php echo $row["name"];?></label><br>
                    </div>
                <?php
                    if(isset($_POST[$name])) array_push($services,$row["name"]) ;
                    }//end while
                }
                ?>
                
                <input type="submit" value="Next" class="Step-btn">
            </form> 
        </div>
    </body>
</html>