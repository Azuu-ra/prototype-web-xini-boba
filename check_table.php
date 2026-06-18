<?php
include 'config/koneksi.php';

$result = mysqli_query($conn, 'SHOW TABLES LIKE "password_resets"');

if ($result && mysqli_num_rows($result) > 0) {
    echo "✓ Table password_resets EXISTS\n";
    
    // Show table structure
    $structure = mysqli_query($conn, 'DESCRIBE password_resets');
    echo "\nTable structure:\n";
    while ($row = mysqli_fetch_assoc($structure)) {
        echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
} else {
    echo "✗ Table password_resets DOES NOT EXIST\n";
    echo "You need to create it first!\n";
}

// Check data in table (if exists)
$dataCheck = mysqli_query($conn, 'SELECT COUNT(*) as count FROM password_resets');
if ($dataCheck) {
    $data = mysqli_fetch_assoc($dataCheck);
    echo "\nRows in table: " . $data['count'] . "\n";
}
?>
