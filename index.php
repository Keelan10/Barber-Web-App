<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="styles/index.css">
    <script src="https://kit.fontawesome.com/65310733dc.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="icon" href="images/black-logo.png">
    <style>
        body{
            overflow-x:hidden;
        }

        .log-btn{
            cursor:pointer;color:white;padding:0.25rem 1rem;background:#ce9c6b;border-radius:5px;border-color:transparent
        }
        .log-btn:hover{
            border: 1px solid #ce9c6b;
            background-color: transparent;
            color:#ce9c6b;
        }

    </style>
    <script>
        $(document).ready(()=>{
            highlightLink();
    
            const date=new Date();
            $("#year").text(date.getFullYear());

            $(window).scroll(highlightLink);
            
            function highlightLink(){
                if($(window).scrollTop()>=410){
                    $(".navbar").addClass("new-background");
                }else{
                    $(".navbar").removeClass("new-background");
                }
    
                if ($(window).scrollTop()<=2284){
                    $("#nav-home").addClass("active");
    
                    $("#nav-about").removeClass("active");
                    $("#nav-services").removeClass("active");
    
                }else if($(window).scrollTop()<=3430){
                    $("#nav-services").addClass("active");
    
                    $("#nav-home").removeClass("active");
                    $("#nav-about").removeClass("active");
                }
                else if($(window).scrollTop()<=5218){
                    $("#nav-about").addClass("active");
    
                    $("#nav-home").removeClass("active");
                    $("#nav-services").removeClass("active");
                }
            }
        });
        
    
    </script>
