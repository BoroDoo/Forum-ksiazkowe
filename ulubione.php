<?php
include('config.php');
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: logowanie.php");
    exit;
}

$nick = $_SESSION['login'];

// Pobierz id użytkownika na podstawie loginu
$sql = "SELECT id FROM uzytkownicy WHERE login = '$nick'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$idUzytkownika = $row['id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Moje ulubione książki</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php if (isset($_SESSION['login'])):?>
    <div class="welcome" style="text-align:right;">
    <p >Witaj, <?php echo $_SESSION['login']; ?>!</p>
</div>
    <?php endif; ?>
<div class="container">
    <div class="sidebar">
        

    <?php if (isset($_SESSION['login'])&& isset($_SESSION['rola'])&&  ($_SESSION['rola']=='user')): ?>

<a href="index.php" class="button">Strona Główna</a> |<a href="moje_recenzje.php" class="button">Moje recenzje</a> |<a href="ulubione.php" class="button">Moje ulubione książki</a>  | <a href="logout.php" class="button">Wyloguj się</a>
    
<?php elseif (isset($_SESSION['login'])&& isset($_SESSION['rola'])&& ($_SESSION['rola']=='admin')): ?>

<a href="index.php" class="button">Strona Główna</a> | <a href="moje_recenzje.php" class="button">Moje recenzje</a> |<a href="ulubione.php" class="button">Moje ulubione książki</a>  | <a href="adminPanel.php" class="button">Panel Administracyjny</a> | <a href="logout.php" class="button">Wyloguj się</a>

</div>  
<?php else: ?>
    <a href="logowanie.php" class="button">Zaloguj się</a>  
    <a href="rejestracja.php" class="button">Zarejestruj się</a>
<?php endif; ?></div>
    <h1 style="text-align: center;padding-top:80px;">Moje ulubione książki</h1>
    <div class="book-container">
        <?php
        $sql = "SELECT k.id, k.tytul, k.autor, k.rok_wydania, k.zdjecie_okladki 
                FROM ulubione u 
                JOIN ksiazki k ON u.idKsiazki = k.id 
                WHERE u.idUzytkownika = '$idUzytkownika'";
        
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='book'>";
                echo "<a href='ksiazka.php?id=" . $row['id'] . "'>";
                echo "<img src='uploads/" . $row['zdjecie_okladki'] . "' alt='Okładka' class='book-cover'></a><br>";
                echo "<strong>Tytuł:</strong> " . $row['tytul'] . "<br>";
                echo "<strong>Autor:</strong> " . $row['autor'] . "<br>";
                echo "<strong>Rok wydania:</strong> " . $row['rok_wydania'] . "<br>";
                echo "</div>";
            }
        } else {
            echo "Brak ulubionych książek.";
        }
        ?>
    </div>
</div>
</body>
</html>
