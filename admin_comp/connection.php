<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "mimi pet store";

if(!$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname))
{
    die("Failed to connect!");
}
?>