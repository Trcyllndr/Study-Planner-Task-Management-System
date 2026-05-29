<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "study_planner_db";

$conn = mysqli_connect(
    $host,
    $username,
    $password,
    $database
);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

?>