<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ebiblioteka";

// Tworzenie połączenia
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
