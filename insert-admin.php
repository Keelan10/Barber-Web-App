<?php

require_once("includes/database.php");
$SQL="UPDATE admin SET password='".password_hash("Qwerty123",PASSWORD_DEFAULT)."';";

// $conn->exec($SQL);
$conn->query($SQL);