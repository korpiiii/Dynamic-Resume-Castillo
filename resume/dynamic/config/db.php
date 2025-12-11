<?php
// Database config
$host = 'localhost';
$dbname = 'resume_db';
$user = 'root';
$pass = ''; // Default XAMPP = no password

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

    // Set error mode to exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Example query (THIS should now work)
$stmt = $pdo->query("SELECT * FROM about");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
