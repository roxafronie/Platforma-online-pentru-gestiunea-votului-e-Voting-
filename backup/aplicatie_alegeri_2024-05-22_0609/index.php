<?php

// Initialize the session
session_start();

?>

<!DOCTYPE html>
<head>
    <title>Alegeri on-line</title>

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
    <section>
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <h2>Această secţiune este dedicată exclusiv exercitării dreptului de vot în varianta on-line</h2>
                </div>
            </div>
        </div>
    </section>
</main>

<script type="text/javascript" src="assets/scripts/jquery/jquery-3.3.1.min.js?v=1.0.1"></script>
<script type="text/javascript" src="assets/scripts/bootstrap/bootstrap.min.js?v=1.0.1"></script>

</body>

</html>