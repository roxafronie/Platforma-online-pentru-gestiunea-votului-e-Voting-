<?php

// Initialize the session
session_start();

// Unset session variables
unset($_SESSION["logged_in"]);
unset($_SESSION["user_id"]);
unset($_SESSION["user_name"]);

// Redirect to login page
header("location: login.php");

exit;