<?php

// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect to login page
if (!(isset($_SESSION["dashboard_logged_in"]) && $_SESSION["dashboard_logged_in"] === true)) {
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
    <title>Alegeri on-line - Administrare - Homepage</title>

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
                            <p class="mb-1 mt-3 font-weight-semibold">Administrator</p>
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
                        <p class="profile-name"><strong>Administrator</strong></p>
                    </div>
                </li>

                <li class="nav-item nav-category">Meniu</li>
                <li class="nav-item active"><a href="home.php" class="nav-link"><i class="menu-icon typcn typcn-bell"></i><span class="menu-title">Ecran principal</span></a></li>
                <li class="nav-item"><a href="users.php" class="nav-link"><i class="menu-icon typcn typcn-bell"></i><span class="menu-title">Utilizatori</span></a></li>
                <li class="nav-item"><a href="elections.php" class="nav-link"><i class="menu-icon typcn typcn-bell"></i><span class="menu-title">Sesiuni alegeri</span></a></li>
                <li class="nav-item"><a href="ballots.php" class="nav-link"><i class="menu-icon typcn typcn-bell"></i><span class="menu-title">Buletine de vot</span></a></li>
            </ul>
        </nav>

        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <?php

                    // init active and closed election arrays
                    $activeElections = array();
                    $closedElections = array();

                    // get active elections
                    $sql    = "
                    SELECT
                        election_id,
                        election_name
                    FROM
                        tbl_elections
                    WHERE
                        NOW() between starting_date AND closing_date
                    ORDER BY
                        sort_order DESC
                    ";
                    $result = mysqli_query($connection, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            $activeElections[] = $row;
                        }
                    }

                    // get closed elections
                    $sql    = "
                    SELECT
                        election_id,
                        election_name,
                        closing_date AS db_closing_date
                    FROM
                        tbl_elections
                    WHERE
                        NOW() > closing_date
                        AND closing_date IN (SELECT max(closing_date) FROM tbl_elections)
                    ORDER BY
                        db_closing_date DESC,
                        sort_order DESC
                    ";
                    $result = mysqli_query($connection, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            $closedElections[] = $row;
                        }
                    }

                    $countActiveElections = count($activeElections);
                    $countClosedElections = count($closedElections);

                    $elections = array();
                    if ($countActiveElections > 0) {
                        $elections = $activeElections;
                    } else if ($countClosedElections > 0) {
                        $elections = $closedElections;
                    }


                    if ($countActiveElections == 0) {
                        ?>
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <p class="text-success">Nu există sesiuni de alegeri active!</p>
                                </div>
                            </div>
                        </div>
                        <?php
                        if ($countClosedElections > 0) {
                            ?>
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        Sesiuni de alegeri închise recent
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php }

                    $countElections = count($elections);
                    if ($countElections > 0) {
                        $i = 0;
                        foreach ($elections as $key => $data) {
                            $i++;

                            $totalElectionUsers = 0;
                            $totalUsers         = 0;

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
                                 election_id=" . $data["election_id"];
                            $result = mysqli_query($connection, $sql);
                            if (mysqli_num_rows($result) == 1) {
                                $currentElection = mysqli_fetch_array($result, MYSQLI_ASSOC);

                                // total number of users that voted on election with given ID
                                $sql    = "
                                SELECT
                                    COUNT(user_id) AS total
                                FROM
                                    tbl_elections_to_users
                                WHERE
                                    election_id=" . $data["election_id"];
                                $result = mysqli_query($connection, $sql);
                                if (mysqli_num_rows($result) == 1) {
                                    $row                = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                    $totalElectionUsers = $row['total'];
                                }

                                // total number of users who can vote in elections
                                $totalUsers = $currentElection["number_of_users"];

                                // the percentage of users who voted in the election
                                $totalPercentageUsers = 0;
                                if ($totalElectionUsers > 0 && $totalUsers) {
                                    $totalPercentageUsers = round($totalElectionUsers / $totalUsers * 100, 2);
                                }
                                ?>

                                <div class="col-lg-<?= ($countElections == 1 && $i == 1 ? "12" : "6") ?> grid-margin stretch-card">
                                    <div class="card">
                                        <div class="p-4 border-bottom bg-light">
                                            <h4 class="card-title mb-0"><?= $data["election_name"] ?></h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center pb-4">
                                                <div id="chartAreaLegend<?= $data["election_id"] ?>"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <h2 class="mb-0 font-weight-medium"><?= $totalElectionUsers ?></h2>
                                                    <p class="mb-5 text-muted">Utilizatori care au votat</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <h2 class="mb-0 font-weight-medium"><?= $totalUsers ?></h2>
                                                    <p class="mb-5 text-muted">Utilizatori cu drept de vot</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <h2 class="mb-0 font-weight-medium"><?= $totalPercentageUsers ?>%</h2>
                                                    <p class="mb-5 text-muted">Rată de participare la vot</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

</html>