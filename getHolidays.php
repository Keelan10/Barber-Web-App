<?php
    require_once("./includes/database.php");
    
    if(isset($_POST["barberid"])){

        // $preparedStatement = $conn->prepare(
        //     "   SELECT start_date, end_date FROM holiday 
        //         WHERE barberid=? 
        //         AND ((? BETWEEN MONTH(start_date)
        //             AND MONTH(end_date)
        //             AND YEAR(start_date)=? 
        //             AND YEAR(end_date)=?)
                    
        //             OR (YEAR(end_date)=? 
        //             AND YEAR(end_date)>YEAR(start_date) 
        //             AND MONTH(end_date)<=?))
                    
        //             OR (YEAR(end_date)>YEAR(start_date)
        //             AND YEAR(start_date)=?
        //             AND MONTH(start_date)<=?
        //             )
        //             ");

        // $preparedStatement->execute(array($_POST["barberid"],$_POST["month"],$_POST["year"],$_POST["year"],$_POST["year"],$_POST["month"],$_POST["year"],$_POST["month"]));

        $preparedStatement = $conn->prepare("SELECT start_date, end_date FROM holiday WHERE barberid=?");
        $preparedStatement->execute(array($_POST["barberid"]));
    
        echo json_encode($preparedStatement->fetchAll());
    }