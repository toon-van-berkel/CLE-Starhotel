<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = db();
    
    $name      = $_GET['name'] ?? '';
    $totalPeople = (int)($_GET['totalPeople'] ?? 0);
    $bookedFrom = $_GET['booked_from'] ?? '';
    $bookedTill = $_GET['booked_till'] ?? '';

    //  error_log("Received dates: from=$bookedFromRaw, till=$bookedTillRaw");

    // // Keep only the date part before any space/time
    // $bookedFrom = explode(' ', $bookedFromRaw)[0];
    // $bookedTill = explode(' ', $bookedTillRaw)[0];

    // // Validate date format YYYY-MM-DD
    // $fromDt = \DateTime::createFromFormat('Y-m-d', $bookedFrom);
    // $tillDt = \DateTime::createFromFormat('Y-m-d', $bookedTill);
    // if (!$fromDt || !$tillDt) {
    //     http_response_code(400);
    //     echo json_encode(['error' => 'Invalid date format'], JSON_UNESCAPED_UNICODE);
    //     exit;
    // }

    
      $stmt = $pdo->prepare("
        SELECT 
            :name as name,
            :totalPeople as current_capacity,
            res.booked_from,
            res.booked_till
        FROM reservations res
        WHERE res.booked_from = :booked_from
        AND res.booked_till = :booked_till
        LIMIT 1");

    $stmt->execute([
        ':name' => $name,
        ':booked_from' => $bookedFrom,
        ':booked_till' => $bookedTill,
        ':totalPeople' => $totalPeople
    ]);

    $confirmation = $stmt->fetch();
    echo json_encode($confirmation, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}   

// try {
//     $pdo = db();

//     $stmt = $pdo->prepare("
//         SELECT 
//             a.name AS kamer,
//             r.current_capacity,
//             res.booked_from,
//             res.booked_till
//         FROM accommodations a
//         JOIN rooms r ON a.id = r.accommodation_id
//         JOIN reservations res ON r.id = res.room_id
//         WHERE r.id = :room_id
//         AND res.booked_from = :booked_from
//         AND res.booked_till = :booked_till
//         AND r.current_capacity = :totalPeople)");
//     // $stmt = $pdo->prepare("SELECT * FROM reservations");


//     $stmt->execute([
//         ':room_id' => $_GET['room_id'] ?? 0,
//         ':booked_from' => $_GET['booked_from'] ?? '',
//         ':booked_till' => $_GET['booked_till'] ?? '',
//         ':totalPeople' => $_GET['totalPeople'] ?? 0
//     ]);


//     $confirmation = $stmt->fetch();

//     echo json_encode($confirmation, JSON_UNESCAPED_UNICODE);
// } catch (Throwable $e) {
//     http_response_code(500);
//     echo json_encode(['error' => 'Internal Server Error'], JSON_UNESCAPED_UNICODE);
// }

