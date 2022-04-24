<?php
require_once("./includes/database.php");


// To do: sanitize string and stuff
$name=strtolower($_GET["accountName"]);
$type=strtolower($_GET["accountType"]);
$email=strtolower($_GET["accountEmail"]);

if($type=="barber"){
    $SQL="DELETE FROM barber where email=".$conn->quote($email);
    $result =$conn->exec($SQL);
    
}else if($type=="customer"){
    $SQL="DELETE FROM customer where email=".$conn->quote($email);
    $result =$conn->exec($SQL);
}
// else if($type=="admin"){
//     $SQL="DELETE FROM admin where email=".$conn->quote($email);
//     $result =$conn->exec($SQL);
// }

// $result=1;

if(isset($result) && $result){
    echo "success";
}
else{
    echo "failure";
}