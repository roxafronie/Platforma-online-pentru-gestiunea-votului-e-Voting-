<?php

// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect to login page
if (!(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true)) {
    header("location: login.php");
    exit;
}

include('libs/connection.php');
include('libs/utils.php');

if (!isset($connection)) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

?>

<!DOCTYPE html>
<head>
    <title>Alegeri on-line - Contul meu</title>

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
        <nav class="navbar navbar-expand-lg bg-faded justify-content-center">
            <a class="navbar-brand d-flex mr-auto" href="index.php">
                <img src="assets/images/logo.png" class="img-fluid" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#myAccountNavBar">
                <i class="fa fa-bars"></i>
            </button>
            <div id="myAccountNavBar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav ml-auto justify-content-end navbar-account">
                    <?php if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) { ?>
                        <li class="nav-item active"><a href="account.php" class="nav-link">Detalii cont</a></li>
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
    <section class="section-dashboard">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 d-flex">
                    <div class="card w-100">
                        <div class="card-body">
                            <?php
                            // get active elections
                            $sql    = "
                            SELECT
                                COUNT(election_id) AS total
                            FROM
                                tbl_elections
                            WHERE
                                NOW() between starting_date AND closing_date
                            ";
                            $result = mysqli_query($connection, $sql);
                            if (mysqli_num_rows($result) == 1) {
                                $row             = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                $activeElections = $row['total'];
                            }
                            ?>
                            <h5 class="card-title"><span class="badge badge-dark"><?= $activeElections ?></span> Sesiuni alegeri active</h5>
                            <p class="card-text">Sesiunile de alegeri au o durată scurtă de votare. Asiguraţi-vă că sunteţi informat!</p>
                        </div>
                        <div class="card-footer">
                            <a href="elections.php" class="btn btn-outline-dark">Start vot</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 d-flex">
                    <div class="card w-100">
                        <div class="card-body">
                            <?php
                            // get history elections
                            $sql    = "
                            SELECT
                                COUNT(e.election_id) AS total
                            FROM
                                tbl_elections e
                            LEFT JOIN 
                                tbl_elections_to_users etu
                                ON etu.election_id = e.election_id
                            WHERE
                                etu.user_id=" . $_SESSION["user_id"];
                            $result = mysqli_query($connection, $sql);
                            if (mysqli_num_rows($result) == 1) {
                                $row              = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                $historyElections = $row['total'];
                            }
                            ?>
                            <h5 class="card-title"><span class="badge badge-dark"><?= $historyElections ?></span> Istoric alegeri</h5>
                            <p class="card-text">Accesaţi buletinele de vot finalizate din sesiunile de alegeri active sau încheiate.</p>
                        </div>
                        <div class="card-footer">
                            <a href="elections-history.php" class="btn btn-outline-dark">Acces istoric</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script type="text/javascript" src="assets/scripts/jquery/jquery-3.3.1.min.js?v=1.0.1"></script>
<script type="text/javascript" src="assets/scripts/bootstrap/bootstrap.min.js?v=1.0.1"></script>

</body>

</html>