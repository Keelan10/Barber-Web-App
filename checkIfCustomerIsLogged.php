<?php

require_once "includes/database.php";
session_start();
echo false;

if (isset($_SESSION['customer_userid'])){
    echo "logged";
}
else{
    echo "not logged";
}

