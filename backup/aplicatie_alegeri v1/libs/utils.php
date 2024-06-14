<?php

// set explicit timezone
if (function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get")) {
    @date_default_timezone_set("Europe/Bucharest");
}

// validate email
function isValidEmail ($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// set current datetime format for MySQL
function setMysqlCurrentDateTime () {
    return date("Y-m-d H:i:s");
}
