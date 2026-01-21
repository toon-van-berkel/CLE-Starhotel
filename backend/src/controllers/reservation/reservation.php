<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';


header('Content-Type: application/json; charset=utf-8');

session_start();

header('Content-Type: application/json; charset=utf-8');

$loggedin = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

if (!$loggedin) {
    header('Location: /rooms');
    exit();
}


$input = json_decode(file_get_contents('php://input'), true);

if (isset($_POST['submit'])) {
    
    $first_name  = htmlentities($_POST['first_name']);
    $last_name   = htmlentities($_POST['last_name']);
    $email       = htmlentities($_POST['email']);
    $booked_from = htmlentities($_POST['booked_from']);
    $booked_till = htmlentities($_POST['booked_till']);

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

    if (!empty($errors)) {
        echo json_encode(['errors' => $errors]);
        exit();
    }
    
}

    


try {

    $user_id = $_SESSION['user_id'];

    $pdoget = db();

           $stmtget = $pdoget->prepare('
                SELECT `first_name`, `last_name`, `email` FROM `users` WHERE id = :user_id
    ');


    $stmtget->execute([
        ':user_id' => $user_id
    ]);





        $pdoadd = db();

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


    

} catch (PDOException $e) {
    $pdoadd->rollBack();
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}