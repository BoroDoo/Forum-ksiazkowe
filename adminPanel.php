<?php
include('config.php'); // Dołączenie pliku konfiguracyjnego z połączeniem do bazy danych
session_start(); // Rozpoczęcie sesji
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php if (isset($_SESSION['login'])):?>
    <div class="welcome" style="text-align:right;">
    <p >Witaj, <?php echo $_SESSION['login']; ?>!</p>
</div>
    <?php endif; ?>
<div class="container">


<?php if (isset($_SESSION['login'])&& isset($_SESSION['rola'])&&  ($_SESSION['rola']=='user')): ?>

<a href="index.php" class="button">Strona Główna</a> |<a href="moje_recenzje.php" class="button">Moje recenzje</a> |<a href="ulubione.php" class="button">Moje ulubione książki</a>  | <a href="logout.php" class="button">Wyloguj się</a>
    
<?php elseif (isset($_SESSION['login'])&& isset($_SESSION['rola'])&& ($_SESSION['rola']=='admin')): ?>

<a href="index.php" class="button">Strona Główna</a> | <a href="moje_recenzje.php" class="button">Moje recenzje</a> |<a href="ulubione.php" class="button">Moje ulubione książki</a>  | <a href="adminPanel.php" class="button">Panel Administracyjny</a> | <a href="logout.php" class="button">Wyloguj się</a>

<br><br><a href="kategorie.php" class="button">Zarzadzanie kategoriami</a>


</div>  
<?php else: ?>
    <a href="logowanie.php" class="button">Zaloguj się</a>  
    <a href="rejestracja.php" class="button">Zarejestruj się</a>
<?php endif; ?></header>
    <div class="container">
        <h1>Panel Administratora</h1>

        <!-- Delete ksiazek -->
        <div class="form-container">
            <h2>Usuń Książkę</h2>
            <form method="post" action="adminPanel.php">
                <label for="bookId">Książka:</label><br>
                <select name="bookId" class="form-select">
                    <?php
                    $sql = "SELECT id, tytul FROM ksiazki";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "' class='form-option'>" . $row['tytul'] . "</option>";
                    }
                    ?>
                </select><br><br>
                <input type="submit" name="deleteBook" value="Usuń książkę" class="button"><br><br>
            </form>
        </div>

        <!-- Delete user -->
        <div class="form-container">
            <h2>Usuń Użytkownika</h2>
            <form method="post" action="adminPanel.php">
                <label for="userId">Użytkownik:</label><br>
                <select name="userId" class="form-select">
                    <?php
                    $sql = "SELECT id, login FROM uzytkownicy";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "' class='form-option'>" . $row['login'] . "</option>";
                    }
                    ?>
                </select><br><br>
                <input type="submit" name="deleteUser" value="Usuń użytkownika" class="button"><br><br>
            </form>
        </div>

        <!-- Delete rec -->
        <div class="form-container">
            <h2>Usuń Recenzję</h2>
            <form method="post" action="adminPanel.php">
                <label for="reviewId">Recenzja:</label><br>
                <select name="reviewId" class="form-select">
                    <?php
                    $sql = "SELECT id, tresc FROM recenzje";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "' class='form-option'>" . substr($row['tresc'], 0, 50) . "...</option>";
                    }
                    ?>
                </select><br><br>
                <input type="submit" name="deleteReview" value="Usuń recenzję" class="button"><br><br>
            </form>
        </div>

    </div>
</div>
</body>
</html>
<?php

if (!isset($_SESSION['login']) || $_SESSION['rola'] != 'admin') {
    header("Location: logowanie.php");
    exit;
}

// Usuwanie książki
if (isset($_POST['deleteBook'])) {
    $bookId = $_POST['bookId'];
    $sql = "DELETE FROM ksiazki WHERE id = '$bookId'";
    if ($conn->query($sql) === TRUE) {
        echo "Książka została usunięta.";
    } else {
        echo "Błąd: " . $conn->error;
    }
}

// Usuwanie użytkownika
if (isset($_POST['deleteUser'])) {
    $userId = $_POST['userId'];
    $sql = "DELETE FROM uzytkownicy WHERE id = '$userId'";
    if ($conn->query($sql) === TRUE) {
        echo "Użytkownik został usunięty.";
    } else {
        echo "Błąd: " . $conn->error;
    }
}

// Usuwanie recenzji
if (isset($_POST['deleteReview'])) {
    $reviewId = $_POST['reviewId'];
    $sql = "DELETE FROM recenzje WHERE id = '$reviewId'";
    if ($conn->query($sql) === TRUE) {
        echo "Recenzja została usunięta.";
    } else {
        echo "Błąd: " . $conn->error;
    }
}

?>