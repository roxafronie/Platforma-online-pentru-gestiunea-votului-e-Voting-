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
    <title>Alegeri on-line - Alegeri</title>

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
                        <li class="nav-item active"><a href="elections.php" class="nav-link">Sesiuni alegeri active</a></li>
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
    <section class="section-elections">
        <div class="container">
            <?php
            $electionId = $_GET['id'];

            // check if active election with id is not opened
            if (!preg_match("/^[1-9][0-9]*$/", $electionId)) {
                // get active elections
                $sql    = "
                SELECT
                    election_id,
                    election_name,
                    election_description,
                    DATE_FORMAT(starting_date, '%d/%m/%Y %H:%i') AS starting_date,
                    DATE_FORMAT(starting_date, '%d/%m/%Y') AS starting_date_day,
                    TIME_FORMAT(TIME(starting_date), '%H:%i') AS starting_date_hours,
                    DATE_FORMAT(closing_date, '%d/%m/%Y %H:%i') AS closing_date,
                    DATE_FORMAT(closing_date, '%d/%m/%Y') AS closing_date_day,
                    TIME_FORMAT(TIME(closing_date), '%H:%i') AS closing_date_hours
                FROM
                    tbl_elections
                WHERE
                     NOW() between starting_date AND closing_date
                ORDER BY
                    sort_order DESC
                ";
                $result = mysqli_query($connection, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $i = 0;
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { ?>
                        <div class="card<?= ($i > 0 ? " mt-5" : "") ?>">
                            <div class="card-header">
                                <h2><a href="elections.php?id=<?= $row["election_id"] ?>"><?= $row["election_name"] ?></a></h2>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($row["election_description"])) { ?>
                                    <div class="mb-4"><?= $row["election_description"] ?></div>
                                <?php } ?>

                                <?php if ($row["starting_date_day"] != $row["closing_date_day"]) { ?>
                                    <p><i class="fa fa-calendar"></i> Dată deschidere: <?= $row["starting_date_day"] ?> <?= $row["starting_date_hours"] ?></p>
                                    <p><i class="fa fa-calendar"></i> Dată închidere: <?= $row["closing_date_day"] ?> <?= $row["closing_date_hours"] ?></p>
                                <?php } else { ?>
                                    <p><i class="fa fa-calendar"></i> Dată: <?= $row["starting_date_day"] ?></p>
                                    <p><i class="fa fa-clock-o"></i> Oră deschidere: <?= $row["starting_date_hours"] ?></p>
                                    <p><i class="fa fa-clock-o"></i> Oră închidere: <?= $row["closing_date_hours"] ?></p>
                                <?php } ?>
                            </div>
                            <div class="card-footer">
                                <a href="elections.php?id=<?= $row["election_id"] ?>" class="btn btn-dark"><small>Vezi buletin de vot</small></a>
                            </div>
                        </div>
                        <?php
                        $i++;
                    }
                } else {
                    echo '<p class="text-warning">Momentan nu sunt sesiuni de alegeri active!</p>';
                }
            } else {

                // get active elections
                $sql    = "
                SELECT
                    election_id,
                    election_type_id,
                    admin_user_id,
                    election_name,
                    election_description,
                    number_of_users,
                    status,
                    sort_order,
                    starting_date,
                    closing_date,
                    (
                    case
                        when NOW() > closing_date then 'CLOSED'
                        when NOW() > starting_date then 'OPENED'
                    end
                    )  AS closing_status,
                    date_format(date_added, '%d.%m.%Y') AS date_added,
                    date_format(date_last_modified, '%d.%m.%Y') AS date_last_modified
                FROM
                    tbl_elections
                WHERE
                     election_id=$electionId
                ";
                $result = mysqli_query($connection, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $i               = 0;
                    $currentElection = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    ?>
                    <div class="row">
                        <div class="col text-center">
                            <h2><?= $currentElection["election_name"] ?></h2>
                            <h4>BULETIN DE VOT ELECTRONIC</h4>
                        </div>
                    </div>

                    <?php

                    $sql    = "
                    SELECT
                        1
                    FROM
                        tbl_elections_to_users
                    WHERE
                        election_id=$electionId AND
                        user_id=" . $_SESSION["user_id"] . "
                    LIMIT 1
                    ";
                    $result = mysqli_query($connection, $sql);
                    if (mysqli_num_rows($result) == 1) { ?>
                        <div class="mt-4">
                            <p class="text-warning">Aţi votat deja la acest buletin de vot electronic.</p>

                            <p class="mt-4"><a href="elections-history.php?id=<?= $currentElection["election_id"] ?>" class="btn btn-dark"><small>vezi buletinul de vot</small></a></p>
                        </div>
                    <?php } else {
                        $ballotsItemsArray = array();

                        // get election questions
                        $sql    = "
                        SELECT
                            ballot_id,
                            ballot_type_id,
                            ballot_name,
                            ballot_description,
                            max_allowed_options
                        FROM
                            tbl_elections_ballots
                        WHERE
                            election_id=$electionId
                        ORDER BY
                            sort_order DESC
                        ";
                        $result = mysqli_query($connection, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $ballotsItemsArray[] = $row;
                            }
                        }

                        $voteRegistered = false;

                        // check if election form is submitted
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST)) {

                            // check if options are sent through post
                            if (isset($_POST["b"]) && is_array($_POST["b"])) {
                                // insert user entry for current election
                                $sql    = "
                                INSERT INTO
                                    tbl_elections_to_users
                                SET
                                    election_id=" . $electionId . ",
                                    user_id=" . $_SESSION["user_id"] . ",
                                    code='" . uniqid() . "',
                                    date_added='" . setMysqlCurrentDateTime() . "'
                                ";
                                $result = mysqli_query($connection, $sql);

                                // options voted
                                $ballotsIdsSubmitted = array_keys($_POST["b"]);

                                // prepare vote options to be inserted in db
                                if (count($ballotsIdsSubmitted) > 0) {
                                    foreach ($ballotsIdsSubmitted as $ballotId) {

                                        // insert user entry for current election question
                                        $sql    = "
                                        INSERT INTO
                                            tbl_elections_ballots_to_users
                                        SET
                                            election_id=" . $electionId . ",
                                            ballot_id=" . $ballotId . ",
                                            user_id=" . $_SESSION["user_id"] . ",
                                            status=1
                                        ";
                                        $result = mysqli_query($connection, $sql);

                                        // insert user entry for current election question variants/options (checkbox or radio)
                                        if (is_array($_POST["b"][$ballotId])) {
                                            // multiple options that can be voted
                                            foreach ($_POST["b"][$ballotId] as $opKey => $opValue) {
                                                $sql    = "
                                                INSERT INTO
                                                    tbl_elections_ballots_options_to_users
                                                SET
                                                    election_id=" . $electionId . ",
                                                    ballot_id=" . $ballotId . ",
                                                    ballot_option_id=" . $opValue . ",
                                                    user_id=" . $_SESSION["user_id"] . "
                                                ";
                                                $result = mysqli_query($connection, $sql);
                                            }
                                        } else {
                                            // single option that can be voted
                                            $sql    = "
                                            INSERT INTO
                                                tbl_elections_ballots_options_to_users
                                            SET
                                                election_id=" . $electionId . ",
                                                ballot_id=" . $ballotId . ",
                                                ballot_option_id=" . $_POST["b"][$ballotId] . ",
                                                user_id=" . $_SESSION["user_id"] . "
                                            ";
                                            $result = mysqli_query($connection, $sql);
                                        }
                                    }

                                    // election vote was successfully registered, records was inserted into db
                                    $voteRegistered = true;

                                    // get user email for email sending
                                    $sql        = "
                                    SELECT
                                        user_email
                                    FROM
                                        tbl_users
                                    WHERE
                                        user_id=" . $_SESSION["user_id"] . "
                                    ";
                                    $resultUser = mysqli_query($connection, $sql);
                                    if (mysqli_num_rows($resultUser) == 1) {
                                        $rowUser   = mysqli_fetch_array($resultUser, MYSQLI_ASSOC);
                                        $userEmail = $rowUser["user_email"];

                                        // check if logged user has a valid email
                                        if (isValidEmail($userEmail)) {
                                            $to      = 'nobody@example.com';
                                            $subject = 'Votul a fost inregistrat cu succces';
                                            $message = 'Vă mulţumim pentru votul dvs. electronic!';
                                            $headers = array(
                                                'From'     => 'webmaster@domain.ro',
                                                'Reply-To' => 'webmaster@domain.ro'
                                            );

                                            // send mail to user
                                            mail($to, $subject, $message, $headers);
                                        }
                                    }
                                    ?>
                                    <p class="text-success">Vă mulţumim pentru votul electronic.</p>
                                <?php }
                            } else { ?>
                                <p class="text-warning">Vă rugăm să specificaţi cel puţin o variantă de răspuns.</p>
                            <?php }
                        }

                        // display election questions (if not voted and successfully message is displayed)
                        if (!$voteRegistered) {
                            if (count($ballotsItemsArray) > 0) { ?>
                                <form name="ballotsForm" method="post" action="elections.php?id=<?= $currentElection["election_id"] ?>" novalidate>
                                    <?php
                                    $i = 1;
                                    foreach ($ballotsItemsArray as $key => $row) { ?>
                                        <div class="row<?= ($i > 1 ? " mt-5" : "") ?>">
                                            <div class="col-12">
                                                <h4><?= $i ?>. <?= $row["ballot_name"] ?></h4>
                                            </div>

                                            <?php
                                            // display election questions options
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
                                                                    <?= isset($_POST["b"][$row["ballot_id"]]) && $_POST["b"][$row["ballot_id"]] == $rowBallot["ballot_option_id"] ? 'checked="checked"' : '' ?>
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
                                                                    <?= isset($_POST["b"][$row["ballot_id"]][$rowBallot["ballot_option_id"]]) && $_POST["b"][$row["ballot_id"]][$rowBallot["ballot_option_id"]] == $rowBallot["ballot_option_id"] ? 'checked="checked"' : '' ?>
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
                                    } ?>

                                    <div class="mt-5">
                                        <button type="submit" class="btn btn-dark"><small>Votez</small></button>
                                    </div>
                                </form>
                            <?php } else {
                                echo '<p class="text-warning">Momentan nu există întrebări definite!</p>';
                            }
                        }
                    } ?>
                <?php }
            }
            ?>
        </div>
    </section>
</main>

<script type="text/javascript" src="assets/scripts/jquery/jquery-3.3.1.min.js?v=1.0.1"></script>
<script type="text/javascript" src="assets/scripts/bootstrap/bootstrap.min.js?v=1.0.1"></script>

</body>

</html>