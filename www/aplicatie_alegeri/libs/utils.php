<?php

// set explicit timezone
if (function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get")) {
    @date_default_timezone_set("Europe/Bucharest");
}

// validate email
function isValidEmail ($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// valid date
function isValidDate ($date) {
    if (empty($date)) {
        return false;
    }

    try {
        $dateTimeObject = new DateTime($date);
    } catch (Exception $e) {
        return false;
    }

    return true;
}

// compare 2 dates (start date and end date)
function compare2dates ($dateStart, $dateEnd) {
    if (strtotime($dateStart) < strtotime($dateEnd)) {
        return true;
    }

    return false;
}

// set current datetime format for MySQL
function setMysqlCurrentDateTime () {
    return date("Y-m-d H:i:s");
}

// convert DateTimePicker format into MySQL format
function convertDateTimePickerToMysqlDateTime ($date) {
    return date("Y-m-d H:i", strtotime($date)) . ":00";
}

// convert MySQL date into DateTimePicker format
function convertMysqlDateTimeToDateTimePicker ($date) {
    return date("d-m-Y H:i", strtotime($date));
}