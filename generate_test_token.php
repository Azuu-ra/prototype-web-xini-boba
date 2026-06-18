<?php
include 'config/koneksi.php';

// Get user ID 4's email
$userQuery = mysqli_query($conn, "SELECT email FROM users WHERE id=4");
if ($userQuery && mysqli_num_rows($userQuery) > 0) {
    $user = mysqli_fetch_assoc($userQuery);
    $email = $user['email'];
    echo "User ID 4 email: " . htmlspecialchars($email) . "<br><br>";
    
    // Generate new token
    $token = bin2hex(random_bytes(32));
    $userId = 4;
    
    $insert = mysqli_query(
        $conn,
        "INSERT INTO password_resets (user_id, token, expires_at, used) 
         VALUES ($userId, '$token', DATE_ADD(NOW(), INTERVAL 1 HOUR), 0)"
    );
    
    if ($insert) {
        $link = "http://localhost/Xini_Boba/password_reset.php?action=reset&token=" . urlencode($token);
        echo "✓ New token generated!<br><br>";
        echo "Token: $token<br><br>";
        echo "Reset Link:<br>";
        echo '<a href="' . htmlspecialchars($link) . '" target="_blank">' . htmlspecialchars($link) . '</a>';
    } else {
        echo "✗ Error: " . mysqli_error($conn);
    }
} else {
    echo "✗ User not found";
}
?>
