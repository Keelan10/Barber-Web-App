<!-- TO DO: PATTERN FOR EMAIL-->
<?php

    session_start();
    $emailErr = $passwordErr = "";
    $email = $password = "";
	$Msg="";

	// echo "---------------------------------".$_GET["referrer"];

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

			$sQuery = "SELECT * FROM customer WHERE email = '$email'  ";

			$Result = $conn->query($sQuery);

			$userResults = $Result->fetch(PDO::FETCH_ASSOC);

			if(($userResults)){//if exists
				$hashed_password = $userResults['password'];
				if(password_verify($password,$hashed_password)){
					$_SESSION['customer_username'] = $userResults["first_name"] ." ".$userResults["last_name"];
					$_SESSION['customer_userid']=$userResults["customerid"];
					// header("Location: index.php?referer=login");//Redirect to customer page

					if(isset($_GET["referrer"])){
						if($_GET["referrer"]=="book"){
							header("Location: book-appointment.php");
							die();
						}
					}
					header("Location: my-schedule.php");
					die();
				}
				else
				{
					$Msg = "Your credentials seem to be wrong" ."<br>". "Try again or make sure you are a registered user!";
					// echo $Msg;
				}
			}else{
				$Msg = "Your credentials seem to be wrong" ."<br>". "Try again or make sure you are a registered user!";
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
	<title>Login</title>
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
						Sign In
					</span>
				</div>

				<?php
					$phpSelf=$_SERVER["PHP_SELF"];
					if (isset($_GET["referrer"])){
						$phpSelf.="?referrer=".$_GET["referrer"];
					}
				?>
				<form class="login100-form validate-form" method="post" action="<?php echo  $phpSelf;?>">
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
						<!-- <div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
							<label class="label-checkbox100" for="ckb1">
								Remember me
							</label>
						</div> -->

						<div>
							<div class="txt1">
								<br>Not a member?
								<a href="register.php" class="txt1">Sign Up</a>
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