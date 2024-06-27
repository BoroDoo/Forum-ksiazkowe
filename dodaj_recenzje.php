<?php
include('config.php'); // Dołączenie pliku konfiguracyjnego z połączeniem do bazy danych
session_start(); // Rozpoczęcie sesji

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['login'])) {
    header("Location: logowanie.php");
    exit;
}

$idKsiazki=isset($_GET['id']) ? $_GET['id'] : '';
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj recenzję</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php if (isset($_SESSION['login'])):?>
    <div class="welcome" style="text-align:right;">
    <p >Witaj, <?php echo $_SESSION['login']; ?>!</p>
</div>
    <?php endif; ?>
<div class="container">
    <header class="sidebar">

    <?php if (isset($_SESSION['login'])&& isset($_SESSION['rola'])&&  ($_SESSION['rola']=='user')): ?>

<a href="index.php" class="button">Strona Główna</a> |<a href="moje_recenzje.php" class="button">Moje recenzje</a> |<a href="ulubione.php" class="button">Moje ulubione książki</a>  | <a href="logout.php" class="button">Wyloguj się</a>
    
<?php elseif (isset($_SESSION['login'])&& isset($_SESSION['rola'])&& ($_SESSION['rola']=='admin')): ?>

<a href="index.php" class="button">Strona Główna</a> | <a href="moje_recenzje.php" class="button">Moje recenzje</a> |<a href="ulubione.php" class="button">Moje ulubione książki</a>  | <a href="adminPanel.php" class="button">Panel Administracyjny</a> | <a href="logout.php" class="button">Wyloguj się</a>

</div>  
<?php else: ?>
    <a href="logowanie.php" class="button">Zaloguj się</a>  
    <a href="rejestracja.php" class="button">Zarejestruj się</a>
<?php endif; ?></header>
    <div class="container">
        <div class="form-container">
            <h1 style="text-align: center;padding-top:80px;">Dodawanie recenzję</h1>
             <form method="post" action="dodaj_recenzje.php?id=<?php echo $idKsiazki; ?>">

                <label for="ocena" class="form-label">Ocena:</label><br>
                <input type="number" name="ocena" min="1" max="10" class="form-input" required><br><br>
                <label for="tresc" class="form-label">Treść:</label><br>
                <textarea name="tresc" class="form-textarea" required></textarea><br><br>
                <input type="submit" value="Dodaj recenzję" class="button"><br><br>
            </form>
        </div>
    </div>
                </div>
</body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nick = $_SESSION['login']; // Używanie loginu aktualnie zalogowanego użytkownika jako nick
    $ocena = $_POST['ocena'];
    $tresc = $_POST['tresc'];
    $data = date('Y-m-d'); // Ustawienie bieżącej daty

    // Dodanie recenzji do bazy danych
    $sql = "INSERT INTO recenzje (idKsiazki, nick, ocena, tresc, data) VALUES ('$idKsiazki', '$nick', '$ocena', '$tresc', '$data')";
    
    if ($conn->query($sql) === TRUE) {
        
        echo "<div style='text-align: center;'>Recenzja dodana pomyślnie!</div>";
        header("Location: ksiazka.php?id=".$idKsiazki);
    } else {
        echo "<div style='text-align: center;'>Błąd: " . $conn->error . "</div>";
    }
}

?>
</html>
