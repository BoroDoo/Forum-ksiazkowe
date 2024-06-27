<?php
include('config.php');
session_start();

if (isset($_POST['id']) && isset($_SESSION['rola']) && $_SESSION['rola'] == 'admin') {
    $id = $_POST['id'];
    // Usuwanie recenzji powiązanych z książką
    $sqlRecenzje = "DELETE FROM recenzje WHERE idKsiazki = '$id'";
    $conn->query($sqlRecenzje);

    // Usuwanie książki
    $sql = "DELETE FROM ksiazki WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Książka została usunięta.";
    } else {
        echo "Błąd podczas usuwania książki: " . $conn->error;
    }

    header('Location: index.php');
} else {
    echo "Nie masz uprawnień do wykonania tej operacji.";
}
?>
