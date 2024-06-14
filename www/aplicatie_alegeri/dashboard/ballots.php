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
    <title>Alegeri on-line - Administrare - Buletine de vot</title>

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
                <li class="nav-item"><a href="home.php" class="nav-link"><i class="menu-icon typcn typcn-bell"></i><span class="menu-title">Ecran principal</span></a></li>
                <li class="nav-item"><a href="users.php" class="nav-link"><i class="menu-icon typcn typcn-bell"></i><span class="menu-title">Utilizatori</span></a></li>
                <li class="nav-item"><a href="elections.php" class="nav-link"><i class="menu-icon typcn typcn-bell"></i><span class="menu-title">Sesiuni alegeri</span></a></li>
                <li class="nav-item active"><a href="ballots.php" class="nav-link"><i class="menu-icon typcn typcn-bell"></i><span class="menu-title">Buletine de vot</span></a></li>
            </ul>
        </nav>

        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <?php

                    // select elections for adding ballots
                    function selectElections ($connection) {
                        // get elections
                        $sql    = "
                        SELECT
                            election_id,
                            election_name
                        FROM
                            tbl_elections
                        ORDER BY
                            sort_order DESC,
                            election_id ASC
                        ";
                        $result = mysqli_query($connection, $sql);
                        if (mysqli_num_rows($result) > 0) { ?>
                            <form name="ballots.php" method="post" action="" novalidate>
                                <div class="form-group mb-0">
                                    <script type="text/javascript">
                                        function onSelectOption() {
                                            var selectedOption = document.getElementById("election_id").value;
                                            window.location.href = 'ballots.php?electionId=' + selectedOption;
                                        }
                                    </script>

                                    <select
                                            id="election_id"
                                            name="election_id"
                                            class="form-control input-lg"
                                            onchange="onSelectOption();"
                                            tabindex="1"
                                            autofocus
                                            required
                                    >
                                        <option value="">- Selectaţi sesiunea de alegeri -</option>
                                        <?php while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { ?>
                                            <option value="<?= $row['election_id'] ?>"<?= $row['election_id'] == $_POST['election_id'] ? ' SELECTED' : '' ?>>
                                                <?= $row['election_name'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </form>
                        <?php } ?>
                    <?php }

                    // form used for both add and edit (ballots)
                    function recordBallotForm ($connection, $electionId, $ballotId = null) {
                        $formEdit      = preg_match("/^[1-9][0-9]*$/", $ballotId);
                        $formAction    = $formEdit ? "ballots.php?edit=$ballotId&electionId=$electionId" : "ballots.php?add&electionId=$electionId";
                        $formSubmitted = false;
                        $dataInserted  = false;

                        // if form is submitted (add or edit)
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $formSubmitted = true;

                            $ballotTypeId      = mysqli_real_escape_string($connection, htmlspecialchars($_POST['ballot_type_id']));
                            $ballotName        = mysqli_real_escape_string($connection, htmlspecialchars($_POST['ballot_name']));
                            $ballotDescription = mysqli_real_escape_string($connection, htmlspecialchars($_POST['ballot_description']));
                            $ballotStatus      = mysqli_real_escape_string($connection, htmlspecialchars($_POST['status']));
                            $ballotSortOrder   = mysqli_real_escape_string($connection, htmlspecialchars($_POST['sort_order']));

                            $errors = array();

                            // validate form fields
                            if (empty($ballotName)) {
                                $errors[] = "Specificaţi numele buletinului de vot.";
                            }

                            if (!preg_match("/^[0-9]+$/", $ballotSortOrder)) {
                                $errors[] = "Specificaţi ordinea de sortare cu numere mai mare de 0 inclusiv.";
                            }

                            // if errors encountered then display them
                            if (count($errors) > 0) {
                                foreach ($errors as $error) {
                                    echo '<p class="text-danger">' . $error . '</p>';
                                }
                            } else {
                                // ballots db table management
                                if (!$formEdit) {
                                    // add ballot
                                    $sql = "
                                    INSERT INTO
                                        tbl_elections_ballots
                                        (
                                            ballot_id,
                                            election_id,
                                            ballot_type_id,
                                            ballot_name,
                                            ballot_description,
                                            status,
                                            sort_order,
                                            date_added
                                        )
                                    VALUES (
                                        NULL,
                                        $electionId,
                                        $ballotTypeId,
                                        '$ballotName',
                                        '$ballotDescription',
                                        $ballotStatus,
                                        $ballotSortOrder,
                                        '" . setMysqlCurrentDateTime() . "'
                                        )
                                    ";
                                } else {
                                    // edit ballot
                                    $sql = "
                                    UPDATE
                                        tbl_elections_ballots
                                    SET
                                         ballot_type_id=$ballotTypeId,
                                         ballot_name='$ballotName',
                                         ballot_description='$ballotDescription',
                                         status=$ballotStatus,
                                         sort_order=$ballotSortOrder,
                                         date_last_modified='" . setMysqlCurrentDateTime() . "'
                                    WHERE
                                        ballot_id=$ballotId
                                    ";
                                }
                                $result = mysqli_query($connection, $sql);
                                if ($result) {
                                    // form fields successfully added into db table
                                    $dataInserted = true;

                                    if (!$formEdit) {
                                        echo '<p class="text-success">Buletinul de vot a fost adăugat cu succes.</p>';
                                    } else {
                                        echo '<p class="text-success">Buletinul de vot a fost modificat cu succes.</p>';
                                    }
                                } else {
                                    if (!$formEdit) {
                                        echo '<p class="text-danger">Eroare la adăugarea buletinului de vot.</p>';
                                    } else {
                                        echo '<p class="text-danger">Eroare la modificarea buletinului de vot.</p>';
                                    }
                                }
                            }

                        } else if ($formEdit) {
                            // get ballot details and fill form with these details only on edit
                            $sql    = "
                            SELECT
                                ballot_id,
                                election_id,
                                ballot_type_id,
                                ballot_name,
                                ballot_description,
                                status,
                                sort_order
                            FROM
                                tbl_elections_ballots
                            WHERE
                                ballot_id=$ballotId
                            ";
                            $result = mysqli_query($connection, $sql);
                            if (mysqli_num_rows($result) == 1) {
                                $row               = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                $ballotTypeId      = $row['ballot_type_id'];
                                $ballotName        = $row['ballot_name'];
                                $ballotDescription = $row['ballot_description'];
                                $ballotStatus      = $row['status'];
                                $ballotSortOrder   = $row['sort_order'];
                            }
                        } else {
                            // user go to add, fields are empty but some default values are prefilled
                            $ballotStatus    = 1;
                            $ballotSortOrder = 0;
                        }

                        if (!($formSubmitted && $dataInserted && !$formEdit)) {
                            ?>
                            <form name="ballots" method="post" action="<?= $formAction ?>" novalidate autocomplete="off">
                                <div class="form-group row">
                                    <label for="ballot_name" class="col-sm-4 col-form-label">Nume buletin de vot<span>*</span></label>
                                    <div class="col-sm-8">
                                        <input
                                                type="text"
                                                id="ballot_name"
                                                name="ballot_name"
                                                value="<?= $ballotName ?>"
                                                class="form-control input-lg"
                                                tabindex="1"
                                                autofocus
                                                required
                                        >
                                    </div>
                                </div>

                                <?php
                                // get ballot types
                                $sql    = "
                                SELECT
                                    ballot_type_id,
                                    ballot_type_name
                                FROM
                                    tbl_elections_ballots_types
                                ORDER BY
                                    sort_order DESC
                                ";
                                $result = mysqli_query($connection, $sql);
                                if (mysqli_num_rows($result) > 0) { ?>
                                    <div class="form-group row">
                                        <label for="ballot_type_id" class="col-sm-4 col-form-label">Tip vot<span>*</span></label>
                                        <div class="col-sm-8">
                                            <select
                                                    id="ballot_type_id"
                                                    name="ballot_type_id"
                                                    class="form-control input-lg"
                                                    tabindex="1"
                                                    required
                                            >
                                                <?php while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { ?>
                                                    <option value="<?= $row['ballot_type_id'] ?>"<?= $row['ballot_type_id'] == $ballotTypeId ? ' SELECTED' : '' ?>>
                                                        <?= $row['ballot_type_name'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="form-group row">
                                    <label for="ballot_description" class="col-sm-4 col-form-label">Descriere</label>
                                    <div class="col-sm-8">
                                        <textarea
                                                type="text"
                                                id="ballot_description"
                                                name="ballot_description"
                                                rows="5"
                                                cols="70"
                                                class="form-control input-lg"
                                                tabindex="2"
                                        ><?= $ballotDescription ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="status" class="col-sm-4 col-form-label">Status <span>*</span></label>
                                    <div class="col-sm-8">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-light<?= ($ballotStatus == 1 ? ' active' : '') ?>" for="status1">
                                                <input
                                                        type="radio"
                                                        id="status1"
                                                        name="status"
                                                        value="1"
                                                    <?= ($ballotStatus == 1 ? 'checked' : '') ?>
                                                >
                                                Activ
                                            </label>
                                            <label class="btn btn-light<?= ($ballotStatus == 0 ? ' active' : '') ?>" for="status0">
                                                <input
                                                        type="radio"
                                                        id="status0"
                                                        name="status"
                                                        value="0"
                                                    <?= ($ballotStatus == 0 ? 'checked' : '') ?>
                                                >
                                                Inactiv
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="sort_order" class="col-sm-4 col-form-label">Ordine sortare <span>*</span></label>
                                    <div class="col-sm-8">
                                        <input
                                                type="text"
                                                id="sort_order"
                                                name="sort_order"
                                                value="<?= $ballotSortOrder ?>"
                                                class="form-control input-lg"
                                                tabindex="6"
                                                required
                                        >
                                    </div>
                                </div>

                                <div class="form-group mt-5">
                                    <button type="submit" class="btn btn-dark btn-lg">Trimite</button>
                                </div>
                            </form>
                        <?php }
                    }

                    // form used for both add and edit (ballot options)
                    function recordBallotOptionForm ($connection, $electionId, $ballotId, $ballotOptionId = null) {
                        $formEdit      = preg_match("/^[1-9][0-9]*$/", $ballotOptionId);
                        $formAction    = $formEdit ? "ballots.php?&variants=$ballotId&editoption=$ballotOptionId&electionId=$electionId" : "ballots.php?add&variants=$ballotId&electionId=$electionId";
                        $formSubmitted = false;
                        $dataInserted  = false;

                        // if form is submitted (add or edit)
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $formSubmitted = true;

                            $ballotOptionName        = mysqli_real_escape_string($connection, htmlspecialchars($_POST['ballot_option_name']));
                            $ballotOptionDescription = mysqli_real_escape_string($connection, htmlspecialchars($_POST['ballot_option_description']));
                            $ballotOptionStatus      = mysqli_real_escape_string($connection, htmlspecialchars($_POST['status']));
                            $ballotOptionSortOrder   = mysqli_real_escape_string($connection, htmlspecialchars($_POST['sort_order']));

                            $errors = array();

                            // validate form fields
                            if (empty($ballotOptionName)) {
                                $errors[] = "Specificaţi numele opţiunii pe buletinul de vot.";
                            }

                            if (!preg_match("/^[0-9]+$/", $ballotOptionSortOrder)) {
                                $errors[] = "Specificaţi ordinea de sortare cu numere mai mare de 0 inclusiv.";
                            }

                            // if errors encountered then display them
                            if (count($errors) > 0) {
                                foreach ($errors as $error) {
                                    echo '<p class="text-danger">' . $error . '</p>';
                                }
                            } else {
                                // ballots db table management
                                if (!$formEdit) {
                                    // add ballot
                                    $sql = "
                                    INSERT INTO
                                        tbl_elections_ballots_options
                                        (
                                            ballot_option_id,
                                            ballot_id,
                                            ballot_option_name,
                                            ballot_option_description,
                                            status,
                                            sort_order,
                                            date_added
                                        )
                                    VALUES (
                                        NULL,
                                        $ballotId,
                                        '$ballotOptionName',
                                        '$ballotOptionDescription',
                                        $ballotOptionStatus,
                                        $ballotOptionSortOrder,
                                        '" . setMysqlCurrentDateTime() . "'
                                        )
                                    ";
                                } else {
                                    // edit ballot
                                    $sql = "
                                    UPDATE
                                        tbl_elections_ballots_options
                                    SET
                                         ballot_option_name='$ballotOptionName',
                                         ballot_option_description='$ballotOptionDescription',
                                         status=$ballotOptionStatus,
                                         sort_order=$ballotOptionSortOrder,
                                         date_last_modified='" . setMysqlCurrentDateTime() . "'
                                    WHERE
                                        ballot_option_id=$ballotOptionId
                                    ";
                                }
                                $result = mysqli_query($connection, $sql);
                                if ($result) {
                                    // form fields successfully added into db table
                                    $dataInserted = true;

                                    if (!$formEdit) {
                                        echo '<p class="text-success">Buletinul de vot a fost adăugat cu succes.</p>';
                                    } else {
                                        echo '<p class="text-success">Buletinul de vot a fost modificat cu succes.</p>';
                                    }
                                } else {
                                    if (!$formEdit) {
                                        echo '<p class="text-danger">Eroare la adăugarea buletinului de vot.</p>';
                                    } else {
                                        echo '<p class="text-danger">Eroare la modificarea buletinului de vot.</p>';
                                    }
                                }
                            }

                        } else if ($formEdit) {
                            // get ballot details and fill form with these details only on edit
                            $sql    = "
                            SELECT
                                ballot_option_id,
                                ballot_option_name,
                                ballot_option_description,
                                status,
                                sort_order
                            FROM
                                tbl_elections_ballots_options
                            WHERE
                                ballot_option_id=$ballotOptionId
                            ";
                            $result = mysqli_query($connection, $sql);
                            if (mysqli_num_rows($result) == 1) {
                                $row                     = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                $ballotOptionName        = $row['ballot_option_name'];
                                $ballotOptionDescription = $row['ballot_option_description'];
                                $ballotOptionStatus      = $row['status'];
                                $ballotOptionSortOrder   = $row['sort_order'];
                            }
                        } else {
                            // user go to add, fields are empty but some default values are prefilled
                            $ballotOptionStatus    = 1;
                            $ballotOptionSortOrder = 0;
                        }

                        if (!($formSubmitted && $dataInserted && !$formEdit)) {
                            ?>
                            <form name="ballots" method="post" action="<?= $formAction ?>" novalidate autocomplete="off">
                                <div class="form-group row">
                                    <label for="ballot_option_name" class="col-sm-4 col-form-label">Opţiune buletin vot <span>*</span></label>
                                    <div class="col-sm-8">
                                        <input
                                                type="text"
                                                id="ballot_option_name"
                                                name="ballot_option_name"
                                                value="<?= $ballotOptionName ?>"
                                                class="form-control input-lg"
                                                tabindex="1"
                                                autofocus
                                                required
                                        >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="ballot_description" class="col-sm-4 col-form-label">Descriere</label>
                                    <div class="col-sm-8">
                                        <textarea
                                                type="text"
                                                id="ballot_option_description"
                                                name="ballot_option_description"
                                                rows="5"
                                                cols="70"
                                                class="form-control input-lg"
                                                tabindex="2"
                                        ><?= $ballotOptionDescription ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="status" class="col-sm-4 col-form-label">Status <span>*</span></label>
                                    <div class="col-sm-8">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-light<?= ($ballotOptionStatus == 1 ? ' active' : '') ?>" for="status1">
                                                <input
                                                        type="radio"
                                                        id="status1"
                                                        name="status"
                                                        value="1"
                                                    <?= ($ballotOptionStatus == 1 ? 'checked' : '') ?>
                                                >
                                                Activ
                                            </label>
                                            <label class="btn btn-light<?= ($ballotOptionStatus == 0 ? ' active' : '') ?>" for="status0">
                                                <input
                                                        type="radio"
                                                        id="status0"
                                                        name="status"
                                                        value="0"
                                                    <?= ($ballotOptionStatus == 0 ? 'checked' : '') ?>
                                                >
                                                Inactiv
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="sort_order" class="col-sm-4 col-form-label">Ordine sortare <span>*</span></label>
                                    <div class="col-sm-8">
                                        <input
                                                type="text"
                                                id="sort_order"
                                                name="sort_order"
                                                value="<?= $ballotOptionSortOrder ?>"
                                                class="form-control input-lg"
                                                tabindex="6"
                                                required
                                        >
                                    </div>
                                </div>

                                <div class="form-group mt-5">
                                    <button type="submit" class="btn btn-dark btn-lg">Trimite</button>
                                </div>
                            </form>
                        <?php }
                    }

                    // get current election
                    function getElectionData ($connection, $electionId) {
                        // get current election
                        $sql    = "
                        SELECT
                            election_id,
                            election_name,
                            (
                            case
                                when NOW() > closing_date then 'CLOSED'
                                when NOW() > starting_date then 'OPENED'
                            end
                            )  AS closing_status
                        FROM
                            tbl_elections
                        WHERE
                            election_id=$electionId
                        ";
                        $result = mysqli_query($connection, $sql);
                        if (mysqli_num_rows($result) == 1) {
                            return mysqli_fetch_array($result, MYSQLI_ASSOC);
                        }

                        return array();
                    }

                    // get current election ballot
                    function getElectionBallotData ($connection, $ballotId) {
                        // get current election
                        $sql    = "
                        SELECT
                            ballot_name
                        FROM
                            tbl_elections_ballots
                        WHERE
                            ballot_id=$ballotId
                        ";
                        $result = mysqli_query($connection, $sql);
                        if (mysqli_num_rows($result) == 1) {
                            return mysqli_fetch_array($result, MYSQLI_ASSOC);
                        }

                        return array();
                    }

                    // default election ballots listing
                    function listBallotRecords ($connection, $electionId) {
                        $records = array();

                        // get closed elections
                        $sql    = "
                        SELECT
                            ballot_id,
                            ballot_name,
                            status,
                            sort_order,
                            date_format(date_added, '%d.%m.%Y') AS date_added
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
                                        <th scope="col">Ordine</th>
                                        <th scope="col">Op.</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    // display election ballots
                                    foreach ($records as $key => $record) {
                                        $currentElection = getElectionData($connection, $electionId);
                                        ?>
                                        <tr>
                                            <td><?= $record['ballot_name'] ?></td>
                                            <td>
                                                <?php
                                                if ($record['status'] == 1) { ?>
                                                    <span class="badge badge-success">ACTIV</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-danger">INACTIV</span>
                                                <?php } ?>
                                            </td>
                                            <td><?= $record['sort_order'] ?></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-outline-dark dropdown-toggle" type="button" data-boundary="window" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="mdi mdi-chevron-down"></span>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a class="dropdown-item" href="ballots.php?variants=<?= $record['ballot_id'] ?>&electionId=<?= $electionId ?>">Variante</a>
                                                        <?php if ($currentElection["closing_status"] != 'OPENED' && $currentElection["closing_status"] != 'CLOSED') { ?>
                                                            <a class="dropdown-item" href="ballots.php?edit=<?= $record['ballot_id'] ?>&electionId=<?= $electionId ?>">Modifică</a>
                                                            <a class="dropdown-item" href="ballots.php?delete=<?= $record['ballot_id'] ?>&electionId=<?= $electionId ?>">Şterge</a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } else {
                            echo '<p class="mb-0 text-info">Momentan nu există buletine de vot!</p>';
                        } ?>
                    <?php }

                    // default election ballots listing
                    function listBallotOptionsRecords ($connection, $electionId, $ballotId) {
                        $records = array();

                        // get closed elections
                        $sql    = "
                        SELECT
                            ballot_option_id,
                            ballot_option_name,
                            status,
                            sort_order
                        FROM
                            tbl_elections_ballots_options
                        WHERE
                            ballot_id=$ballotId
                        ORDER BY
                            sort_order DESC
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
                                        <th scope="col">Ordine</th>
                                        <th scope="col">Op.</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    // display election ballots
                                    foreach ($records as $key => $record) {
                                        $currentElection = getElectionData($connection, $electionId);
                                        ?>
                                        <tr>
                                            <td><?= $record['ballot_option_name'] ?></td>
                                            <td>
                                                <?php
                                                if ($record['status'] == 1) { ?>
                                                    <span class="badge badge-success">ACTIV</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-danger">INACTIV</span>
                                                <?php } ?>
                                            </td>
                                            <td><?= $record['sort_order'] ?></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-outline-dark dropdown-toggle" type="button" data-boundary="window" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="mdi mdi-chevron-down"></span>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <?php if ($currentElection["closing_status"] != 'OPENED' && $currentElection["closing_status"] != 'CLOSED') { ?>
                                                            <a class="dropdown-item" href="ballots.php?variants=<?= $ballotId ?>&editoption=<?= $record['ballot_option_id'] ?>&electionId=<?= $electionId ?>">Modifică</a>
                                                            <a class="dropdown-item" href="ballots.php?variants=<?= $ballotId ?>&deleteoption=<?= $record['ballot_option_id'] ?>&electionId=<?= $electionId ?>">Şterge</a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } else {
                            echo '<p class="mb-0 text-info">Momentan nu există opţiuni pe buletinul vot!</p>';
                        } ?>
                    <?php }

                    // delete ballot by ID
                    function deleteBallot ($connection, $id) {
                        $sql    = "
                        DELETE
                        FROM
                            tbl_elections_ballots
                        WHERE
                            ballot_id=$id
                        ";
                        $result = mysqli_query($connection, $sql);

                        $sql    = "
                        DELETE
                        FROM
                            tbl_elections_ballots_options
                        WHERE
                            ballot_id=$id
                        ";
                        $resultOptions = mysqli_query($connection, $sql);

                        if ($result && $resultOptions) {
                            echo '<p class="text-success">Buletinul de vot a fost şters cu succes.</p>';
                        } else {
                            echo '<p class="text-danger">Eroare la ştergerea buletinului de vot.</p>';
                        }
                    }

                    // delete ballot option by ID
                    function deleteBallotOption ($connection, $id) {
                        $sql    = "
                        DELETE
                        FROM
                            tbl_elections_ballots_options
                        WHERE
                            ballot_option_id=$id
                        ";
                        $result = mysqli_query($connection, $sql);
                        if ($result) {
                            echo '<p class="text-success">Opţiunea din buletinul de vot a fost şters cu succes.</p>';
                        } else {
                            echo '<p class="text-danger">Eroare la ştergerea opţiunii din buletinul de vot.</p>';
                        }
                    }

                    if (isset($_GET['electionId'])) {
                        $electionId = $_GET['electionId'];

                        $currentElection = getElectionData($connection, $electionId);

                        if (isset($_GET['variants'])) {
                            $currentElectionBallot = getElectionBallotData($connection, $_GET['variants']);
                        }
                    }

                    ?>

                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="py-3 px-4 border-bottom bg-section">
                                <h4 class="mb-0">Buletine de vot</h4>
                                <h5 class="mb-0"><?= $currentElection['election_name'] ?></h5>
                                <?php if (isset($_GET['variants'])) { ?>
                                    <h5 class="mb-0"><?= $currentElectionBallot['ballot_name'] ?></h5>
                                <?php } ?>
                            </div>

                            <div class="card-body card-results">
                                <?php

                                if (isset($currentElection["closing_status"])) {
                                    if ($currentElection["closing_status"] == 'OPENED') {
                                        echo '<p class="text-danger">Sesiunea de alegeri cu ID-ul (' . $electionId . ') a început şi nu se mai pot face modificări sau alte operaţii!</p>';
                                    } else if ($currentElection["closing_status"] == 'CLOSED') {
                                        echo '<p class="text-danger">Sesiunea de alegeri cu ID-ul (' . $electionId . ') s-a încheiat şi nu se mai pot face modificări sau alte operaţii!</p>';
                                    }
                                    return true;
                                }

                                if (isset($_GET['variants'])) {
                                    if ($currentElection["closing_status"] == 'OPENED' || $currentElection["closing_status"] == 'CLOSED') {
                                        echo '<p class="text-danger">Statusul sesiunii de alegeri nu mai permite operaţii pe această variantă!</p>';

                                        return true;
                                    }
                                }

                                if (isset($_GET['electionId'])) {
                                    ?>
                                    <div class="buttonsContainer">
                                        <?php if ($currentElection["closing_status"] != 'OPENED' && $currentElection["closing_status"] != 'CLOSED') { ?>
                                            <?php if (!isset($_GET['variants'])) { ?>
                                                <a class="btn btn-lg btn-dark btn-fw" href="ballots.php?add&electionId=<?= $electionId ?>">Adăugare</a>
                                            <?php } else { ?>
                                                <a class="btn btn-lg btn-dark btn-fw" href="ballots.php?add&variants=<?= $_GET['variants'] ?>&electionId=<?= $electionId ?>">Adăugare</a>
                                            <?php } ?>
                                        <?php } ?>
                                        <a class="btn btn-lg btn-dark btn-fw" href="ballots.php?electionId=<?= $electionId ?>">Vizualizare buletine de vot</a>
                                        <?php if (isset($_GET['variants'])) { ?>
                                            <a class="btn btn-lg btn-dark btn-fw" href="ballots.php?variants=<?= $_GET['variants'] ?>&electionId=<?= $electionId ?>">Vizualizare opţiuni vot</a>
                                        <?php } ?>
                                    </div>
                                <?php } ?>

                                <?php

                                if (isset($_GET['electionId'])) {
                                    // check if election id is valid
                                    if (preg_match("/^[1-9][0-9]*$/", $electionId)) {
                                        // add
                                        if (isset($_GET['add'])) {
                                            if (!isset($_GET['variants'])) {
                                                // add ballot
                                                recordBallotForm($connection, $electionId);
                                            } else {
                                                // add ballot option
                                                $ballotId = $_GET['variants'];

                                                // check if ballot id is valid
                                                if (preg_match("/^[1-9][0-9]*$/", $ballotId)) {
                                                    recordBallotOptionForm($connection, $electionId, $ballotId);
                                                } else {
                                                    echo '<p class="text-warning">ID-ul nu este valid sau categoria nu este disponibilă!</p>';
                                                }
                                            }
                                        } else if (isset($_GET['variants'])) {
                                            $ballotId = $_GET['variants'];

                                            // check if ballot id is valid
                                            if (preg_match("/^[1-9][0-9]*$/", $ballotId)) {
                                                // edit ballot option
                                                if (isset($_GET['editoption'])) {
                                                    $ballotOptionId = $_GET['editoption'];

                                                    if (preg_match("/^[1-9][0-9]*$/", $ballotOptionId)) {
                                                        recordBallotOptionForm($connection, $electionId, $ballotId, $ballotOptionId);
                                                    } else {
                                                        echo '<p class="text-warning">ID-ul nu este valid sau categoria nu este disponibilă!</p>';
                                                    }
                                                } else if (isset($_GET['deleteoption'])) {
                                                    // delete ballot option
                                                    $ballotOptionId = $_GET['deleteoption'];

                                                    if (preg_match("/^[1-9][0-9]*$/", $ballotOptionId)) {
                                                        deleteBallotOption($connection, $ballotOptionId);
                                                    } else {
                                                        echo '<p class="text-warning">ID-ul nu este valid sau categoria nu este disponibilă!</p>';
                                                    }
                                                } else {
                                                    listBallotOptionsRecords($connection, $electionId, $ballotId);
                                                }
                                            } else {
                                                echo '<p class="text-warning">ID-ul nu este valid sau categoria nu este disponibilă!</p>';
                                            }
                                        } else if (isset($_GET['edit'])) {
                                            // edit ballot
                                            $ballotId = $_GET['edit'];

                                            // check if ballot id is valid
                                            if (preg_match("/^[1-9][0-9]*$/", $ballotId)) {
                                                recordBallotForm($connection, $electionId, $ballotId);
                                            } else {
                                                echo '<p class="text-warning">ID-ul nu este valid sau categoria nu este disponibilă!</p>';
                                            }
                                        } else if (isset($_GET['delete'])) {
                                            // delete ballot
                                            $ballotId = $_GET['delete'];

                                            // check if ballot id is valid
                                            if (preg_match("/^[1-9][0-9]*$/", $ballotId)) {
                                                deleteBallot($connection, $ballotId);
                                            } else {
                                                echo '<p class="text-warning">ID-ul nu este valid sau categoria nu este disponibilă!</p>';
                                            }
                                        } else {
                                            listBallotRecords($connection, $electionId);
                                        }
                                    } else {
                                        echo '<p class="text-warning">ID-ul nu este valid sau categoria nu este disponibilă!</p>';
                                    }
                                } else {
                                    selectElections($connection);
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