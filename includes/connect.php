<?php 
$host = "172.31.22.43"; //hostname
$db = "Naomi200645137"; //database name
$user = "Naomi200645137"; //username
$password = "jztLDwD6JZ"; //password

$dsn = "mysql:host=$host;dbname=$db";


try {
   $pdo = new PDO ($dsn, $user, $password); 
   $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage()); 
}