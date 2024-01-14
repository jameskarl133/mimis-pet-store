<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "mimis pet store2";

if(!$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname))
{
    die("Failed to connect!");
}
?>