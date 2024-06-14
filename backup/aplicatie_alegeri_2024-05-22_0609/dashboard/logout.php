<?php

// Initialize the session
session_start();

unset($_SESSION["dashboard_logged_in"]);
unset($_SESSION["dashboard_id"]);
unset($_SESSION["dashboard_user_name"]);

// Redirect to login page
header("location: index.php");

exit;