<?php
//craate sql connection
$servername = "localhost:3306";
$username = "root";
$password = "root";
$dbname = "ir_index";

// Create connection
$connection = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
