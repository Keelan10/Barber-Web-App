<?php

require_once "includes/database.php";

if (isset($_POST["transactionid"])){

    $preparedStatement = $conn->prepare("update transactions set is_cancelled=1 where transactionid = ?");

    $preparedStatement->execute(array($_POST["transactionid"]));

    if ($preparedStatement->rowCount()==0){
        echo "failure";
    }else{
        echo "success";
    }
    
}