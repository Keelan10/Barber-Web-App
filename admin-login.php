<!-- TO DO: PATTERN FOR EMAIL-->
<?php

    session_start();
    $emailErr = $passwordErr = "";
    $email = $password = "";
	$Msg="";

    if($_SERVER["REQUEST_METHOD"]=="POST"){

        //test email input
        if (empty($_POST["Email"])) {
            $emailErr = "Email is required";
        }else {
            $email = test_input($_POST["Email"]);
            if (!preg_match("/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$/",$email)) { //TO DO pattern for email
                $emailErr = "Enter a proper email"; 
            }
        }

        //test password input
        if (empty($_POST["Password"])) {
            $passwordErr = "Password is required";
        }else {
            $password = test_input($_POST["Password"]);
			if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/",$password)){
                $passwordErr = "Wrong format";
            }
        }
    

		if($emailErr == "" && $passwordErr == "" ){
			require_once "includes/database.php";
			$email = strtolower($email);

			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$sQuery = "SELECT * FROM admin WHERE email = '$email'  ";

			$Result = $conn->query($sQuery);

			$userResults = $Result->fetch(PDO::FETCH_ASSOC);

			if(($userResults)){//if exists
				$hashed_password = $userResults['password'];
                // echo $password."<br>";
                // echo $hashed_password."<br>";
				if(password_verify($password,$hashed_password)){
					$_SESSION['admin_username'] = $userResults["first_name"] . " ".$userResults["last_name"];
					$_SESSION['admin_userid']=$userResults["adminid"];
                    header("Location: dashboard.php");
					die();
				}
				else
				{
					$Msg = "Your credentials seem to be wrong" ."<br>". "Try again or make sure you are a registered admin!";
					// echo $Msg;
				}
			}else{
				$Msg = "Your credentials seem to be wrong" ."<br>". "Try again or make sure you are a registered admin!";
				// echo $Msg;
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

<!DOCTYPE html>
<html lang="en">
<head>
	<title> Admin Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="styles/util.css">
	<link rel="stylesheet" type="text/css" href="styles/main.css">
<!--===============================================================================================-->
	<link rel="icon" href="images/black-logo.png">

</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-form-title" style="background-image: url(images/bg-02.jpg);">
					<span class="login100-form-title-1">
						Admin Sign In
					</span>
				</div>

				<form class="login100-form validate-form" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
					<div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
						<span class="label-input100">Email</span>
						<input class="input100" type="text" name="Email" placeholder="Enter email" value = "<?php if ($emailErr == ""){echo $email;} ?>" style = " <?php if ($emailErr != ""){echo "border: 2px solid white; border-bottom: 2px solid red";}?>" title ="Enter your email (characters@characters.domain)" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required>
						<span class="focus-input100"></span>
					</div>
				
					<div class="wrap-input100 validate-input m-b-18" data-validate = "Password is required">
						<span class="label-input100">Password</span>
						<input class="input100" type="password" name="Password" placeholder="Enter password" value = "<?php if ($passwordErr == ""){echo $password;} ?>" style = " <?php if ($passwordErr != ""){echo "border: 2px solid white; border-bottom: 2px solid red";}?>" title ="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"  pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$" required>
						<span class="focus-input100"></span>
					</div>

					<div class="flex-sb-m w-full p-b-30">

						<div>
							<div class="txt1">
								<?php if ($Msg != ""){ ?>
                                    <br><br>
									<div class="error" style="color: red">
										<?php echo $Msg ?>
									</div>
									
								<?php }?>
							</div>
						</div>
						
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Login
						</button>
					</div>

                </form>

			</div>
		</div>
	</div>

</body>
</html>