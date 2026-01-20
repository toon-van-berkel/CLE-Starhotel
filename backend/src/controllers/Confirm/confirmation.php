<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';


header('Content-Type: application/json; charset=utf-8');

// SELECT 
//             accommodations.name,
//             rooms.max_capacity,
//             reservations.booked_from,
//             reservations.booked_till
//         FROM accommodations a
//         JOIN rooms r ON a.id = r.accommodation_id
//         JOIN reservations res ON r.id = res.room_id
//         WHERE r.id = :room_id
//         AND res.booked_from = :booked_from
//         AND res.booked_till = :booked_till
//         AND r.max_capacity = :totalPeople

// $reservationId = filter_input(INPUT_GET, 'reservation_id', FILTER_VALIDATE_INT);
$reservationId = 1;
if (!$reservationId) {
    http_response_code(400);
    echo json_encode(['record' => [], 'error' => 'reservation_id is required'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {

        $pdo = db();

           $stmt = $pdo->prepare('
                SELECT 
            res.id          AS reservation_id,
            r.id            AS room_id,
            res.booked_from,
            res.booked_till,
            a.id            AS accommodation_id,
            a.name          AS accommodation_name
        FROM reservations res
        LEFT JOIN reservation_rooms rr   ON rr.reservation_id = res.id
        LEFT JOIN rooms r                ON r.id = rr.room_id
        LEFT JOIN room_accommodations ra ON ra.room_id = r.id
        LEFT JOIN accommodations a       ON a.id = ra.accommodation_id
        WHERE res.id = :reservation_id
    ');
        $stmt->execute([':reservation_id' => $reservationId]);

        $reservation = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'record' => $reservation
        ], JSON_UNESCAPED_UNICODE);
    } catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'record' => [],
        'error' => 'Internal server error'
    ], JSON_UNESCAPED_UNICODE);
}




    // $stmt1 = $pdo->prepare('
    //     SELECT name FROM accommodations WHERE id = 1
        


    // ');
    // $stmt2 = $pdo->prepare('
    //     SELECT max_capacity FROM rooms WHERE id = 1


    // ');
    // $stmt3 = $pdo->prepare('
    //     SELECT `booked_from`, `booked_till` FROM `reservations` WHERE id = 1


    // ');

    // function allStmt (query $stmt1, query $stmt2, query $stmt3) {

        // $stmt1->execute([
        //     ':accommodations_id' => $room_id,
        // ]);
        // $stmt2->execute([
        //     ':max_capacity' => $totalPeople,
            
        // ]);
        // $stmt3->execute([
        //     ':booked_till' => $booked_till,
        //     ':booked_from' => $booked_from,
            
        // ]);

    // }


    


