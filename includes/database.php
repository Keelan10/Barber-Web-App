<?php
//params to connect to database
$dbHost="localhost";
$dbUser="root";
$dbPass="";
$dbName="barbershop";

//connection to database
// $mysqli = mysqli_connect($dbHost,$dbUser,$dbPass,$dbName);

$options = [
    PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
  ];

try{
    $conn = new PDO("mysql:host=$dbHost;dbname=$dbName",$dbUser,$dbPass,$options); 
}catch(PDOException $e){

    echo "<br>".$e->getMessage();
    die;
}

    

?>