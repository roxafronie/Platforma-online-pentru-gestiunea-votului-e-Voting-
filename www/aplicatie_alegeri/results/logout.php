<?php

// Initialize the session
session_start();

unset($_SESSION["results_logged_in"]);
unset($_SESSION["results_id"]);
unset($_SESSION["results_user_name"]);

// Redirect to login page
header("location: index.php");

exit;