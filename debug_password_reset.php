<?php
include 'config/koneksi.php';

echo "<h2>Debug Password Reset</h2>";
echo "<hr>";

// Check if table exists
$tableCheck = mysqli_query($conn, 'SHOW TABLES LIKE "password_resets"');
echo "✓ Table exists: " . (mysqli_num_rows($tableCheck) > 0 ? "YES" : "NO") . "<br><br>";

// Show all tokens in database
echo "<h3>Tokens in Database:</h3>";
$result = mysqli_query($conn, "SELECT id, user_id, token, expires_at, used, created_at FROM password_resets ORDER BY created_at DESC LIMIT 10");

if ($result && mysqli_num_rows($result) > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>User ID</th><th>Token (first 20 chars)</th><th>Expires At</th><th>Used</th><th>Created</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['user_id'] . "</td>";
        echo "<td>" . substr($row['token'], 0, 20) . "...</td>";
        echo "<td>" . $row['expires_at'] . "</td>";
        echo "<td>" . ($row['used'] ? "YES" : "NO") . "</td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "❌ No tokens found in database";
}

echo "<br><br>";

// Check server time vs database time
$timeCheck = mysqli_query($conn, "SELECT NOW() as db_time");
$timeRow = mysqli_fetch_assoc($timeCheck);
echo "<h3>Time Check:</h3>";
echo "Server time: " . date('Y-m-d H:i:s') . "<br>";
echo "Database time: " . $timeRow['db_time'] . "<br>";

// Test token validation
if (isset($_GET['test_token'])) {
    $testToken = $_GET['test_token'];
    echo "<h3>Testing Token Validation:</h3>";
    echo "Test token: " . substr($testToken, 0, 30) . "...<br>";
    
    $tokenEsc = mysqli_real_escape_string($conn, $testToken);
    $testQuery = mysqli_query($conn, "SELECT * FROM password_resets WHERE token='$tokenEsc' AND used=0 AND expires_at > NOW() LIMIT 1");
    
    if ($testQuery && mysqli_num_rows($testQuery) > 0) {
        echo "✓ Token found and valid!<br>";
        $row = mysqli_fetch_assoc($testQuery);
        echo "<pre>";
        print_r($row);
        echo "</pre>";
    } else {
        echo "❌ Token not found or expired<br>";
        
        // Try without expires check
        $testQuery2 = mysqli_query($conn, "SELECT * FROM password_resets WHERE token='$tokenEsc' LIMIT 1");
        if ($testQuery2 && mysqli_num_rows($testQuery2) > 0) {
            echo "But found without expiry check:<br>";
            $row = mysqli_fetch_assoc($testQuery2);
            echo "<pre>";
            print_r($row);
            echo "</pre>";
        }
    }
}

?>
<br><a href="password_reset.php">Back to Password Reset</a>
