<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';



function getReservationDetails(PDO $pdo, int $room_id, string $booked_from, string $booked_till, int $totalPeople): ?array
{
    $stmt = $pdo->prepare('
        SELECT 
            accommodations.name,
            rooms.current_capacity,
            reservations.booked_from,
            reservations.booked_till
        FROM accommodations a
        JOIN rooms r ON a.id = r.accommodation_id
        JOIN reservations res ON r.id = res.room_id
        WHERE r.id = :room_id
        AND res.booked_from = :booked_from
        AND res.booked_till = :booked_till
        AND r.current_capacity = :totalPeople
    ');

    $stmt->execute([
        ':room_id' => $room_id,
        ':booked_from' => $booked_from,
        ':booked_till' => $booked_till,
        ':totalPeople' => $totalPeople,
    ]);

    $reservation = $stmt->fetch();

    return $reservation ?: null;


    $room_id = (int)($_GET['room_id'] ?? 0);
    $booked_from    = (string)($_GET['booked_from'] ?? '');
    $booked_till    = (string)($_GET['booked_till'] ?? '');
    $totalPeople = (int)($_GET['totalPeople'] ?? 0);


    
}

