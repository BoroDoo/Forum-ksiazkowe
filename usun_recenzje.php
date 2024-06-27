<?php
include('config.php');
session_start();

if (isset($_POST['recenzja_id']) && isset($_POST['idKsiazki'])  || (isset($_SESSION['login']) && $_SESSION['login'] == $_POST['nick'])) {
    $recenzja_id = $_POST['recenzja_id'];
    $idKsiazki = $_POST['idKsiazki'];

    // Usuwanie konkretnej recenzji
    $sql = "DELETE FROM recenzje WHERE id = '$recenzja_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Recenzja została usunięta.";
    } else {
        echo "Błąd podczas usuwania recenzji: " . $conn->error;
    }

    header("Location: ksiazka.php?id=$idKsiazki");
} else {
    echo "Nie masz uprawnień do wykonania tej operacji.";
}
?>
