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
    <title>Alegeri on-line - Administrare - Utilizatori</title>

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
                <li class="nav-item active"><a href="users.php" class="nav-link"><i class="menu-icon typcn typcn-bell"></i><span class="menu-title">Utilizatori</span></a></li>
                <li class="nav-item"><a href="elections.php" class="nav-link"><i class="menu-icon typcn typcn-bell"></i><span class="menu-title">Sesiuni alegeri</span></a></li>
                <li class="nav-item"><a href="ballots.php" class="nav-link"><i class="menu-icon typcn typcn-bell"></i><span class="menu-title">Buletine de vot</span></a></li>
            </ul>
        </nav>

        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <?php

                    // default users listing
                    function listRecords ($connection) {
                        $records = array();

                        // get closed elections
                        $sql    = "
                        SELECT
                            user_id,
                            user_name,
                            user_email,
                            user_first_name,
                            user_last_name,
                            user_phones,
                            status,
                            date_format(date_added, '%d.%m.%Y') AS date_added
                        FROM
                            tbl_users
                        ORDER BY
                            user_first_name
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
                                        <th scope="col">Prenume</th>
                                        <th scope="col">Nume utilizator</th>
                                        <th scope="col">E-mail</th>
                                        <th scope="col">Telefon</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Op.</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    // display users
                                    foreach ($records as $key => $record) { ?>
                                        <tr>
                                            <td><?= $record['user_first_name'] ?></td>
                                            <td><?= $record['user_last_name'] ?></td>
                                            <td><?= $record['user_name'] ?></td>
                                            <td><?= $record['user_email'] ?></td>
                                            <td><?= $record['user_phones'] ?></td>
                                            <td>
                                                <?php
                                                if ($record['status'] == 1) { ?>
                                                    <span class="badge badge-success">ACTIV</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-danger">INACTIV</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-outline-dark dropdown-toggle" type="button" data-boundary="window" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="mdi mdi-chevron-down"></span>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a class="dropdown-item" href="users.php?edit=<?= $record['user_id'] ?>">Modifică</a>
                                                        <a class="dropdown-item" href="users.php?delete=<?= $record['user_id'] ?>">Şterge</a>
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
                            echo '<p class="mb-0 text-info">Momentan nu există sesiuni de alegeri!</p>';
                        } ?>
                    <?php }

                    // form used for both add and edit
                    function recordForm ($connection, $userId = null) {
                        $formEdit      = preg_match("/^[1-9][0-9]*$/", $userId);
                        $formAction    = $formEdit ? "users.php?edit=$userId" : 'users.php?add';
                        $formSubmitted = false;
                        $dataInserted  = false;

                        // if form is submitted (add or edit)
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $formSubmitted = true;

                            $userFirstName         = mysqli_real_escape_string($connection, htmlspecialchars($_POST['user_first_name']));
                            $userLastName          = mysqli_real_escape_string($connection, htmlspecialchars($_POST['user_last_name']));
                            $userName              = mysqli_real_escape_string($connection, htmlspecialchars($_POST['user_name']));
                            $userEmail             = mysqli_real_escape_string($connection, htmlspecialchars($_POST['user_email']));
                            $userPassword          = mysqli_real_escape_string($connection, htmlspecialchars($_POST['user_password']));
                            $userPasswordConfirmed = mysqli_real_escape_string($connection, htmlspecialchars($_POST['confirm_user_password']));
                            $userPhones            = mysqli_real_escape_string($connection, htmlspecialchars($_POST['user_phones']));
                            $userStatus            = mysqli_real_escape_string($connection, htmlspecialchars($_POST['status']));

                            $errors = array();

                            // validate form fields
                            if (empty($userFirstName)) {
                                $errors[] = "Specificaţi numele utilizatorului.";
                            }

                            if (empty($userLastName)) {
                                $errors[] = "Specificaţi prenumele utilizatorului.";
                            }

                            if (empty($userName)) {
                                $errors[] = "Specificaţi numele utilizatorului pentru autentificare.";
                            }

                            if (!isValidEmail($userEmail)) {
                                $errors[] = "Specificaţi e-mailul utilizatorului.";
                            }

                            if (empty($userPassword) || $userPassword != $userPasswordConfirmed) {
                                $errors[] = "Parola nu este corect confirmată.";
                            }

                            // if errors encountered then display them
                            if (count($errors) > 0) {
                                foreach ($errors as $error) {
                                    echo '<p class="text-danger">' . $error . '</p>';
                                }
                            } else {
                                // users db table management
                                if (!$formEdit) {
                                    // add user
                                    $sql = "
                                    INSERT INTO
                                        tbl_users
                                        (
                                            user_id,
                                            user_name,
                                            user_email,
                                            user_password,
                                            user_first_name,
                                            user_last_name,
                                            user_phones,
                                            status,
                                            date_added
                                        )
                                    VALUES (
                                        NULL,
                                        '$userName',
                                        '$userEmail',
                                        '" . md5($userPassword) . "',
                                        '$userFirstName',
                                        '$userLastName',
                                        '" . (!empty($userPhones) ? $userPhones : "") . "',
                                        $userStatus,
                                        '" . setMysqlCurrentDateTime() . "'
                                        )
                                    ";
                                } else {
                                    // edit user
                                    $sql = "
                                    UPDATE
                                        tbl_users
                                    SET
                                         user_name='$userName',
                                         user_email='$userEmail',
                                         user_password='" . md5($userPassword) . "',
                                         user_first_name='$userFirstName',
                                         user_last_name='$userLastName',
                                         user_phones='" . (!empty($userPhones) ? $userPhones : "") . "',
                                         status=$userStatus,
                                         date_last_modified='" . setMysqlCurrentDateTime() . "'
                                    WHERE
                                        user_id=$userId
                                    ";
                                }
                                $result = mysqli_query($connection, $sql);
                                if ($result) {
                                    // form fields successfully added into db table
                                    $dataInserted = true;

                                    if (!$formEdit) {
                                        echo '<p class="text-success">Utilizatorul a fost adăugat cu succes.</p>';
                                    } else {
                                        echo '<p class="text-success">Utilizatorul a fost modificat cu succes.</p>';
                                    }
                                } else {
                                    if (!$formEdit) {
                                        echo '<p class="text-danger">Eroare la adăugarea utilizatorului.</p>';
                                    } else {
                                        echo '<p class="text-danger">Eroare la modificarea utilizatorului.</p>';
                                    }
                                }
                            }
                        } else if ($formEdit) {
                            // get user details and fill form with these details only on edit
                            $sql    = "
                            SELECT
                                user_id,
                                user_name,
                                user_email,
                                user_first_name,
                                user_last_name,
                                user_phones,
                                status,
                                date_format(date_added, '%d.%m.%Y') AS date_added
                            FROM
                                tbl_users
                            WHERE
                                user_id=$userId
                            ";
                            $result = mysqli_query($connection, $sql);
                            if (mysqli_num_rows($result) == 1) {
                                $row                   = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                $userFirstName         = $row['user_first_name'];
                                $userLastName          = $row['user_last_name'];
                                $userName              = $row['user_name'];
                                $userEmail             = $row['user_email'];
                                $userPassword          = '';
                                $userPasswordConfirmed = '';
                                $userPhones            = $row['user_phones'];
                                $userStatus            = $row['status'];
                            }
                        } else {
                            // user go to add, fields are empty but default status is "Activ" (1)
                            $userStatus = 1;
                        }

                        if (!($formSubmitted && $dataInserted && !$formEdit)) {
                            ?>
                            <form name="users" method="post" action="<?= $formAction ?>" novalidate autocomplete="off">
                                <div class="form-group row">
                                    <label for="user_first_name" class="col-sm-4 col-form-label">Nume <span>*</span></label>
                                    <div class="col-sm-8">
                                        <input
                                                type="text"
                                                id="user_first_name"
                                                name="user_first_name"
                                                value="<?= $userFirstName ?>"
                                                class="form-control input-lg"
                                                tabindex="1"
                                                autofocus
                                                required
                                        >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="user_last_name" class="col-sm-4 col-form-label">Prenume <span>*</span></label>
                                    <div class="col-sm-8">
                                        <input
                                                type="text"
                                                id="user_last_name"
                                                name="user_last_name"
                                                value="<?= $userLastName ?>"
                                                class="form-control input-lg"
                                                tabindex="2"
                                                required
                                        >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="user_name" class="col-sm-4 col-form-label">Nume utilizator <span>*</span></label>
                                    <div class="col-sm-8">
                                        <input
                                                type="text"
                                                id="user_name"
                                                name="user_name"
                                                value="<?= $userName ?>"
                                                class="form-control input-lg"
                                                tabindex="3"
                                                required
                                        >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="user_email" class="col-sm-4 col-form-label">E-mail <span>*</span></label>
                                    <div class="col-sm-8">
                                        <input
                                                type="email"
                                                id="user_email"
                                                name="user_email"
                                                value="<?= $userEmail ?>"
                                                class="form-control input-lg"
                                                tabindex="4"
                                                required
                                        >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="user_password" class="col-sm-4 col-form-label">Parola <span>*</span></label>
                                    <div class="col-sm-8">
                                        <input
                                                type="password"
                                                id="user_password"
                                                name="user_password"
                                                class="form-control input-lg"
                                                placeholder="*********"
                                                tabindex="5"
                                                required
                                        >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="confirm_user_password" class="col-sm-4 col-form-label">Confirmare parola <span>*</span></label>
                                    <div class="col-sm-8">
                                        <input
                                                type="password"
                                                id="confirm_user_password"
                                                name="confirm_user_password"
                                                class="form-control input-lg"
                                                placeholder="*********"
                                                tabindex="6"
                                                required
                                        >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="user_phones" class="col-sm-4 col-form-label">Nr. telefon</label>
                                    <div class="col-sm-8">
                                        <input
                                                type="text"
                                                id="user_phones"
                                                name="user_phones"
                                                value="<?= $userPhones ?>"
                                                class="form-control input-lg"
                                                tabindex="7"
                                        >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="status" class="col-sm-4 col-form-label">Status <span>*</span></label>
                                    <div class="col-sm-8">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-light<?= ($userStatus == 1 ? ' active' : '') ?>" for="status1">
                                                <input
                                                        type="radio"
                                                        id="status1"
                                                        name="status"
                                                        value="1"
                                                    <?= ($userStatus == 1 ? 'checked' : '') ?>
                                                >
                                                Activ
                                            </label>
                                            <label class="btn btn-light<?= ($userStatus == 0 ? ' active' : '') ?>" for="status0">
                                                <input
                                                        type="radio"
                                                        id="status0"
                                                        name="status"
                                                        value="0"
                                                    <?= ($userStatus == 0 ? 'checked' : '') ?>
                                                >
                                                Inactiv
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-5">
                                    <button type="submit" class="btn btn-dark btn-lg">Trimite</button>
                                </div>
                            </form>
                        <?php }
                    }

                    // delete user by ID
                    function delete ($connection, $id) {
                        $sql    = "
                        DELETE
                        FROM
                            tbl_users
                        WHERE
                            user_id=$id
                        ";
                        $result = mysqli_query($connection, $sql);
                        if ($result) {
                            echo '<p class="text-success">Utilizatorul a fost şters cu succes.</p>';
                        } else {
                            echo '<p class="text-danger">Eroare la ştergerea utilizatorului.</p>';
                        }
                    }

                    ?>

                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="py-3 px-4 border-bottom bg-section">
                                <h4 class="mb-0">Utilizatori</h4>
                            </div>

                            <div class="card-body card-results">
                                <div class="buttonsContainer">
                                    <a class="btn btn-lg btn-dark btn-fw" href="users.php?add">Adăugare</a>
                                    <a class="btn btn-lg btn-dark btn-fw" href="users.php">Vizualizare</a>
                                </div>

                                <?php
                                if (isset($_GET['add'])) {
                                    recordForm($connection);
                                } else if (isset($_GET['edit'])) {
                                    $userId = $_GET['edit'];

                                    // check if user id is valid
                                    if (preg_match("/^[1-9][0-9]*$/", $userId)) {
                                        recordForm($connection, $userId);
                                    } else {
                                        echo '<p class="text-warning">ID-ul nu este valid sau categoria nu este disponibilă!</p>';
                                    }
                                } else if (isset($_GET['delete'])) {
                                    $userId = $_GET['delete'];

                                    // check if user id is valid
                                    if (preg_match("/^[1-9][0-9]*$/", $userId)) {
                                        delete($connection, $userId);
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