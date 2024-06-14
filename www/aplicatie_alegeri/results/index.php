<?php

// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect to account page
if (isset($_SESSION["results_logged_in"]) && $_SESSION["results_logged_in"] === true) {
    header("location: home.php");
    exit;
}

// Include connection and utils file
include('../libs/connection.php');
include('../libs/utils.php');

if (!isset($connection)) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

?>
<!DOCTYPE html>
<head>
    <title>Alegeri on-line - Rezultate - Autentificare</title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta content="True" name="HandheldFriendly">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <link type="text/css" rel="stylesheet" href="assets/fonts/mdi/materialdesignicons.css?v=1.0.1">
    <link type="text/css" rel="stylesheet" href="assets/media/vendor.bundle.base.min.css?v=1.0.1">
    <link type="text/css" rel="stylesheet" href="assets/media/vendor.bundle.addons.min.css?v=1.0.1">

    <link rel="Shortcut Icon" href="assets/images/favicon.ico">
</head>

<body>

<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one">
            <div class="row w-100">
                <div class="col-lg-4 mx-auto">
                    <div class="auto-form-wrapper">
                        <?php

                        // Processing form data when the form is submitted
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_name'], $_POST['user_password'])) {
                            $username = mysqli_real_escape_string($connection, htmlspecialchars($_POST['user_name']));
                            $password = mysqli_real_escape_string($connection, htmlspecialchars($_POST['user_password']));

                            // Validate credentials
                            if (empty($_POST["user_name"]) && empty($_POST["user_password"])) {
                                echo '<p class="text-danger">Introduceti Nume utilizator si Parola.</p>';
                            } else {
                                // verify last user credentials
                                $sql    = "
                                SELECT
                                    *
                                FROM
                                    tbl_users_admin
                                WHERE
                                    " . (isValidEmail($username) ? 'user_email' : 'user_name') . "='$username'
                                    AND user_password = '" . md5($password) . "'
                                LIMIT
			                        0,1
                                ";
                                $result = mysqli_query($connection, $sql);
                                $row    = mysqli_fetch_array($result, MYSQLI_ASSOC);

                                if (mysqli_num_rows($result) == 1) {
                                    // Authentication is correct, so start a new session
                                    session_start();

                                    // Storing session variables
                                    $_SESSION["results_logged_in"] = true;
                                    $_SESSION["results_user_id"]   = $row['user_id'];
                                    $_SESSION["results_user_name"] = $username;

                                    // Redirect user to account page
                                    header("location: home.php");
                                } else {
                                    echo '<p class="text-danger">Nume utilizator sau Parola sunt incorecte.</p>';
                                }
                            }
                        }
                        ?>

                        <form name="index" method="post" action="index.php">
                            <div class="form-group">
                                <label class="label">E-mail</label>
                                <div class="input-group">
                                    <input type="text" id="user_name" name="user_name" class="form-control input-lg" tabindex="1" autofocus>

                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                          <i class="mdi mdi-lock"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="label">Parola</label>
                                <div class="input-group">
                                    <input type="password" id="user_password" name="user_password" class="form-control input-lg" tabindex="2" placeholder="*********">

                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                          <i class="mdi mdi-lock"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary submit-btn btn-block">Trimite</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="assets/scripts/vendor.bundle.base.min.js?v=1.0.1"></script>

</body>

</html>