<?php
// make_hash.php
// Usage: Run this script in your browser or with PHP CLI to generate a password hash
$password = isset($_GET['password']) ? $_GET['password'] : 'client123';
echo 'Password: ' . htmlspecialchars($password) . "<br>";
echo 'Hash: ' . password_hash($password, PASSWORD_DEFAULT);
