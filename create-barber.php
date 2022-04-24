<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Barber</title>

<?php
        $activeMenu="accounts";
        include_once("includes/admin-menu.php");

        $fnameErr = $lnameErr = $phoneErr = $emailErr = $streetErr = $cityErr = $dobErr = $passwordErr = "";
        $fname = $lname = $phone = $email = $street = $city = $dob = $password = "";

        if ($_SERVER['REQUEST_METHOD'] == "POST"){

            if (empty($_POST['Firstname'])){
                $fnameErr = "First name is required";
                // echo $fnameErr;
            }
            else{
                $fname = test_input($_POST['Firstname']);
                if (!preg_match("/^[A-Z][a-z]*(['\-]{0,1}[A-Z][a-z]+)*$/",$fname)){
                    $fnameErr = "First name should start with a capital letter and consists of alphabets only.";
                    //echo $fnameErr;
                }
            }

            if (empty($_POST['Lastname'])){
                $lnameErr = "Last name is required";
                // echo $lnameErr;
            }
            else{
                $lname = test_input($_POST['Lastname']);
                if (!preg_match("/^[A-Z][a-z]*(['\-]{0,1}[A-Z][a-z]+)*$/",$lname)){
                    $lnameErr = "Last name should start with a capital letter and consists of alphabets only.";
                    //echo $lnameErr;
                }
            }

            if (empty($_POST['Phone'])){
                $phoneErr = "Phone number is required.";
                //echo $phoneErr;
            }
            else{
                $phone = test_input($_POST['Phone']);
                if (!preg_match("/^(5[24789]([0-9]{6}))|([2469]([0-9]{6}))$/",$phone)){
                    $phoneErr = "Wrong format for phone number";
                    //echo $phoneErr;
                }
            }

            if (empty($_POST['Email'])){
                $emailErr = "Email is required";
                //echo $emailErr;
            }
            else {
                $email = test_input($_POST['Email']);
                if (!preg_match("/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$/",$email)){
                    $emailErr = "Wrong format for email";
                    //echo $emailErr;
                }
            }

            if (empty($_POST['Street'])){
                $streetErr = "Street name is required";
                //echo $streetErr;
            }
            else{
                $street = test_input($_POST['Street']);
                if (!preg_match("/^[A-Z][a-z]+([\'\-\s](([A-Z][a-z]+)|[a-z]+))*$/",$street)){
                    $streetErr = "Street name should start with a capital letter and consists of alphabets only.";
                    //echo $streetErr;
                }
            }

            if (empty($_POST['City'])){
                $cityErr = "City name is required";
                //echo $cityErr;
            }
            else{
                $city = test_input($_POST['City']);
                if (!preg_match("/^[A-Z][a-z]+([\-\s][A-Z][a-z]+)*$/",$city)){
                    $cityErr = "City name should start with a capital letter and consists of alphabets only.";
                    //echo $cityErr;
                }
            }

            if (empty($_POST['DOB'])){
                $dobErr = "Date of birth is required";
                //echo $dobErr;
            }
            else {
                $dob = test_input($_POST['DOB']);
                $today = date("Y-m-d");
                // echo $dob."<br>";
                // echo $today."<br>";
                if ($dob > $today){
                    $dobErr = "Invalid Date of birth";
                    //echo $dobErr;
                }
            }

            if (empty($_POST["Password"])) {
                $passwordErr = "Password is required";
                //echo $passwordErr;
            } 
            else {
                $password = $_POST["Password"];
                if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/",$password)){
                    $passwordErr = "Wrong password format";
                    //echo $passwordErr;
                }
            }

            if ($fnameErr =="" && $lnameErr =="" && $phoneErr =="" && $emailErr =="" && $streetErr =="" && $cityErr =="" && $dobErr =="" && $passwordErr == ""){
                require_once "includes/database.php";
                $msg = "";
                $hashed_password = password_hash($password,PASSWORD_DEFAULT);
    
                $SQL= "Select NOW();";
                $result=$conn->query($SQL);
                $now= $result->fetch()["NOW()"];
    
                $email = strtolower($email);
                $sInsert = "INSERT INTO barber (first_name,last_name,phone,dob,email,password,last_active,street,city) VALUES ("
                            .$conn->quote($fname).","
                            .$conn->quote($lname).","
                            .$conn->quote($phone).","
                            .$conn->quote($dob).","
                            .$conn->quote($email).","
                            .$conn->quote($hashed_password).","
                            .$conn->quote($now).","
                            .$conn->quote($street).","
                            .$conn->quote($city).")";
    
                $result = $conn->exec($sInsert);
    
                if ($result){
                    $msg = "Record saved";
                    // echo $msg;
                    // header("Location: create-barber.php");
                    // header("Location: index.php");
                    // die();
                }
                else{
                    $msg = "ERROR: Your credentials could not be saved!";
                    echo $msg;
                }
            }
        }

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }


        if ($_SERVER['REQUEST_METHOD'] == "POST" || $fnameErr != "" || $lnameErr != "" || $phoneErr != "" || $emailErr != "" || $streetErr != "" || $cityErr != "" || $dobErr != "" || $passwordErr != ""){
            $fname = $lname = $phone = $email = $street = $city = $dob = $password = "";
        }

    ?>
    <main class="content">
                <div class="container-fluid p-0">

                    <div class="mb-3">
                        <h1 class="h3 d-inline align-middle">Create Barber Account</h1>
                    </div>
                    <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
                    <div class="row">
                        
                        <div class="col-12 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Barber Name
                                        <?php if ($fnameErr != ""){ echo "<p class = 'error'>$fnameErr</p>";}?>
                                        <?php if ($lnameErr != ""){ echo "<p class = 'error'>$lnameErr</p>";}?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <input type="text" class="form-control" placeholder="Last name" name="Lastname" value = "<?php if ($lnameErr == ""){echo $lname;} ?>" title="Lastname should consists of letters and should start with a capital letter" pattern="[A-Z][a-z]*(['\-]{0,1}[A-Z][a-z]+)*$" required>
                                </div>
                                <div class="card-body">
                                    <input type="text" class="form-control" placeholder="First name" name="Firstname" value = "<?php if ($fnameErr == ""){echo $fname;} ?>" title="Firstname should consists of letters and should start with a capital letter" pattern="[A-Z][a-z]*(['\-]{0,1}[A-Z][a-z]+)*$" required> 
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Barber Phone Number
                                        <?php if ($phoneErr != ""){ echo "<p class = 'error'>$phoneErr</p>";}?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <input type="tel" class="form-control" placeholder="Phone Number" name="Phone" value = "<?php if ($phoneErr == ""){echo $phone;} ?>" title ="Enter your phone number (Local one only)" pattern="(5[24789]([0-9]{6}))|([2469]([0-9]{6}))$" required>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Barber Email
                                        <?php if ($emailErr != ""){ echo "<p class = 'error'>$emailErr</p>";}?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <input type="email" class="form-control" placeholder="Email" name="Email" value = "<?php if ($emailErr == ""){echo $email;} ?>" title ="Enter your email (characters@characters.domain)" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Barber Address
                                        <?php 
                                        if ($streetErr != ""){ 
                                            echo "<p class = 'error'>$streetErr</p>";}
                                            echo $cityErr;
                                        if ($cityErr != ""){ echo "<p class = 'error'>$cityErr</p>";}
                                        ?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <input type="text" class="form-control" placeholder="Street" name="Street" value="<?php if ($streetErr==""){echo $street;}?>" title="Enter a valid Street name" pattern="[A-Z][a-z]+([\'\-\s](([A-Z][a-z]+)|[a-z]+))*$" required><br><br>
                                    <input type="text" class="form-control" placeholder="City" name="City" value="<?php if ($cityErr==""){echo $city;}?>" title="Enter a valid City name" pattern="[A-Z][a-z]+([\-\s][A-Z][a-z]+)*$" required>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Barber Password
                                        <?php if ($passwordErr != ""){ echo "<p class = 'error'>$passwordErr</p>";}?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <input type="password" class="form-control" placeholder="Password" name="Password" value = "<?php if ($passwordErr == ""){echo $password;} ?>" title ="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$" required>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Barber Date of Birth
                                        <?php if ($dobErr != ""){ echo "<p class = 'error'>$dobErr</p>";}?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                <input type="text" class="form-control" placeholder="Date of Birth" onfocus="(this.type='date')" name="DOB" value = "<?php if ($dobErr == ""){echo $dob;} ?>" title ="Enter your date of birth (dd-mm-yyyy)" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="submit" value="Ok" class="form-control ok-btn" style="width: 10%;">
                    </form>
                </div>
            </main>
        </div><!--end of main div-->
    </div><!--end of wrapper div-->
    <?php //} ?>
</body>
</html>