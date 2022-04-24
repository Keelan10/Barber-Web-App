
    <script src="https://kit.fontawesome.com/65310733dc.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/history.css">
    <!-- include jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./js/navbar.js"></script>
    <link rel="icon" href="images/black-logo.png">
</head>

<body>
    <?php
        session_start();
        //temporary message! To do it properly afterwards
        if(!isset($_SESSION["customer_username"])||!(isset($_SESSION["customer_userid"]))){
    ?>

        <div class="error-msg">
            <i class="fa fa-times-circle"></i>
            This is a error message.
            <a href="login.php">Login</a> to continue
        </div>
    
    <?php        
        die;
        }
    ?>


    <nav class="navbar navbar-expand-custom navbar-mainbg">
        <a class="navbar-brand navbar-logo" href="#"><b>Jack of all Fades</b></a>
        <button class="navbar-toggler" type="button" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars text-white"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <div class="hori-selector">
                    <div class="left"></div>
                    <div class="right"></div>
                </div>
                <li <?php if ($active_menu  == "step"){ echo "class='nav-item active'";}else{echo "class='nav-item'";} ?> >
                    <a class="nav-link" href="book-appointment.php"><i class="fas fa-calendar-check"></i>Book Appointments</a>
                </li>
                <li <?php if ($active_menu  == "schedule"){ echo "class='nav-item active'";}else{echo "class='nav-item'";} ?>>
                    <a class="nav-link" href="my-schedule.php"><i class="fa fa-clock-o"></i>My Schedule</a>
                </li>
                <li <?php if ($active_menu  == "visit"){ echo "class='nav-item active'";}else{echo "class='nav-item'";} ?>>
                    <a class="nav-link" href="visit-history.php"><i class="fa fa-calendar"></i>Visit history</a>
                </li>
                <li <?php if ($active_menu  == "purchase"){ echo "class='nav-item active'";}else{echo "class='nav-item'";} ?>>
                    <a class="nav-link" href="order-history.php"><i class="fas fa-shopping-cart"></i>Purchase History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">
                        <i class="fa fa-sign-out" aria-hidden="true"></i>Logout</a>
                </li>
            </ul>
        </div>
    </nav>
