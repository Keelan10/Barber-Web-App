<?php
require_once("./includes/database.php");

if($_POST){

    // echo $_POST["day"];
    $date = $_POST["year"]."-".$_POST["month"]."-".$_POST["day"];
    // echo $date;
    // echo "DATE('".$date."')";

    $preparedStatement = $conn->prepare(
    "SELECT appointment.transactionid,appointment.start_time, DATE_ADD(start_time,INTERVAL SUM(duration) minute) AS end_time
    FROM appointment, appointmentdetails,service,transactions
    WHERE appointment.barberid=?
    AND transactions.transactionid=appointment.transactionid
    AND appointment.transactionid=appointmentdetails.transactionid
    AND appointmentdetails.serviceid=service.serviceid
    AND appointmentdate= ?
    AND transactions.is_cancelled=0
    GROUP BY transactionid
    ORDER BY start_time ASC"
    );
    
    $preparedStatement->execute( array($_POST["barberid"],$date) );

    echo json_encode($preparedStatement->fetchAll());

    // SELECT appointment.transactionid,appointment.appointmentdate,appointment.start_time, SUM(duration) as duration, DATE_ADD(start_time,INTERVAL SUM(duration) minute) AS end_time
    // FROM appointment, appointmentdetails,service,transactions
    // WHERE appointment.barberid=1
    // AND transactions.transactionid=appointment.transactionid
    // AND appointment.transactionid=appointmentdetails.transactionid
    // AND appointmentdetails.serviceid=service.serviceid
    // AND appointmentdate= DATE("2022-08-05")
    // AND transactions.is_cancelled=0
    // GROUP BY transactionid
    // ORDER BY start_time ASC
}