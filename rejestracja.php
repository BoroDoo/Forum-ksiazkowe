<?php
include('config.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $haslo = password_hash($_POST['haslo'], PASSWORD_DEFAULT); // Hashowanie hasła przed zapisaniem

   
    $sql = "INSERT INTO uzytkownicy (login, haslo) VALUES ('$login', '$haslo')";

    if ($conn->query($sql) === TRUE) {
        header("Location: logowanie.php");
    } else {
        echo "Błąd: " . $conn->error;
    }

}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <header class="sidebar">
        <a href="index.php" class="button">Strona Główna</a>
    </header>
    <div class="container2">
        <div class="form-container">
            <h1>Rejestracja</h1>
            <form method="post" action="rejestracja.php">
                <label for="login" class="form-label">Login:</label><br>
                <input type="text" name="login" class="form-input" required><br><br>
                <label for="haslo" class="form-label">Hasło:</label><br>
                <input type="password" name="haslo" class="form-input" required><br><br>
                <input type="submit" value="Zarejestruj się" class="button"><br><br>
            </form>
        </div>
    </div>
</div>
</body>
</html>
