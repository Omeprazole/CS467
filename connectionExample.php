<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
    include 'sqlInfo.php';

    //connect to database
    $mysqli = new mysqli($dbhost, $dbname, $dbpass, $dbuser);

    //check connection
    if(!$mysqli || $mysqli->connect_errno) {
        die("connection error" .$mysqli->connect_errno ."".$mysqli->connect_error);
    }
?>
