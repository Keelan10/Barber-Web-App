<?php
require_once("includes/database.php");
session_start();

if (isset($_POST["transactionID"])) {
    
    $transactionID = $_POST['transactionID'];
    $preparedStatement = $conn->prepare("SELECT DATE_FORMAT(paymentdate,'%M %d %Y') AS payment_date,  CONCAT(DATE_FORMAT(appointment.appointmentdate,'%M %d %Y'), ', ', DATE_FORMAT(appointment.start_time,'%H:%i')) as appointment_datetime, service.name, duration, appointmentdetails.price, is_cancelled
    FROM appointmentdetails, appointment, service, transactions,payment
    WHERE appointment.transactionid = ?
    AND appointmentdetails.transactionid = appointment.transactionid
    AND transactions.transactionid = appointmentdetails.transactionid
    AND payment.paymentid=transactions.paymentid
    AND appointmentdetails.serviceid = service.serviceid
    ");

    $preparedStatement->execute(array($transactionID));
    $result = $preparedStatement->fetchAll();

    header('Content-Type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);
}