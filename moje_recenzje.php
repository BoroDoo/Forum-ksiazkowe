<?php
include('config.php'); 
session_start(); 

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['login'])) {
    header("Location: logowanie.php");
    exit;
}

// Pobranie loginu aktualnie zalogowanego użytkownika
$nick = $_SESSION['login'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Moje recenzje</title>
    <link rel="stylesheet" type="text/css" href="style.css">
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
    <div class="form-container2">
        <h1 style="text-align: center;padding-top:80px;">Moje recenzje</h1>
        <?php
        
        $sql = "SELECT r.id, r.ocena, r.tresc, r.data, k.tytul 
                FROM recenzje r 
                JOIN ksiazki k ON r.idKsiazki = k.id 
                WHERE r.nick = '$nick'";
        
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
           
            while ($row = $result->fetch_assoc()) {
                echo "<div class='recenzja-container'>";
                echo "<strong>Tytuł:</strong> " . $row['tytul'] . "<br>";
                echo "<strong>Ocena:</strong> " . $row['ocena'] . "<br>";
                echo "<strong>Treść:</strong> " . $row['tresc'] . "<br>";
                echo "<strong>Data:</strong> " . $row['data'] . "<br>";
                echo "<form method='post' action='moje_recenzje.php'>";
                echo "<input type='hidden' name='recenzja_id' value='" . $row['id'] . "'>";
                echo "<input type='submit' class='button' value='Usuń recenzję'><br><br>";
                echo "</form>";
                echo "</div><br>";
            }
        } else {
            echo "Brak recenzji.";
        }
        ?>
    </div>
</div>
</body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idRecenzji = $_POST['recenzja_id'];

    $sql = "DELETE FROM recenzje WHERE id = '$idRecenzji'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Recenzja usunięta pomyślnie!";
        header("Location: moje_recenzje.php");
    } else {
        echo "Błąd: " . $conn->error;
    }
}
?>
</html>
