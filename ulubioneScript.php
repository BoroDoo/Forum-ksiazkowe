<?php
include('config.php');
session_start();

if (!isset($_SESSION['login'])) {

    exit;
}

$idKsiazki = $_POST['idKsiazki'];
$action = $_POST['action'];
$nick = $_SESSION['login'];

// Pobierz id użytkownika na podstawie loginu
$sql = "SELECT id FROM uzytkownicy WHERE login = '$nick'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$idUzytkownika = $row['id'];

if ($action == 'add') {
    $sql = "INSERT INTO ulubione (idKsiazki, idUzytkownika) VALUES ('$idKsiazki', '$idUzytkownika')";
    if ($conn->query($sql) === TRUE) {
        echo 'added';
    } else {
        echo 'error';
    }
} elseif ($action == 'remove') {
    $sql = "DELETE FROM ulubione WHERE idKsiazki = '$idKsiazki' AND idUzytkownika = '$idUzytkownika'";
    if ($conn->query($sql) === TRUE) {
        echo 'removed';
    } else {
        echo 'error';
    }
}
?>
