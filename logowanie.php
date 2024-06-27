<?php
include('config.php'); // Dołączenie pliku konfiguracyjnego z połączeniem do bazy danych
session_start(); // Rozpoczęcie sesji

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $haslo = $_POST['haslo'];

    // Przygotowanie zapytania SQL do sprawdzenia loginu użytkownika
    $sql = "SELECT id,rola, haslo FROM uzytkownicy WHERE login = '$login'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['haslo'];

        // Weryfikacja hasła
        if (password_verify($haslo, $hashed_password)) {
            $_SESSION['login'] = $login; // Przechowywanie loginu w sesji
            $_SESSION['id'] = $row['id']; // Przechowywanie ID użytkownika w sesji
            $_SESSION['rola'] = $row['rola'];
            header("location: index.php"); // Przekierowanie na stronę główną
        } else {
            echo "<p class='error-message'>Błędny login lub hasło!</p>";
        }
    } else {
        echo "<p class='error-message'>Błędny login lub hasło!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <header class="sidebar">
        <a href="index.php" class="button">Strona Główna</a>
    </header>
    <div class="container2">
        <div class="form-container">
            <h1>Logowanie</h1>
            <form method="post" action="logowanie.php">
                <label for="login" class="form-label">Login:</label><br>
                <input type="text" name="login" class="form-input" required><br><br>
                <label for="haslo" class="form-label">Hasło:</label><br>
                <input type="password" name="haslo" class="form-input" required><br><br>
                <input type="submit" value="Zaloguj się" class="button"><br><br>
            </form>
        </div>
    </div>
</div>
</body>
</html>
