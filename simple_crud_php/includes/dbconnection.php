<?php
$host = "localhost";
$dbname = "simplecruddb";
$username = "root";
$password = "";


try {
    // Create a PDO instance (this is the connection)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the error mode to throw exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: <br>" . $e->getMessage();
}
