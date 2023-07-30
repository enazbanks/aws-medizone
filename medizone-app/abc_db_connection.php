<?php

// Variables to hold access details of the database

$mysql_host = "medizone-aurora.cluster-ccyoyjeysntx.us-west-2.rds.amazonaws.com";
$mysql_user = "admin";
$mysql_pass = "admin123";

$mysql_db = "abc_database";

// Variable to run sql function with database host, user and password details

$conn=mysqli_connect($mysql_host,$mysql_user,$mysql_pass,$mysql_db);


// Function to check if connection successful

if(!$conn)
{
    die("Database connection failed.".mysqli_connect_error());
}
else
{
    echo "Connected to ".$mysql_db;
}

?>
