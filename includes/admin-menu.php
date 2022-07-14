<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="./js/admin.js"></script>
    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet" type='text/css'>
    <script src="https://kit.fontawesome.com/65310733dc.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles/admin-style.css">
    <link rel="icon" href="images/black-logo.png">
    </head>

    <body>
        <?php
        session_start();
        if (!isset($_SESSION["admin_username"]) || !(isset($_SESSION["admin_userid"]))) {
        ?>

            <div class="error-msg">
                <i class="fa fa-times-circle"></i>
                This is a error message.
                <a href="admin-login.php">Login</a> to continue
            </div>

        <?php
            die;
        }
        ?>

        <div class="wrapper">
            <nav id="sidebar" class="sidebar js-sidebar">
                <div class="sidebar-content js-simplebar">
                    <a class="sidebar-brand" href="dashboard.php">
                        <span class="align-middle">Admin</span>
                    </a>

                    <ul class="sidebar-nav">
                        <li class="sidebar-header">
                            Pages
                        </li>
                        <li class="sidebar-item <?php if ($activeMenu == "dashboard") echo "active" ?>">
                            <a class="sidebar-link" href="dashboard.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sliders align-middle">
                                    <line x1="4" y1="21" x2="4" y2="14"></line>
                                    <line x1="4" y1="10" x2="4" y2="3"></line>
                                    <line x1="12" y1="21" x2="12" y2="12"></line>
                                    <line x1="12" y1="8" x2="12" y2="3"></line>
                                    <line x1="20" y1="21" x2="20" y2="16"></line>
                                    <line x1="20" y1="12" x2="20" y2="3"></line>
                                    <line x1="1" y1="14" x2="7" y2="14"></line>
                                    <line x1="9" y1="8" x2="15" y2="8"></line>
                                    <line x1="17" y1="16" x2="23" y2="16"></line>
                                </svg> <span class="align-middle">Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?php if ($activeMenu == "catalogue") echo "active" ?>">
                            <a class="sidebar-link">
                                <i class="fas fa-book-open" style="color: rgba(233, 236, 239, .5)"></i>
                                <span class="align-middle">Catalogue</span>
                            </a>
                            <div class="dropdown-content">
                                <a href="add-product.php">Add product</a>
                                <a href="edit-catalogue.php">Edit/Remove product</a>
                            </div>
                        </li>

                        <li class="sidebar-item <?php if ($activeMenu == "accounts") echo "active" ?>">
                            <a class="sidebar-link">
                                <i class="fa fa-user" aria-hidden="true"></i>

                                <span class="align-middle">Accounts</span>
                            </a>
                            <div class="dropdown-content">
                                <a href="./create-barber.php">Create barber</a>
                                <a href="./delete-account.php">View/Delete</a>
                            </div>
                        </li>

                        <li class="sidebar-item <?php if ($activeMenu == "attendance") echo "active" ?>">
                            <a class="sidebar-link" href="attendance.php">
                                <i class="fa fa-clock-o"></i>
                                <span class="align-middle">Attendance</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?php if ($activeMenu == "orders") echo "active" ?>">
                            <a class="sidebar-link" href="view-order.php">
                                <i class="fas fa-store"></i>
                                <span class="align-middle">Orders</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?php if ($activeMenu == "appointments") echo "active" ?>">
                            <a class="sidebar-link" href="view-appointment.php">
                                <i class="fas fa-calendar"></i>
                                <span class="align-middle">Appointments</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?php if ($activeMenu == "MarkPickup") echo "active" ?>">
                            <a class="sidebar-link" href="MarkPickup.php">
                                <i class="fas fa-check-square"></i>
                                <span class="align-middle">Mark Pickup</span>
                            </a>
                        </li>
                        
                        <li class="sidebar-item <?php if ($activeMenu == "services") echo "active" ?>">
                            <a class="sidebar-link">
                                <i class="fas fa-dollar-sign" style="color: rgba(233, 236, 239, .5)"></i>
                                <span class="align-middle">Services & pricing</span>
                            </a>
                            <div class="dropdown-content">
                                <a href="add-service.php">Add service</a>
                                <a href="services-pricing.php">Edit/Remove services</a>
                                <!-- <a href="#">Delete product</a> -->
                            </div>
                        </li>

                        <li class="sidebar-item <?php if ($activeMenu == "holiday") echo "active" ?>">
                            <a class="sidebar-link" href="holiday-approval.php">
                                <i class="fas fa-umbrella-beach"></i>
                                <span class="align-middle">Holiday approval</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="main">
                <nav class="navbar navbar-expand navbar-light navbar-bg">
                    <a class="sidebar-toggle js-sidebar-toggle">
                        <i class="hamburger align-self-center"></i>
                    </a>

                    <div class="navbar-collapse collapse">
                        <ul class="navbar-nav navbar-align">
                            <li class="nav-item" style="padding-top: 0.5rem;padding-right:1rem">Welcome <?php echo $_SESSION["admin_username"]; ?></li>
                            <!-- <li class="nav-item"><a class="nav-link btn text-dark" href="#">Sign out</a></li> -->
                            <li class="nav-item"><a class="nav-link btn" href="logout.php" style="color:#495057">Sign out</a></li>
                        </ul>
                    </div>

                </nav>