<?php
include 'db.php';

// 1. Disable Foreign Key Checks to allow cleaning and structural changes
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

// 2. Fix the sector table structure (sector_name must be a string, not an int)
mysqli_query($conn, "ALTER TABLE sector MODIFY sector_name VARCHAR(100)");
echo "Table structure fixed...<br>";

// 3. Clear existing data
mysqli_query($conn, "TRUNCATE TABLE slot");
mysqli_query($conn, "TRUNCATE TABLE sector");
mysqli_query($conn, "TRUNCATE TABLE user");
mysqli_query($conn, "TRUNCATE TABLE university");
echo "Data cleared...<br>";

// 4. Create a System Admin User (ID 99) 
// This is required because your 'sector' table requires a user_id
$admin_id = 99;
mysqli_query($conn, "INSERT INTO user (user_id, name, email, password, vehical_number) 
                    VALUES ($admin_id, 'System Admin', 'admin@parking.com', 'admin123', 'ADMIN-01')");
mysqli_query($conn, "INSERT INTO university (user_id, university_id) VALUES ($admin_id, 1001)");
echo "System Admin created...<br>";

// 5. Insert the 4 Sectors (Linking them to the Admin User)
$sectors = [
    1 => 'Sector A (Students)',
    2 => 'Sector B (Faculty)',
    3 => 'Sector C (Staff & Visitors)',
    4 => 'Sector D (University Reserved)'
];

foreach ($sectors as $id => $name) {
    // We include user_id here to satisfy your database constraint
    $q = "INSERT INTO sector (sector_id, sector_name, user_id) VALUES ($id, '$name', $admin_id)";
    mysqli_query($conn, $q);
}
echo "4 Sectors created and linked to Admin...<br>";

// 6. Helper function for vehicle types
function getVType($i) {
    $types = ['Car', 'Bike', 'Bicycle'];
    return $types[$i % 3];
}

// 7. Insert Slots for Sectors A, B, and C (30 slots each)
for ($s = 1; $s <= 3; $s++) {
    $sectorLetter = ($s == 1) ? 'A' : (($s == 2) ? 'B' : 'C');
    for ($i = 1; $i <= 30; $i++) {
        $slotName = $sectorLetter . "-" . str_pad($i, 2, '0', STR_PAD_LEFT);
        $type = getVType($i);
        mysqli_query($conn, "INSERT INTO slot (name, vehical_type, status, sector_id) 
                            VALUES ('$slotName', '$type', 'Available', $s)");
    }
}
echo "90 slots created for Sectors A, B, and C...<br>";

// 8. Insert Slots for Sector D (20 slots)
for ($i = 1; $i <= 20; $i++) {
    $slotName = "D-" . str_pad($i, 2, '0', STR_PAD_LEFT);
    mysqli_query($conn, "INSERT INTO slot (name, vehical_type, status, sector_id) 
                        VALUES ('$slotName', 'Car', 'Available', 4)");
}
echo "20 slots created for Sector D...<br>";

// 9. Re-enable Foreign Key Checks
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");

echo "<strong>SUCCESS: Database fully configured!</strong><br>";
echo "You can now log in as Admin with ID: <strong>99</strong> and Password: <strong>admin123</strong>";
?>