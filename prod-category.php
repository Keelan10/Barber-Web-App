<?php
    require_once("includes/database.php");

    $q=($_GET['q']);
    // strtolower()
    //TO DO sanitize $q sipakieter 


    $queryStatement= "SELECT * FROM product WHERE category =".$conn->quote($q).";";

    $results=$conn->query($queryStatement);
    
    //or use class and objects
    $a=array();

    while($row=$results->fetch()){
        $entry=array("name"=>$row["product_name"],"price"=>$row["price"],"image"=>$row["image_name"],"qty"=>$row["quantity"], "id"=>$row["productid"]);
        array_push($a,$entry);
    }
    
    header('Content-Type: application/json');
    echo JSON_encode($a);
    
    
?>