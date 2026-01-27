<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';


header('Content-Type: application/json; charset=utf-8');

session_start();

header('Content-Type: application/json; charset=utf-8');

function loginCheck() {

    $loggedin = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

    if (!$loggedin) {
    header('Location: /rooms');
    exit();
    }
}

function upload() {

// $user_id = $_SESSION['user_id'] ?? null;
//     if (!$user_id) {
//         echo json_encode(['error' => 'Not authenticated']);
//         exit();
//     }

    $user_id = 1;

    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($_POST['submit'])) {
    
    $first_name  = htmlentities($_POST['first_name']);
    $last_name   = htmlentities($_POST['last_name']);
    $email       = htmlentities($_POST['email']);
    $booked_from = htmlentities($_POST['booked_from']);
    $booked_till = htmlentities($_POST['booked_till']);
    $current_capacity      = htmlentities($_POST['current_capacity']);
    $max_capacity      = htmlentities($_POST['max_capacity']);

    $errors = [];

    if ($first_name === "" || is_numeric($first_name)) {
        $errors['first_name'] = "Invalid first_name";
    }

    if ($last_name === "" || is_numeric($last_name)) {
        $errors['last_name'] = "Invalid last_name";
    }

    if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email";
    }

    if ($booked_from === "" || $booked_till === "") {
        $errors['booking'] = "Booking dates required";
    } elseif (strtotime($booked_from) === false || strtotime($booked_till) === false) {
        $errors['booking'] = "Invalid booking dates";
    } elseif (strtotime($booked_from) >= strtotime($booked_till)) {
        $errors['booking'] = "Check-out date must be after check-in date";
    }

    if ($max_capacity === "" || !ctype_digit($max_capacity) || (int)$max_capacity < 1) {
        $errors['max_capacity'] = "Invalid max capacity";
    }

    if ($current_capacity === "" || !ctype_digit($current_capacity) || (int)$current_capacity < 1) {
        $errors['current_capacity'] = "Invalid number of people";
    } elseif (ctype_digit($max_capacity) && (int)$current_capacity > (int)$max_capacity) {
        $errors['current_capacity'] = "Number of people exceeds max capacity";
    }

    if (!empty($errors)) {
        echo json_encode(['errors' => $errors]);
        exit();
    }

        $pdoadd = db();

        $stmtUser = $pdoadd->prepare('
            UPDATE `users`
            SET `first_name` = :first_name,
                `last_name`  = :last_name,
                `email`      = :email
            WHERE `id` = :user_id
        ');
        $stmtUser->execute([
            ':first_name' => $first_name,
            ':last_name'  => $last_name,
            ':email'      => $email,
            ':user_id'    => $user_id
        ]);

        $stmtadd = $pdoadd->prepare('
            INSERT INTO `reservations`(`user_id`, `status_id`, `booked_from`, `booked_till`) 
            VALUES (:user_id, :status_id, :booked_from, :booked_till)
        ');

        $stmtadd->execute([
            ':user_id' => $user_id,
            ':status_id' => 'pending',
            ':booked_from' => $booked_from,
            ':booked_till' => $booked_till
        ]);
    
    }
}
    


try {

    // $user_id = $_SESSION['user_id'];

    $pdoget = db();

           $stmtget = $pdoget->prepare('
                SELECT `first_name`, `last_name`, `email` FROM `users` WHERE id = :user_id
    ');


    $stmtget->execute([
        ':user_id' => $user_id
    ]);





    

} catch (PDOException $e) {
    if (isset($pdoadd)) {
        $pdoadd->rollBack();
    }
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}




function uploadTest () {

    $user_id = 1;

    $first_name  = htmlentities($_POST['first_name']);
    $last_name   = htmlentities($_POST['last_name']);
    $email       = htmlentities($_POST['email']);
    $booked_from = htmlentities($_POST['booked_from']);
    $booked_till = htmlentities($_POST['booked_till']);
    $current_capacity      = htmlentities($_POST['current_capacity']);
    
    
    
    $pdoadd = db();

        $stmtUser = $pdoadd->prepare('
            UPDATE `users`
            SET `first_name` = :first_name,
                `last_name`  = :last_name,
            WHERE `id` = :user_id
        ');
        $stmtUser->execute([
            ':first_name' => $first_name,
            ':last_name'  => $last_name,
            ':user_id'    => $user_id
        ]);

        $stmtadd = $pdoadd->prepare('
            INSERT INTO `reservations`(`user_id`, `status_id`, `booked_from`, `booked_till`) 
            VALUES (:user_id, :status_id, :booked_from, :booked_till)
        ');

        $stmtadd->execute([
            ':user_id' => $user_id,
            ':status_id' => 'pending',
            ':booked_from' => $booked_from,
            ':booked_till' => $booked_till
        ]);
    
    
}