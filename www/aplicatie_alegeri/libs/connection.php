<?php

$databaseHost     = 'localhost';
$databaseName     = 'aplicatie_alegeri';
$databaseUser     = 'root';
$databasePassword = 'Summer2024';

$connection = mysqli_connect($databaseHost, $databaseUser, $databasePassword, $databaseName);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
