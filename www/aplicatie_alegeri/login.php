<?php

// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect to account page
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
    header("location: account.php");
    exit;
}

// Include connection and utils file
include('libs/connection.php');
include('libs/utils.php');

if (!isset($connection)) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

?>
<!DOCTYPE html>
<head>
    <title>Alegeri on-line - Autentificare</title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta content="True" name="HandheldFriendly">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,700">

    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="assets/media/bootstrap/bootstrap.min.css?v=1.0.1">
    <link type="text/css" rel="stylesheet" href="assets/media/bundle.addons.css?v=1.0.1">

    <link rel="Shortcut Icon" href="assets/images/favicon.ico">
</head>

<body>

<header>
    <div class="container">
        <nav class="navbar navbar-expand-md bg-faded justify-content-center">
            <a class="navbar-brand d-flex mr-auto" href="index.php">
                <img src="assets/images/logo.png" class="img-fluid" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#myAccountNavBar">
                <i class="fa fa-bars"></i>
            </button>
            <div id="myAccountNavBar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav ml-auto justify-content-end navbar-account">
                    <?php if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) { ?>
                        <li class="nav-item"><a href="account.php" class="nav-link">Detalii cont</a></li>
                        <li class="nav-item"><a href="elections.php" class="nav-link">Sesiuni alegeri active</a></li>
                        <li class="nav-item"><a href="elections-history.php" class="nav-link">Istoric alegeri</a></li>
                        <li class="nav-item"><a href="logout.php" class="nav-link"><i class="fa fa-close text-danger"><!----></i> Deconectare</a></li>
                    <?php } else { ?>
                        <li class="nav-item"><a href="login.php" class="nav-link"><i class="fa fa-lock"><!----></i> Autentificare</a></li>
                    <?php } ?>
                </ul>
            </div>
        </nav>
    </div>
</header>

<main>
    <section class="section-account">
        <div class="container">
            <?php

            // Processing form data when the form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_name'], $_POST['user_password'])) {
                $username = mysqli_real_escape_string($connection, htmlspecialchars($_POST['user_name']));
                $password = mysqli_real_escape_string($connection, htmlspecialchars($_POST['user_password']));

                // Validate credentials
                if (empty($_POST["user_name"]) && empty($_POST["user_password"])) {
                    echo '<p class="text-danger">Introduceti Nume utilizator si Parola.</p>';
                } else {
                    $sql    = "
                    SELECT
                        *
                    FROM
                        tbl_users
                    WHERE
                        " . (isValidEmail($username) ? 'user_email' : 'user_name') . "='$username'
                        AND user_password = '" . md5($password) . "'
                    ";
                    $result = mysqli_query($connection, $sql);
                    $row    = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    if (mysqli_num_rows($result) == 1) {
                        // Authentication is correct, so start a new session
                        session_start();

                        // Storing session variables
                        $_SESSION["logged_in"] = true;
                        $_SESSION["user_id"]   = $row['user_id'];
                        $_SESSION["user_name"] = $username;

                        // Redirect user to account page
                        header("location: account.php");
                    } else {
                        echo '<p class="text-danger">Nume utilizator sau Parola sunt incorecte.</p>';
                    }
                }
            }
            ?>
            <div class="form-account mt-3 text-center">
                <form name="signin" method="post" action="login.php">
                    <div class="avatar"><i class="fa fa-user-o"><!----></i></div>

                    <h4>Autentificare</h4>

                    <div class="form-group">
                        <input type="text" id="user_name" name="user_name" placeholder="Nume utilizator" class="form-control input-lg" tabindex="1" value="" autofocus required>
                    </div>

                    <div class="form-group">
                        <input type="password" id="user_password" name="user_password" placeholder="Parola" class="form-control input-lg" tabindex="2" value="" required>
                    </div>

                    <button type="submit" class="btn btn-dark btn-block">Intră în cont</button>
                </form>

                <div class="small mt-5">
                    <p>Se vor folosi datele de logare pentru contul utilizatorului.</p>
                    <p>Nota: În cazul în care aţi uitat parola sau numele de utilizator vă rugăm să vă adresaţi admnistratorilor siteului.</p>
                </div>
            </div>
        </div>
    </section>
</main>

<script type="text/javascript" src="assets/scripts/jquery/jquery-3.3.1.min.js?v=1.0.1"></script>
<script type="text/javascript" src="assets/scripts/bootstrap/bootstrap.min.js?v=1.0.1"></script>

</body>

</html>