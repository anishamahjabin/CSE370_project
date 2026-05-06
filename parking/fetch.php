<?php
error_reporting(0);
include 'db.php';

header('Content-Type: application/json');

if (!$conn) {
    echo json_encode(["error" => "Connection failed"]);
    exit;
}

// Total seats per sector (must match dashboard.php)
$sector_totals = [
    1 => 100,  // Sector A - Students
    2 => 60,   // Sector B - Faculty
    3 => 50,   // Sector C - Staff
    4 => 20,   // Sector D - University Reserved
];

$data = [];

foreach ($sector_totals as $id => $total) {

    // Count active bookings (reservations with end_time in the future)
    $q_book = mysqli_query($conn,
        "SELECT COUNT(*) as cnt
         FROM reservation r
         JOIN slot s ON r.slot_id = s.slot_id
         WHERE s.sector_id = $id
         AND r.end_time >= NOW()"
    );
    $booked = $q_book ? (int)mysqli_fetch_assoc($q_book)['cnt'] : 0;

    // Count physically occupied slots that are NOT from a reservation
    // (i.e. walk-in / gate entry without a booking)
    $q_occ = mysqli_query($conn,
        "SELECT COUNT(*) as cnt
         FROM slot s
         WHERE s.sector_id = $id
         AND s.status = 'Occupied'
         AND s.slot_id NOT IN (
             SELECT r.slot_id FROM reservation r WHERE r.end_time >= NOW()
         )"
    );
    $occupied = $q_occ ? (int)mysqli_fetch_assoc($q_occ)['cnt'] : 0;

    $available = $total - $booked - $occupied;
    if ($available < 0) $available = 0;

    $data[$id] = [
        'total'     => $total,
        'occupied'  => $occupied,
        'booked'    => $booked,
        'available' => $available
    ];
}

echo json_encode($data);
?>