<?php
require 'constants.php';

// //connect to the DB
$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if(mysqli_errno($connection)) {
    die(mysqli_error($connection));
}

if ($connection -> connect_errno) {
    echo "Failed to connect: " . $connection -> connect_error;
    exit();
}