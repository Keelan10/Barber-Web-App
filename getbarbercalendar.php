<?php
// session_start();
require_once("includes/database.php");
$sql = "SELECT appointment.transactionid,CONCAT(customer.first_name,\" \",customer.last_name) as custname,customer.phone,appointmentdate,start_time,transactions.paymentid,barber.barberid
        FROM appointment,payment,transactions,barber,customer
        WHERE transactions.transactionid=appointment.transactionid
        AND transactions.paymentid=payment.paymentid
        AND barber.barberid=appointment.barberid
        AND customer.customerid = transactions.customerid
        AND transactions.is_cancelled = 0
        AND appointmentdate >= CURDATE();";

$result = $conn->query($sql);
$array = $result->fetchAll(PDO::FETCH_ASSOC);

for ($i = 0; $i < count($array); $i++) {
    $transationID = $array[$i]['transactionid'];

    $serviceSQL =
        "SELECT service.name
                            FROM appointmentdetails,service
                            WHERE service.serviceid=appointmentdetails.serviceid
                            AND  transactionid=" . $transationID . ";";

    $serviceResults = $conn->query($serviceSQL);
    $services = $serviceResults->fetchall();

    $string = "";
    for ($j = 0; $j < sizeof($services) - 1; $j++) {
        $string .= $services[$j]["name"] . ", ";
    }
    $string .= $services[sizeof($services) - 1]["name"];

    //DATA IN THE FORM SERVICE1,SERVICE2,SERVICE3

    $array[$i]['services'] = $string;
}
header('Content-Type: application/json');
echo json_encode($array, JSON_PRETTY_PRINT);