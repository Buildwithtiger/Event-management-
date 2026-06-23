<?php
require_once 'config.php';

// Delete existing admin user first (optional, but clean)
$pdo->prepare("DELETE FROM admin_users WHERE username = 'admin'")->execute();

// Create new admin with password 'admin123'
$password_hash = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO admin_users (username, password) VALUES (?, ?)");
$stmt->execute(['admin', $password_hash]);

echo "Admin user created/updated successfully!<br>";
echo "Username: admin<br>";
echo "Password: admin123<br>";
echo "Hash stored: " . $password_hash;
?>