</head>
<body>
    
    <div class="hero-wrapper">
        <nav class="navbar">
            <div class="nav-center">
                    <a href="#" class="nav-logo"><img id="logo" src="images/logo.png" alt=""></a>            
                <ul class="nav-links">
                    <li><a href="#" id="nav-home" >Home</a></li>
                    <li><a href="#services" id="nav-services">Services</a></li>
                    <li><a href="#info" id="nav-about">About</a></li>
                    <li><a href="shop.php" id="nav-shop" target=”_blank”>Shop</a></li>
                    <li>
                        <?php 
                            if(isset($_SESSION['customer_username']) && isset($_SESSION['customer_userid'])){
                                echo '<a href="logout.php"><button class="log-btn">Logout</button></a>';
                            }
                            else{
                                echo '<a href="login.php" target="_blank"><button class="log-btn">Login</button></a>';
                            }
                        ?>
                    </li>
                </ul>
            </div>    
        </nav>
        <div class="container">
            <div class="text-container">
                <h4>jack of all fades</h4>
                <h1>❝ We cut hair, not corners ❞</h1>
                <a href="<?php 
                            if(isset($_SESSION['customer_username']) && isset($_SESSION['customer_userid'])){
                                echo 'book-appointment.php';
                            }
                            else{
                                echo 'login.php?referrer=book';
                            }
                        ?>" class="btn">
                    Book an appointment
                </a>
            </div>
            
        </div>
    </div>
    <section class="intro">

        <div class="container">
            <h2>welcome to <span class="title">jack of all fades</span> hair salon</h2>
            <p class="center">Jack of all Fades Barber Shop specializes in classic cuts, beard trims, hot towel shaves and quality grooming products. Our team of professional and knowledgeable barbers work to help you achieve your best look. We’re a little bit old school with a modern touch. We thrive on great music, thoughtful conversation and fostering relationship in our community. We’ve created a laid back environment for your regular dose of self-care.</p>
        </div>
        
        <section class="info container">
            <div>
                <h3>hours</h3>
                <div class="time">
                    <p class="center">Mon - Fri : 8:00-20:00</p>
                    <p class="center">Sat : 8:00-16:00</p>
                    <p class="center">Sun : closed </p>
                    <p class="center">Holidays: 8:00-12:00</p>
                </div>
                
            </div>
            <div>
                <h3>contact us</h3>
                <p class="center">Contact us on:</p>
                <p class="center"><a href="tel:+230 57655508" class="tel-num">+230 59294259</a></p><br>
                <p class="center">Email us on:</p>
                <p class="center"><a href = "mailto: kezhilen.motean@umail.uom.ac.mu" class="email">JOAF@gmail.com</a></p>
            </div>
            <div>
                <h3>location</h3>   
                <p class="center">Level 1</p>
                <p class="center">Phase II,FoICDT</p>
                <p class="center">University of Mauritius</p>                
                <!-- <div class="map-center">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3100.148699988765!2d57.4941107!3d-20.2349416!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x217c5ae895555555%3A0x6cf4c4a3d4cea297!2sUniversity%20of%20Mauritius!5e1!3m2!1sen!2smu!4v1639567501746!5m2!1sen!2smu" width="100%" height="300px" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div> -->
            </div>
        </section>
    </section>

    <section class="music">
        <div class="container">
            <h3>Let the music do the talking</h3>
            <iframe src="https://open.spotify.com/embed/artist/3DoTxN5SX13QpC2mrjko2O?utm_source=generator&theme=0" width="100%" height="400" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"></iframe>
            <!-- <iframe src="https://open.spotify.com/embed/artist/3DoTxN5SX13QpC2mrjko2O?utm_source=generator" width="100%" height="400" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"></iframe> -->
        </div>

    </section>


    <section id="services" class="services">
        <div class="container">
            <div class="header-design">
                    <img class="header-img" style="width:300px;height: auto;" src="images/moustache1.png" alt="">
                    <div class="line"></div>        
            </div>
            <h2>The Menu</h2>

            <?php
                require_once "includes/database.php";
                $sSelect = "SELECT name,price,description FROM service;";
                $result = $conn->query($sSelect);
                $rowcount = $result->rowcount();
                if ($rowcount == 0){
                    echo "<h3>No service on display right now!</h3>";
                }
                else{
            ?>

            <div class="menu">
                <?php 
                    while($row = $result->fetch()){

                ?>
                    <article>
                        <h3><?php echo $row['name']."  Rs ".round($row['price'],0) ?></h3>
                        <p class="center"><?php echo $row['description'] ?></p>
                    </article>
                <?php 
                        } 
                    }
                ?>
            </div>
            <div style="display: flex;justify-content: center;">


                <a href="<?php 
                            if(isset($_SESSION['customer_username']) && isset($_SESSION['customer_userid'])){
                                echo 'book-appointment.php';
                            }
                            else{
                                echo 'login.php?referrer=book';
                            }
                        ?>" id="services-book-btn">Book</a>
                
            </div>
            
        </div>
    </section>

    <section id="info" class="team">
        <div class="header-design">
            <img class="header-img" style="width:300px;height: auto;" src="./images/scissors-and-comb-vector-1600292.jpg" alt="">      
        </div>
        <h2>meet the family</h2>
        <section class="barbers">
            <div class="flip-card">
                <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <img src="./images/barber1.jpg" alt="Avatar" style="width:350px;height:300px;">
                    </div>
                    <div class="flip-card-back">
                        <h1>Kenylen Motean</h1>
                        <p>Age: 25yrs</p>
                        <p>Experience: 5yrs</p>
                        <p>Specialties: Beard Trim, Fade</p>
                    </div>
                </div>
            </div>
            <div class="flip-card">
                <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <img src="./images/barber2.jpg" alt="Avatar" style="width:350px;height:300px;">
                    </div>
                    <div class="flip-card-back">
                        <h1>Keelan Cannoo</h1>
                        <p>Age: 25yrs</p>
                        <p>Experience: 5yrs</p>
                        <p>Specialties: Beard Trim, Fade</p>
                    </div>
                </div>
            </div>
            <div class="flip-card">
                <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <img src="./images/barber4.png" alt="Avatar" style="width:350px;height:300px;">
                    </div>
                    <div class="flip-card-back">
                        <h1>Kezhilen Motean</h1>
                        <p>Age: 25yrs</p>
                        <p>Experience: 5yrs</p>
                        <p>Specialties: Beard Trim, Fade</p>
                    </div>
                </div>
            </div>
        </section>
    </section>

    <section class="interior">
        <div class="int-img">
            <img src="./images/interior1.jpeg" alt="" width="100%">
		</div>
		<div class="int-txt">
			<div class="int-logo">
				<img src="./images/logo.png" alt="" width="100px">
				<div class="int-line"></div>
			</div>
            <p class="int-desc" style="max-width:100%;margin:2rem 0;">Our barbershop is definitely one of the most unique that you will come across anytime soon with our comfortable setting and old school look with a twist of new school our shop is one to impress all that come to it for the first time. With a cozy atmosphere, we thrive to become the most trusted grooming destination in Reduit.</p>
		</div>
	</section>

    <footer>
        <div class="container about-container">
            <div class="about">
                <div class="center">
                <img height=200px width=200px src="images/logo.png" alt="">
                </div>
                <ul class="media">
                    <li>
                        <a href="#" ><i class="fab fa-twitter"></i></a>
                    </li>
                    <li>
                        <a href="#"><i class="fab fa-facebook"></i></a>
                    </li>
                    <li>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </li>
                </ul>
            </div>
            <div class="about-services">
                <h2>Jack Of All Fades</h2>
                <p>Founded in 2022, Jack of all fades is The Authority in Men's Grooming. Get an exceptional haircut and shave from one of our friendly and skillful professionals. <br><br>Relax, Look Great, Feel Confident.
                    <br>Our Services are available to all members of the public regardless of race, gender or sexual orientation.
                </p>
            </div>
            <div class="contact">
                <h2>contact us</h2>
                <p>Contact us on:</p>
                <p><a href="tel:+230 57655508" class="tel-num">+230 59294259</a></p><br>
                <p>Email us on:</p>
                <p><a href = "mailto: kezhilen.motean@umail.uom.ac.mu" class="email">JOAF@gmail.com</a></p><br>
                <p>Location:</p>
                <p>University Of Mauritius</p>
            </div>
        </div>
            <p class="center">Copyright &copy; <span id="year"></span> All rights reserved</p> 
    </footer>
</body>
</html>