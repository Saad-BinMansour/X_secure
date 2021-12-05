<?php
    $DB_host= "localhost";
    $DB_user= "root";
    $DB_pass= "";
    $DB_name= "xscure";

    $connection = mysqli_connect($DB_host, $DB_user, $DB_pass, $DB_name);

    if(!$connection)
    {
        die("Failed to connect database!!");
    }
?>