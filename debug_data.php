<?php
// Simple debug script to understand data structure
echo "Debugging data structure\n";

// Connect to database directly
$host = 'localhost';
$dbname = 'fmrr_dev';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get years data
    $stmt = $pdo->query("SELECT * FROM years LIMIT 5");
    $years = $stmt->fetchAll(PDO::FETCH_OBJ);
    echo "Years data structure:\n";
    var_dump($years);
    
    // Get entities data
    $stmt = $pdo->query("SELECT * FROM entities LIMIT 5");
    $entities = $stmt->fetchAll(PDO::FETCH_OBJ);
    echo "\nEntities data structure:\n";
    var_dump($entities);
    
    // Get a sample regulation
    $stmt = $pdo->query("SELECT * FROM regulations LIMIT 1");
    $regulation = $stmt->fetch(PDO::FETCH_OBJ);
    echo "\nSample regulation data structure:\n";
    var_dump($regulation);
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>