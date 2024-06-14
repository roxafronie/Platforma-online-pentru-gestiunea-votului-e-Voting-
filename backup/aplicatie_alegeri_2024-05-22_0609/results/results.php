<?php

// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect to login page
if (!(isset($_SESSION["results_logged_in"]) && $_SESSION["results_logged_in"] === true)) {
    header("location: login.php");
    exit;
}

include('../libs/connection.php');
include('../libs/utils.php');

if (!isset($connection)) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

?>
<!DOCTYPE html>
<head>
    <title>Alegeri on-line - Rezultate - Detalii sesiune de alegeri</title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta content="True" name="HandheldFriendly">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <link type="text/css" rel="stylesheet" href="assets/fonts/mdi/materialdesignicons.css?v=1.0.1">
    <link type="text/css" rel="stylesheet" href="assets/media/vendor.bundle.base.min.css?v=1.0.1">
    <link type="text/css" rel="stylesheet" href="assets/media/vendor.bundle.addons.min.css?v=1.0.1">

    <link rel="Shortcut Icon" href="assets/images/favicon.ico">
</head>

<body>

<script type="text/javascript" src="assets/scripts/vendor.bundle.base.min.js?v=1.0.1"></script>
<script type="text/javascript" src="assets/scripts/vendor.bundle.addons.min.js?v=1.0.1"></script>

