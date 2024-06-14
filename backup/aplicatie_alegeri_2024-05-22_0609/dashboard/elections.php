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
    <title>Alegeri on-line - Administrare - Sesiuni alegeri</title>

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
                <li class="nav-item active"><a href="elections.php" class="nav-link"><i class="menu-icon typcn typcn-bell"></i><span class="menu-title">Sesiuni alegeri</span></a></li>
                <li class="nav-item"><a href="ballots.php" class="nav-link"><i class="menu-icon typcn typcn-bell"></i><span class="menu-title">Buletine de vot</span></a></li>
            </ul>
        </nav>

        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <?php

                    // default elections listing
                    function listRecords ($connection) {
                        $records = array();

                        // get closed elections
                        $sql    = "
                        SELECT
                            election_id,
                            election_name,
                            status,
                            sort_order,
                            starting_date,
                            closing_date,
                            IF(NOW() > starting_date, 'OPENED', '') AS opening_status,
                            IF(NOW() > closing_date, 'CLOSED', '') AS closing_status,
                            DATE_FORMAT(starting_date, '%d/%m/%Y %H:%i') AS starting_date_formatted,
                            DATE_FORMAT(closing_date, '%d/%m/%Y %H:%i') AS closing_date_formatted
                        FROM
                            tbl_elections
                        ORDER BY
                            sort_order
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
                                        <th scope="col">Ordine</th>
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
                                                <?php if ($record['closing_status'] == 'CLOSED') { ?>
                                                    <span class="closed">Încheiată</span>
                                                <?php } else if ($record['opening_status'] == 'OPENED') { ?>
                                                    <span class="opened">Activă</span>
                                                <?php } else { ?>
                                                    <span>În viitor</span>
                                                <?php } ?>
                                            </td>
                                            <td><?= $record['starting_date_formatted'] ?></td>
                                            <td><?= $record['closing_date_formatted'] ?></td>
                                            <td><?= $record['sort_order'] ?></td>
                                            <td>
                                                <?php if (empty($record['opening_status']) && empty($record['closing_status'])) { ?>
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-dark dropdown-toggle" type="button" data-boundary="window" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <span class="mdi mdi-chevron-down"></span>
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item" href="elections.php?edit=<?= $record['election_id'] ?>">Modifică</a>
                                                            <a class="dropdown-item" href="elections.php?members=<?= $record['election_id'] ?>">Comisie validare</a>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php }
                                    ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="6"><em>Alegerile încheiate sau active nu pot modificate / şterse.</em></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        <?php } else {
                            echo '<p class="mb-0 text-info">Momentan nu există sesiuni de alegeri!</p>';
                        } ?>
                    <?php }

                    // form used for both add and edit
                    function recordForm ($connection, $electionId = null) {
                        $formEdit      = preg_match("/^[1-9][0-9]*$/", $electionId);
                        $formAction    = $formEdit ? "elections.php?edit=$electionId" : 'elections.php?add';
                        $formSubmitted = false;
                        $dataInserted  = false;

                        // if form is submitted (add or edit)
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $formSubmitted = true;

                            $electionName          = mysqli_real_escape_string($connection, htmlspecialchars($_POST['election_name']));
                            $electionDescription   = mysqli_real_escape_string($connection, htmlspecialchars($_POST['election_description']));
                            $electionStartingDate  = mysqli_real_escape_string($connection, htmlspecialchars($_POST['starting_date']));
                            $electionClosingDate   = mysqli_real_escape_string($connection, htmlspecialchars($_POST['closing_date']));
                            $electionNumberOfUsers = mysqli_real_escape_string($connection, htmlspecialchars($_POST['number_of_users']));
                            $electionSortOrder     = mysqli_real_escape_string($connection, htmlspecialchars($_POST['sort_order']));

                            $errors = array();

                            // validate form fields
                            if (empty($electionName)) {
                                $errors[] = "Specificaţi numele sesiunii de alegeri.";
                            }

                            if (!isValidDate($electionStartingDate)) {
                                $errors[] = "Specificaţi data de începere a sesiunii de alegeri.";
                            }

                            if (!isValidDate($electionClosingDate)) {
                                $errors[] = "Specificaţi data de închidere a sesiunii de alegeri.";
                            } else if (!compare2dates($electionStartingDate, $electionClosingDate)) {
                                $errors[] = "Data de închidere trebuie să fie mai mare sau egala decât data de începere.";
                            }

                            if (!preg_match("/^[1-9][0-9]*$/", $electionNumberOfUsers)) {
                                $errors[] = "Specificaţi numărul de utilizatori care participă la sesiunea de alegeri.";
                            }

                            if (!preg_match("/^[0-9]+$/", $electionSortOrder)) {
                                $errors[] = "Specificaţi ordinea de sortare cu numere mai mare de 0 inclusiv.";
                            }

                            // if errors encountered then display them
                            if (count($errors) > 0) {
                                foreach ($errors as $error) {
                                    echo '<p class="text-danger">' . $error . '</p>';
                                }
                            } else {
                                // elections db table management
                                if (!$formEdit) {
                                    // add election
                                    $sql = "
                                    INSERT INTO
                                        tbl_elections
                                        (
                                            election_id,
                                            election_name,
                                            election_description,
                                            starting_date,
                                            closing_date,
                                            number_of_users,
                                            sort_order,
                                            date_added
                                        )
                                    VALUES (
                                        NULL,
                                        '$electionName',
                                        '$electionDescription',
                                        '" . convertDateTimePickerToMysqlDateTime($electionStartingDate) . "',
                                        '" . convertDateTimePickerToMysqlDateTime($electionClosingDate) . "',
                                        $electionNumberOfUsers,
                                        $electionSortOrder,
                                        '" . setMysqlCurrentDateTime() . "'
                                        )
                                    ";
                                } else {
                                    // edit election
                                    $sql = "
                                    UPDATE
                                        tbl_elections
                                    SET
                                        election_name='$electionName',
                                        election_description='$electionDescription',
                                        starting_date='" . convertDateTimePickerToMysqlDateTime($electionStartingDate) . "',
                                        closing_date='" . convertDateTimePickerToMysqlDateTime($electionClosingDate) . "',
                                        number_of_users=$electionNumberOfUsers,
                                        sort_order=$electionSortOrder,
                                        date_last_modified='" . setMysqlCurrentDateTime() . "'
                                    WHERE
                                        election_id=$electionId
                                    ";
                                }
                                $result = mysqli_query($connection, $sql);
                                if ($result) {
                                    // form fields successfully added into db table
                                    $dataInserted = true;

                                    if (!$formEdit) {
                                        echo '<p class="text-success">Sesiunea de alegeri a fost adăugată cu succes.</p>';
                                    } else {
                                        echo '<p class="text-success">Sesiunea de alegeri a fost modificată cu succes.</p>';
                                    }
                                } else {
                                    if (!$formEdit) {
                                        echo '<p class="text-danger">Eroare la adăugarea sesiunii de alegeri.</p>';
                                    } else {
                                        echo '<p class="text-danger">Eroare la modificarea sesiunii de alegeri.</p>';
                                    }
                                }
                            }
                        } else if ($formEdit) {
                            // get election details and fill form with these details only on edit
                            $sql    = "
                            SELECT
                                election_id,
                                election_name,
                                election_description,
                                starting_date,
                                closing_date,
                                number_of_users,
                                sort_order
                            FROM
                                tbl_elections
                            WHERE
                                election_id=$electionId
                            ";
                            $result = mysqli_query($connection, $sql);
                            if (mysqli_num_rows($result) == 1) {
                                $row                   = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                $electionName          = $row['election_name'];
                                $electionDescription   = $row['election_description'];
                                $electionStartingDate  = convertMysqlDateTimeToDateTimePicker($row['starting_date']);
                                $electionClosingDate   = convertMysqlDateTimeToDateTimePicker($row['closing_date']);
                                $electionNumberOfUsers = $row['number_of_users'];
                                $electionSortOrder     = $row['sort_order'];
                            }
                        } else {
                            // user go to add, fields are empty but some default values are prefilled
                            $electionNumberOfUsers = 0;
                            $electionSortOrder     = 0;
                        }

                        if (!($formSubmitted && $dataInserted && !$formEdit)) {
                            ?>
                            <form name="elections" method="post" action="<?= $formAction ?>" novalidate autocomplete="off">
                                <div class="form-group row">
                                    <label for="election_name" class="col-sm-4 col-form-label">Nume <span>*</span></label>
                                    <div class="col-sm-8">
                                        <input
                                                type="text"
                                                id="election_name"
                                                name="election_name"
                                                value="<?= $electionName ?>"
                                                class="form-control input-lg"
                                                tabindex="1"
                                                autofocus
                                                required
                                        >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="election_description" class="col-sm-4 col-form-label">Descriere</label>
                                    <div class="col-sm-8">
                                        <textarea
                                                type="text"
                                                id="election_description"
                                                name="election_description"
                                                rows="5"
                                                cols="70"
                                                class="form-control input-lg"
                                                tabindex="2"
                                        ><?= $electionDescription ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="starting_date" class="col-sm-4 col-form-label">Dată începere <span>*</span></label>
                                    <div class="col-sm-8">
                                        <input
                                                type="text"
                                                id="starting_date"
                                                name="starting_date"
                                                value="<?= $electionStartingDate ?>"
                                                class="form-control input-lg datetimepicker"
                                                tabindex="3"
                                                required
                                        >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="closing_date" class="col-sm-4 col-form-label">Dată închidere <span>*</span></label>
                                    <div class="col-sm-8">
                                        <input
                                                type="text"
                                                id="closing_date"
                                                name="closing_date"
                                                value="<?= $electionClosingDate ?>"
                                                class="form-control input-lg datetimepicker"
                                                tabindex="4"
                                                required
                                        >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="number_of_users" class="col-sm-4 col-form-label">Total utilizatori cu drept de vot <span>*</span></label>
                                    <div class="col-sm-8">
                                        <input
                                                type="text"
                                                id="number_of_users"
                                                name="number_of_users"
                                                value="<?= $electionNumberOfUsers ?>"
                                                class="form-control input-lg"
                                                tabindex="5"
                                                required
                                        >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="sort_order" class="col-sm-4 col-form-label">Ordine sortare <span>*</span></label>
                                    <div class="col-sm-8">
                                        <input
                                                type="text"
                                                id="sort_order"
                                                name="sort_order"
                                                value="<?= $electionSortOrder ?>"
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

                    // add member to election ID
                    function members ($connection, $id) {
                        $users = array();

                        // get users
                        $sql    = "
                        SELECT
                            user_id,
                            user_first_name,
                            user_last_name,
                            user_name,
                            user_password,
                            user_email,
                            CONCAT(user_first_name, ' ', user_last_name) AS user_full_name
                        FROM
                            tbl_users
                        ORDER BY
                            user_first_name
                        ";
                        $result = mysqli_query($connection, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                //$users[] = $row;
                                $users[$row['user_id']] = array(
                                    "user_name"      => $row['user_name'],
                                    "user_password"  => $row['user_password'],
                                    "user_email"     => $row['user_email'],
                                    "user_full_name" => $row['user_full_name']
                                );
                            }
                        }

                        $userNamesValidators = array();
                        // get validation commission users
                        $sql    = "
                        SELECT
                            evc.user_id,
                            u.user_name,
                            u.user_password,
                            u.user_email,
                            CONCAT(u.user_first_name, ' ', u.user_last_name) AS user_full_name
                        FROM
                            tbl_elections_validation_commissions evc,
                            tbl_users u
                        WHERE
                            evc.user_id=u.user_id AND
                            evc.election_id=$id
                        ORDER BY
                            evc.date_added DESC
                        ";
                        $result = mysqli_query($connection, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $userNamesValidators[$row['user_id']] = $row['user_name'];
                            }
                        }

                        // if form is submitted (add or edit)
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            // delete users from validation commissions
                            $sql    = "
                            DELETE
                            FROM
                                tbl_elections_validation_commissions
                            WHERE
                                election_id=$id
                            ";
                            $result = mysqli_query($connection, $sql);

                            // delete validation commissions users from admin login users
                            foreach ($userNamesValidators as $userId => $userName) {
                                $sql    = "
                                DELETE
                                FROM
                                    tbl_users_admin
                                WHERE
                                    user_name='" . $userName . "'
                                ORDER BY
                                    user_id DESC
                                LIMIT
                                    1
                                ";
                                $result = mysqli_query($connection, $sql);
                            }

                            // if errors encountered then display them
                            if (count($_POST['user_ids']) == 0) {
                                echo '<p class="text-danger">Specificaţi cel puţin un membru pentru comisia de validare.</p>';
                            } else {
                                echo '<p class="text-success">Adăugarea membrilor din comisia de validare a fost efectută cu succes.</p>';

                                // add user(s) to validation commission
                                foreach ($_POST['user_ids'] as $userId) {
                                    // insert user into validation commissions
                                    $sql    = "
                                    INSERT INTO
                                        tbl_elections_validation_commissions
                                    SET
                                        election_id=$id,
                                        user_id=$userId,
                                        date_added='" . setMysqlCurrentDateTime() . "'
                                    ";
                                    $result = mysqli_query($connection, $sql);

                                    // insert validation commission user into admin login users
                                    $sql    = "
                                    INSERT INTO
                                        tbl_users_admin
                                    SET
                                        user_role_id=2,
                                        user_name='" . $users[$userId]['user_name'] . "',
                                        user_password='" . $users[$userId]['user_password'] . "',
                                        user_email='" . $users[$userId]['user_email'] . "',
                                        user_full_name='" . $users[$userId]['user_full_name'] . "',
                                        date_added='" . setMysqlCurrentDateTime() . "'
                                    ";
                                    $result = mysqli_query($connection, $sql);
                                }
                            }
                        }

                        $usersValidators = array();
                        // get validation commission users
                        $sql    = "
                        SELECT
                            user_id
                        FROM
                            tbl_elections_validation_commissions
                        WHERE
                            election_id=$id
                        ";
                        $result = mysqli_query($connection, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $usersValidators[] = $row['user_id'];
                            }
                        }
                        ?>
                        <form name="electionMembers" method="post" action="elections.php?members=<?= $id ?>" novalidate autocomplete="off">
                            <div class="form-group row">
                                <label for="election_name" class="col-sm-4 col-form-label">Selectaţi membrii comisiei <span>*</span></label>
                                <div class="col-sm-8">
                                    <select
                                            id="user_ids"
                                            name="user_ids[]"
                                            class="form-control input-lg"
                                            tabindex="1"
                                            style="height: 400px;"
                                            autofocus
                                            multiple
                                            required
                                    >
                                        <?php
                                        if (count($users) > 0) {
                                            foreach ($users as $userId => $userData) { ?>
                                                <option value="<?= $userId ?>"<?= in_array($userId, $usersValidators) ? ' SELECTED' : '' ?>>
                                                    <?= $userData['user_full_name'] ?>
                                                </option>
                                            <?php }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mt-5">
                                <button type="submit" class="btn btn-dark btn-lg">Trimite</button>
                            </div>
                        </form>
                        <?php
                    }

                    ?>

                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="py-3 px-4 border-bottom bg-section">
                                <h4 class="mb-0">Sesiuni alegeri</h4>
                            </div>

                            <div class="card-body card-results">
                                <div class="buttonsContainer">
                                    <a class="btn btn-lg btn-dark btn-fw" href="elections.php?add">Adăugare</a>
                                    <a class="btn btn-lg btn-dark btn-fw" href="elections.php">Vizualizare</a>
                                </div>

                                <?php
                                if (isset($_GET['add'])) {
                                    recordForm($connection);
                                } else if (isset($_GET['edit'])) {
                                    $electionId = $_GET['edit'];

                                    // check if election id is valid
                                    if (preg_match("/^[1-9][0-9]*$/", $electionId)) {
                                        recordForm($connection, $electionId);
                                    } else {
                                        echo '<p class="text-warning">ID-ul nu este valid sau categoria nu este disponibilă!</p>';
                                    }
                                } else if (isset($_GET['members'])) {
                                    $electionId = $_GET['members'];

                                    // check if election id is valid
                                    if (preg_match("/^[1-9][0-9]*$/", $electionId)) {
                                        members($connection, $electionId);
                                    } else {
                                        echo '<p class="text-warning">ID-ul nu este valid sau categoria nu este disponibilă!</p>';
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