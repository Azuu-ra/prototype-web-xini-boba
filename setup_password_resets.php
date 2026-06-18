<?php
include 'config/koneksi.php';

$sqlFile = file_get_contents('create_password_resets.sql');

// Split by semicolon to handle multiple statements
$statements = array_filter(array_map('trim', explode(';', $sqlFile)));

foreach ($statements as $statement) {
    if (!empty($statement)) {
        if (mysqli_query($conn, $statement)) {
            echo "✓ SQL executed successfully\n";
        } else {
            echo "✗ Error: " . mysqli_error($conn) . "\n";
        }
    }
}

// Verify table creation
$result = mysqli_query($conn, 'SHOW TABLES LIKE "password_resets"');
if ($result && mysqli_num_rows($result) > 0) {
    echo "\n✓ Table 'password_resets' created successfully!\n\n";
    
    // Show table structure
    $structure = mysqli_query($conn, 'DESCRIBE password_resets');
    echo "Table structure:\n";
    echo "================\n";
    while ($row = mysqli_fetch_assoc($structure)) {
        echo $row['Field'] . " (" . $row['Type'] . ")\n";
    }
} else {
    echo "\n✗ Table creation failed\n";
}

mysqli_close($conn);
?>
