<?php
// db.php
$host = "localhost";
$db = "viniat";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}
?>
