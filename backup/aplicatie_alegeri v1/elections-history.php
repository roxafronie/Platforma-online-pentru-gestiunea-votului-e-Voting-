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
    <title>Alegeri on-line - Istoric alegeri</title>

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
                        <li class="nav-item active"><a href="elections-history.php" class="nav-link">Istoric alegeri</a></li>
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
    <section class="section-elections">
        <div class="container">
            <?php
            $electionId = $_GET['id'];

            // check if election id is not opened
            if (!preg_match("/^[1-9][0-9]*$/", $electionId)) {
                // get history elections
                $sql    = "
                SELECT
                    e.election_id,
                    e.election_name,
                    DATE_FORMAT(e.starting_date, '%d/%m/%Y %H:%i') AS starting_date,
                    DATE_FORMAT(e.starting_date, '%d/%m/%Y') AS starting_date_day,
                    TIME_FORMAT(TIME(e.starting_date), '%H:%i') AS starting_date_hours,
                    DATE_FORMAT(e.closing_date, '%d/%m/%Y %H:%i') AS closing_date,
                    DATE_FORMAT(e.closing_date, '%d/%m/%Y') AS closing_date_day,
                    TIME_FORMAT(TIME(e.closing_date), '%H:%i') AS closing_date_hours,
                    DATE_FORMAT(etu.date_added, '%d/%m/%Y %H:%i') AS date_added
                FROM
                    tbl_elections e
                LEFT JOIN 
                    tbl_elections_to_users etu
                    ON etu.election_id = e.election_id
                WHERE
                    etu.user_id=" . $_SESSION["user_id"] . " 
                ORDER BY
                    etu.date_added DESC
                ";
                $result = mysqli_query($connection, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $i = 0;
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { ?>
                        <div class="card<?= ($i > 0 ? " mt-5" : "") ?>">
                            <div class="card-header">
                                <h2><a href="elections-history.php?id=<?= $row["election_id"] ?>"><?= $row["election_name"] ?></a></h2>
                            </div>
                            <div class="card-body">
                                <?php if ($row["starting_date_day"] != $row["closing_date_day"]) { ?>
                                    <p><i class="fa fa-calendar"></i> Dată deschidere: <?= $row["starting_date_day"] ?> <?= $row["starting_date_hours"] ?></p>
                                    <p><i class="fa fa-calendar"></i> Dată închidere: <?= $row["closing_date_day"] ?> <?= $row["closing_date_hours"] ?></p>
                                <?php } else { ?>
                                    <p><i class="fa fa-calendar"></i> Dată: <?= $row["starting_date_day"] ?></p>
                                    <p><i class="fa fa-clock-o"></i> Oră deschidere: <?= $row["starting_date_hours"] ?></p>
                                    <p><i class="fa fa-clock-o"></i> Oră închidere: <?= $row["closing_date_hours"] ?></p>
                                <?php } ?>
                                <p><i class="fa fa-calendar"></i> Dată vot: <?= $row["date_added"] ?></p>
                            </div>
                            <div class="card-footer">
                                <a href="elections-history.php?id=<?= $row["election_id"] ?>" class="btn btn-dark"><small>Vezi buletin de vot</small></a>
                            </div>
                        </div>
                        <?php
                        $i++;
                    }
                } else {
                    echo '<p class="text-warning">Momentan nu există un istoric al sesiunilor de alegeri!</p>';
                }
            } else {

                // build array with election questions options voted
                $userVotes   = array();
                $sql         = "
                SELECT
                    eb.ballot_type_id,
                    ebotu.ballot_id,
                    ebotu.ballot_option_id
                FROM
                    tbl_elections_ballots eb
                LEFT JOIN
                    tbl_elections_ballots_options_to_users ebotu
                    ON ebotu.ballot_id = eb.ballot_id
                WHERE
                    ebotu.election_id=$electionId
                    AND ebotu.user_id=" . $_SESSION["user_id"];
                $resultVotes = mysqli_query($connection, $sql);
                if (mysqli_num_rows($resultVotes) > 0) {
                    while ($rowVotes = mysqli_fetch_array($resultVotes, MYSQLI_ASSOC)) {
                        if ($rowVotes["ballot_type_id"] == 1) {
                            $userVotes[$rowVotes["ballot_id"]] = $rowVotes["ballot_option_id"];
                        } else if ($rowVotes["ballot_type_id"] == 2) {
                            if (isset($userVotes[$rowVotes["ballot_id"]]) && !is_array($userVotes[$rowVotes["ballot_id"]])) {
                                $userVotes[$rowVotes["ballot_id"]] = array($rowVotes["ballot_option_id"] => $rowVotes["ballot_option_id"]);
                            } else {
                                $userVotes[$rowVotes["ballot_id"]][$rowVotes["ballot_option_id"]] = $rowVotes["ballot_option_id"];
                            }
                        }
                    }
                }

                // get election questions
                $sql    = "
                SELECT
                    eb.ballot_id,
                    eb.ballot_type_id,
                    eb.ballot_name,
                    eb.ballot_description
                FROM
                    tbl_elections_ballots eb
                LEFT JOIN 
                    tbl_elections_to_users etu
                    ON etu.election_id = eb.election_id
                WHERE
                    etu.election_id=$electionId
                    AND etu.user_id=" . $_SESSION["user_id"] . " 
                ORDER BY
                    eb.sort_order DESC
                ";
                $result = mysqli_query($connection, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $i = 1;
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        ?>
                        <div class="row<?= ($i > 1 ? " mt-5" : "") ?>">
                            <div class="col-12">
                                <h4><?= $i ?>. <?= $row["ballot_name"] ?></h4>
                            </div>

                            <?php
                            // display election questions options voted (if there are votes)
                            $sql          = "
                            SELECT
                                ballot_option_id,
                                ballot_id,
                                ballot_option_name
                            FROM
                                tbl_elections_ballots_options
                            WHERE
                                ballot_id=" . $row["ballot_id"] . " 
                            ORDER BY
                                sort_order DESC
                            ";
                            $resultBallot = mysqli_query($connection, $sql);
                            if (mysqli_num_rows($resultBallot) > 0) {
                                while ($rowBallot = mysqli_fetch_array($resultBallot, MYSQLI_ASSOC)) {
                                    ?>
                                    <div class="col-12 col-options">
                                        <?php
                                        $oId = "b_" . $row["ballot_id"] . '_' . $rowBallot["ballot_option_id"];
                                        if ($row["ballot_type_id"] == 1) { ?>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input
                                                        type="radio"
                                                        id="ocr_<?= $oId ?>"
                                                        name="b[<?= $row["ballot_id"] ?>]"
                                                        class="custom-control-input"
                                                        value="<?= $rowBallot["ballot_option_id"] ?>"
                                                        disabled
                                                    <?= $userVotes[$row["ballot_id"]] == $rowBallot["ballot_option_id"] ? 'checked="checked"' : '' ?>
                                                >
                                                <label for="ocr_<?= $oId ?>" class="custom-control-label">
                                                    <?= $rowBallot["ballot_option_name"] ?>
                                                </label>
                                            </div>
                                        <?php } else { ?>
                                            <div class="custom-control custom-checkbox">
                                                <input
                                                        type="checkbox"
                                                        id="occ_<?= $oId ?>"
                                                        name="b[<?= $row["ballot_id"] ?>][<?= $rowBallot["ballot_option_id"] ?>]"
                                                        class="custom-control-input"
                                                        value="<?= $rowBallot["ballot_option_id"] ?>"
                                                        disabled
                                                    <?= isset($userVotes[$row["ballot_id"]][$rowBallot["ballot_option_id"]]) && $userVotes[$row["ballot_id"]][$rowBallot["ballot_option_id"]] == $rowBallot["ballot_option_id"] ? 'checked="checked"' : '' ?>
                                                >
                                                <label for="occ_<?= $oId ?>" class="custom-control-label">
                                                    <?= $rowBallot["ballot_option_name"] ?>
                                                </label>
                                            </div>
                                        <?php }
                                        ?>
                                    </div>
                                    <?php
                                }
                            }
                            ?>

                            <?php
                            // display ballot description if exists
                            if (!empty($row["ballot_description"])) { ?>
                                <div class="col-12 mt-3">
                                    <?= $row["ballot_description"] ?>
                                </div>
                            <?php } ?>
                        </div>
                        <?php
                        $i++;
                    }
                } ?>
                <p class="mt-5"><a href="elections-history.php" class="btn btn-dark"><small>Vezi tot istoricul</small></a></p>
            <?php }
            ?>
        </div>
    </section>
</main>

<script type="text/javascript" src="assets/scripts/jquery/jquery-3.3.1.min.js?v=1.0.1"></script>
<script type="text/javascript" src="assets/scripts/bootstrap/bootstrap.min.js?v=1.0.1"></script>

</body>

</html>