<div class="container-scroller">
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
            <a class="navbar-brand brand-logo" href="home.php">
                <img src="assets/images/logo.png" alt="" class="img-fluid"></a>
            <a class="navbar-brand brand-logo-mini" href="home.php">
                <img src="assets/images/logo-mini.png" alt="logo"/></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown user-dropdown">
                    <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                        <span class="mdi mdi-face-recognition mdi-24px"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                        <div class="dropdown-header text-center">
                            <p class="mb-1 mt-3 font-weight-semibold">Comisie validare</p>
                            <p class="font-weight-light text-muted mb-0"></p>
                        </div>
                        <a class="dropdown-item" href="logout.php">Deconectare</a>
                    </div>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                <span class="mdi mdi-menu"></span>
            </button>
        </div>
    </nav>

    <div class="container-fluid page-body-wrapper">
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item nav-profile">
                    <div class="text-center">
                        <p class="profile-name"><strong>Comisie validare</strong></p>
                    </div>
                </li>

                <li class="nav-item nav-category">Meniu</li>
                <li class="nav-item"><a href="home.php" class="nav-link"><i class="menu-icon typcn typcn-bell"></i><span class="menu-title">Ecran principal</span></a></li>
                <li class="nav-item active"><a href="results.php" class="nav-link"><i class="menu-icon typcn typcn-bell"></i><span class="menu-title">Rezultate</span></a></li>
            </ul>
        </nav>

        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <?php

                    function listRecords ($connection) {
                        $records = array();

                        // get elections
                        $sql    = "
                        SELECT
                            DISTINCT e.election_id,
                            e.election_name,
                            e.status,
                            DATE_FORMAT(e.starting_date, '%d/%m/%Y %H:%i') AS starting_date,
                            DATE_FORMAT(e.closing_date, '%d/%m/%Y %H:%i') AS closing_date,
                            IF(NOW() > e.closing_date, 'CLOSED', 'OPENED') AS closing_status,
                            date_format(e.date_added, '%d.%m.%Y') AS date_added
                        FROM
                            tbl_elections e,
                            tbl_elections_validation_commissions evc,
                            tbl_users u,
                            tbl_users_admin ua
                        WHERE
                            NOW() > e.starting_date
                                AND e.election_id=evc.election_id
                                AND evc.user_id=u.user_id
                                AND " . (isset($_SESSION["results_user_name"]) && isValidEmail($_SESSION["results_user_name"]) ? "u.user_email=ua.user_email AND ua.user_email" : "u.user_name=ua.user_name AND ua.user_name") . "='" . $_SESSION["results_user_name"] . "'
                        ORDER BY
                            e.sort_order DESC,
                            election_id
                        ";
                        $result = mysqli_query($connection, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $records[] = $row;
                            }
                        }

                        if (count($records) > 0) { ?>
                            <div class="resultsContainer table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col">Nume</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Dată începere</th>
                                        <th scope="col">Dată închidere</th>
                                        <th scope="col">Op.</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    // display elections
                                    foreach ($records as $key => $record) { ?>
                                        <tr>
                                            <td><?= $record['election_name'] ?></td>
                                            <td>
                                                <?php if ($record['closing_status'] == 'OPENED') { ?>
                                                    <span class="opened">Activă</span>
                                                <?php } else { ?>
                                                    <span class="closed">Încheiată</span>
                                                <?php } ?>
                                            </td>
                                            <td><?= $record['starting_date'] ?></td>
                                            <td><?= $record['closing_date'] ?></td>
                                            <td>
                                                <?php if ($record['closing_status'] == 'CLOSED') { ?>
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-dark dropdown-toggle" type="button" data-boundary="window" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <span class="mdi mdi-chevron-down"></span>
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item" href="results.php?details=<?= $record['election_id'] ?>">Rezultat</a>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } else {
                            echo '<p class="mb-0 text-info">Momentan nu există sesiuni de alegeri!</p>';
                        } ?>
                    <?php }

                    function details ($connection, $id) {
                        // get current election
                        $sql    = "
                        SELECT
                            election_id,
                            election_name,
                            number_of_users,
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
                            election_id=" . $id . "
                            AND NOW() > closing_date
                        ";
                        $result = mysqli_query($connection, $sql);
                        if (mysqli_num_rows($result) == 1) {
                            $currentElection    = mysqli_fetch_array($result, MYSQLI_ASSOC);
                            $totalElectionUsers = 0;

                            // total number of users that voted on election with given ID
                            $sql    = "
                            SELECT
                                COUNT(user_id) AS total
                            FROM
                                tbl_elections_to_users
                            WHERE
                                election_id=" . $id;
                            $result = mysqli_query($connection, $sql);
                            if (mysqli_num_rows($result) == 1) {
                                $row                = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                $totalElectionUsers = $row['total'];
                            }

                            // total number of users who can vote in elections
                            $totalUsers = $currentElection["number_of_users"];

                            $totalPercentageUsers = 0;
                            if ($totalElectionUsers > 0 && $totalUsers) {
                                $totalPercentageUsers = round($totalElectionUsers / $totalUsers * 100, 2);
                            }
                            ?>
                            <h2><?= $currentElection["election_name"] ?></h2>
                            <div class="row mt-4 mb-2">
                                <div class="col-8 col-sm-5 col-md-4 col-lg-4">
                                    <p class="mb-0">Nr. utilizatori cu drept de vot</p>
                                </div>
                                <div class="col-4 col-sm-7 col-md-8 col-lg-8">
                                    <p><strong><?= $totalUsers ?></strong></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8 col-sm-5 col-md-4 col-lg-4">
                                    <p class="mb-0">Utilizatori care au votat</p>
                                </div>
                                <div class="col-4 col-sm-7 col-md-8 col-lg-8">
                                    <p><strong><?= $totalElectionUsers ?></strong></p>
                                </div>
                            </div>
                            <div class="row mb-4 border border-top-0 border-right-0 border-left-0 border-secondary">
                                <div class="col-8 col-sm-5 col-md-4 col-lg-4">
                                    <p class="mb-0">Rată de participare la vot</p>
                                </div>
                                <div class="col-4 col-sm-7 col-md-8 col-lg-8">
                                    <p><strong><?= $totalPercentageUsers ?>%</strong></p>
                                </div>
                            </div>
                            <?php

                            $ballotsItems = array();

                            // get election questions
                            $sql    = "
                                SELECT
                                    ballot_id,
                                    ballot_type_id,
                                    ballot_name,
                                    ballot_description
                                FROM
                                    tbl_elections_ballots
                                WHERE
                                    election_id=$id
                                ORDER BY
                                    sort_order DESC
                                ";
                            $result = mysqli_query($connection, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                    $ballotsItems[] = $row;
                                }
                            }

                            $i = 0;
                            foreach ($ballotsItems as $key => $data) {
                                $i++;
                                ?>
                                <div class="row<?= ($i > 1 ? " mt-5" : "") ?>">
                                    <div class="col-12">
                                        <h2><?= $i ?>. <?= $data["ballot_name"] ?></h2>
                                    </div>
                                    <?php
                                    $ballotsOptionsResults = array();

                                    // get election questions options votes number
                                    $sql    = "
                                    SELECT
                                        eb.ballot_id,
                                        ebo.ballot_option_id,
                                        ebo.ballot_option_name,
                                        (
                                            SELECT 
                                                COUNT(*) 
                                            FROM 
                                                tbl_elections_ballots_options_to_users 
                                            WHERE 
                                                ballot_option_id = ebo.ballot_option_id
                                        ) AS total_votes,
                                        (
                                            SELECT 
                                                COUNT(*) 
                                            FROM 
                                                tbl_elections_ballots_options_to_users ebotu
                                            LEFT JOIN tbl_elections_ballots_to_users ebtu
                                                ON ebtu.ballot_id = ebotu.ballot_id
                                                AND ebotu.user_id = ebtu.user_id
                                            WHERE
                                                ballot_option_id = ebo.ballot_option_id
                                                AND (ebtu.status = 1 OR ebtu.status IS NULL)
                                        ) AS total_valid_votes,
                                        (		
                                            SELECT 
                                                COUNT(*) 
                                            FROM 
                                                tbl_elections_ballots_options_to_users ebotu
                                            LEFT JOIN tbl_elections_ballots_to_users ebtu
                                                ON ebtu.ballot_id = ebotu.ballot_id
                                                AND ebotu.user_id = ebtu.user_id
                                            WHERE
                                                ebotu.ballot_id = ebo.ballot_id
                                                AND (ebtu.status = 1 OR ebtu.status IS NULL)
                                        ) AS total_valid_ballot_votes
                                        
                                    FROM
                                        tbl_elections_ballots_options ebo
                                    LEFT JOIN
                                        tbl_elections_ballots eb
                                        ON ebo.ballot_id = eb.ballot_id
                                    LEFT JOIN
                                        tbl_elections_ballots_types ebt
                                        ON eb.ballot_type_id = ebt.ballot_type_id
                                    WHERE
                                        ebo.ballot_id=" . $data["ballot_id"];
                                    $result = mysqli_query($connection, $sql);
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $ballotsOptionsResults[] = $row;
                                        }
                                    }

                                    if (count($ballotsOptionsResults) > 0) {
                                        $k                 = 0;
                                        $totalVotesOverall = 0;
                                        foreach ($ballotsOptionsResults as $oKey => $oData) {
                                            $k++;

                                            $totalVotes = $oData["total_valid_ballot_votes"];
                                            $percentage = 0;

                                            if ($oData["total_valid_votes"] > 0 && $totalVotes > 0) {
                                                if ($data["ballot_type_id"] == 1) {
                                                    // percentage calculated for a single answer option
                                                    $percentage = round($oData["total_valid_votes"] / $totalVotes * 100, 2);
                                                } else if ($data["ballot_type_id"] == 2) {
                                                    // percentage calculated for the multiple answer option
                                                    $percentage = round($oData["total_valid_votes"] * 100 / $totalElectionUsers, 2);
                                                }
                                            }
                                            ?>
                                            <div class="col-12 <?= ($k > 1 ? " mt-3" : "") ?>">
                                                <h3><?= $oData["ballot_option_name"] ?></h3>
                                                <p><small>Voturi exprimate: <span class="badge badge-dark"><?= $oData["total_votes"] ?></span></small></p>

                                                <?php
                                                ?>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%;" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"><?= $percentage ?>%</div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <div class="col-12 mt-2">
                                        <p style="font-size: 1.125rem"><small>Total utilizatori care şi-au exprimat votul</small>: <strong><?= $totalElectionUsers ?></strong></p>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        <?php } else {
                            echo '<p class="mb-0 text-info">Sesiunea de alegeri este în desfăşurare sau nu există!</p>';
                        }
                    }

                    ?>

                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="py-3 px-4 border-bottom bg-section">
                                <h4 class="mb-0">Rezultate</h4>
                            </div>

                            <div class="card-body card-results">
                                <div class="buttonsContainer">
                                    <a class="btn btn-lg btn-dark btn-fw" href="results.php">Vizualizare alegeri active </a>
                                </div>

                                <?php
                                if (isset($_GET['details'])) {
                                    $electionId = $_GET['details'];

                                    // check if election id is valid
                                    if (preg_match("/^[1-9][0-9]*$/", $electionId)) {
                                        details($connection, $electionId);
                                    } else {
                                        echo '<p class="text-warning">ID-ul nu este valid!</p>';
                                    }
                                } else {
                                    listRecords($connection);
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

</html>