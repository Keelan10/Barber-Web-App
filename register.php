<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Register</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="styles/util.css">
	<link rel="stylesheet" type="text/css" href="styles/main.css">
<!--===============================================================================================-->
</head>
<?php 

    $fnameErr = $lnameErr = $phoneErr = $emailErr = $dobErr = $passwordErr = "";
    $fname = $lname = $phone = $email = $dob = $password = "";

    if ($_SERVER['REQUEST_METHOD'] == "POST"){

        if (empty($_POST['Firstname'])){
            $fnameErr = "First name is required";
        }
        else{
            $fname = test_input($_POST['Firstname']);
            if (!preg_match("/^[A-Z][a-z]*(['\-]{0,1}[A-Z][a-z]+)*$/",$fname)){
                $fnameErr = "First name should start with a capital letter and consists of alphabets only.";
            }
        }

        if (empty($_POST['Lastname'])){
            $lnameErr = "Last name is required";
        }
        else{
            $lname = test_input($_POST['Lastname']);
            if (!preg_match("/^[A-Z][a-z]*(['\-]{0,1}[A-Z][a-z]+)*$/",$lname)){
                $lnameErr = "Last name should start with a capital letter and consists of alphabets only.";
            }
        }

        if (empty($_POST['Phone'])){
            $phoneErr = "Phone number is required.";
        }
        else{
            $phone = test_input($_POST['Phone']);
            if (!preg_match("/^(5[24789]([0-9]{6}))|([2469]([0-9]{6}))$/",$phone)){
                $phoneErr = "Wrong format for phone number";
            }
        }

        if (empty($_POST['Email'])){
            $emailErr = "Email is required";
        }
        else {
            $email = test_input($_POST['Email']);
            if (!preg_match("/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$/",$email)){
                $emailErr = "Wrong format for email";
            }
        }

        if (empty($_POST['DOB'])){
            $dobErr = "Date of birth is required";
        }
        else {
            $dob = test_input($_POST['DOB']);
            $today = date("Y-m-d");
            // echo $dob."<br>";
            // echo $today."<br>";
            if ($dob > $today){
                $dobErr = "Invalid Date of birth";
            }
        }

        if (empty($_POST["Password"])) {
            $passwordErr = "Password is required";
        } 
        else {
            $password = $_POST["Password"];
            if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/",$password)){
                $passwordErr = "Wrong format";
            }
        }

        /*
        echo $fnameErr."<br>";
        echo $lnameErr."<br>";
        echo $phoneErr."<br>";
        echo $emailErr."<br>";
        echo $dobErr."<br>";
        echo $passwordErr."<br>";
        */

        if ($fnameErr =="" && $lnameErr =="" && $phoneErr=="" && $emailErr =="" && $dobErr =="" && $passwordErr == ""){
            require_once "includes/database.php";
            $msg = "";
            $hashed_password = password_hash($password,PASSWORD_DEFAULT);

            $SQL= "Select NOW();";
            $result=$conn->query($SQL);
            $now= $result->fetch()["NOW()"];

            $email = strtolower($email);
            
            // $insert = "INSERT INTO customer(first_name,last_name,phone,dob,email,password,last_active) VALUES ('".$fname."','".$lname."','".$phone."','".$dob."','".$email."','".$hashed_password."','$now')";
            
            $insert = "INSERT INTO customer(first_name,last_name,phone,dob,email,password,last_active) VALUES ("
                        .$conn->quote($fname).","
                        .$conn->quote($lname).","
                        .$conn->quote($phone).","
                        .$conn->quote($dob).","
                        .$conn->quote($email).","
                        .$conn->quote($hashed_password).","
                        .$conn->quote($now).")";

            
            // echo $insert;

            $result = $conn->exec($insert);

            if ($result){
                $msg = "Record saved";
                echo $msg;
                header("Location: login.php");
                // header("Location: index.php");
                die();
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
?>

<body>
    <?php if ($_SERVER['REQUEST_METHOD'] == "GET" || $fnameErr != "" || $lnameErr != "" || $phoneErr != "" || $emailErr != "" || $dobErr != "" || $passwordErr != ""){	?>
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-form-title" style="background-image: url(images/bg-02.jpg);">
					<span class="login100-form-title-1">
						Register
					</span>
				</div>

				<form class="login100-form validate-form" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" >

					<div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
						<span class="label-input100">First name</span>
						<input class="input100" type="text" name="Firstname" placeholder="Enter first name" value = "<?php if ($fnameErr == ""){echo $fname;} ?>" style = " <?php if ($fnameErr != ""){echo "border: 2px solid white; border-bottom: 2px solid red";}?>" title="Firstname should consists of letters and should start with a capital letter" pattern="[A-Z][a-z]*(['\-]{0,1}[A-Z][a-z]+)*$" required>
						<span class="focus-input100"></span>
					</div>

                    <div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
						<span class="label-input100">Last name</span>
						<input class="input100" type="text" name="Lastname" placeholder="Enter last name" value = "<?php if ($lnameErr == ""){echo $lname;} ?>" style = " <?php if ($lnameErr != ""){echo "border: 2px solid white; border-bottom: 2px solid red";}?>" title="Lastname should consists of letters and should start with a capital letter" pattern="[A-Z][a-z]*(['\-]{0,1}[A-Z][a-z]+)*$" required>
						<span class="focus-input100"></span>
					</div>

                    <div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
						<span class="label-input100">Phone</span>
						<input class="input100" type="tel" name="Phone" placeholder="Enter phone number" value = "<?php if ($phoneErr == ""){echo $phone;} ?>" style = " <?php if ($phoneErr != ""){echo "border: 2px solid white; border-bottom: 2px solid red";}?>" title ="Enter your phone number (Local one only)" pattern="(5[24789]([0-9]{6}))|([2469]([0-9]{6}))$" required>
						<span class="focus-input100"></span>
					</div>

                    <div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
						<span class="label-input100">Email</span>
						<input class="input100" type="email" name="Email" placeholder="Enter email" value = "<?php if ($emailErr == ""){echo $email;} ?>" style = " <?php if ($emailErr != ""){echo "border: 2px solid white; border-bottom: 2px solid red";}?>" title ="Enter your email (characters@characters.domain)" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required> 
						<span class="focus-input100"></span>
					</div>

                    <div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
						<span class="label-input100">Birth Date</span>
						<input class="input100" type="text" onfocus="(this.type='date')" name="DOB" placeholder="Enter date of birth" value = "<?php if ($dobErr == ""){echo $dob;} ?>" style = " <?php if ($dobErr != ""){echo "border: 2px solid white; border-bottom: 2px solid red";}?>" title ="Enter your date of birth (dd-mm-yyyy)" required>
						<span class="focus-input100"></span>
					</div>
				
					<div class="wrap-input100 validate-input m-b-18" data-validate = "Password is required">
						<span class="label-input100">Password</span>
						<input class="input100" type="password" name="Password" placeholder="Enter password" value = "<?php if ($passwordErr == ""){echo $password;} ?>" style = " <?php if ($passwordErr != ""){echo "border: 2px solid white; border-bottom: 2px solid red";}?>" title ="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$" required>
						<span class="focus-input100"></span>
					</div>

                    <div class="flex-sb-m w-full p-b-30">
						<div>
							<div class="txt1">
                                <br>Already have an account?
								<a href="login.php" class="txt1">Sign In</a>
							</div>
						</div>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							REGISTER
						</button>
					</div>

				</form>
			</div>
		</div>
	</div>
    <?php } ?>

</body>
</html>