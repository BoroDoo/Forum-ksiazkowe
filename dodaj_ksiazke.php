<?php
include('config.php'); 
session_start(); 

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['login'])) {
    header("Location: logowanie.php");
    exit;
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Dodaj książkę</title>
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
    <div class="form-container">
        <h1 style="text-align: center;padding-top:80px;">Dodaj książkę</h1>
        <form method="post" enctype="multipart/form-data" action="dodaj_ksiazke.php">
            <label for="tytul" class="form-label">Tytuł:</label><br>
            <input type="text" name="tytul" class="form-input" required><br><br>
            <label for="autor" class="form-label">Autor:</label><br>
            <input type="text" name="autor" class="form-input"><br><br>
            <label for="rok_wydania" class="form-label">Rok wydania:</label><br>
            <input type="number" name="rok_wydania" class="form-input"><br><br>
            <label for="kategoria" class="form-label">Kategoria:</label><br>
            <select name="kategoria" class="form-select"><br>
                <?php
                // Pobranie kategorii z bazy danych
                $sql = "SELECT id, kategoria FROM kategorie";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "' class='form-option'>" . $row['kategoria'] . "</option>";
                }
                ?>
            </select><br><br>
            <label for="zdjecie_okladki" class="form-label"><b style="font-size:22px;">Zdjęcie okładki:</b></label><br><br>
            <input type="file" name="zdjecie_okladki" class="form-input" required><br><br>
            <input type="submit" value="Dodaj książkę" class="button"><br><br>
        </form>
    </div>
</div>
</body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tytul = $_POST['tytul'];
    $autor = $_POST['autor'];
    $rok_wydania = $_POST['rok_wydania'];
    $kategoria = $_POST['kategoria'];
    $zdjecie_okladki = $_FILES['zdjecie_okladki']['name'];
    $idUzytkownika = $_SESSION['id'];

    // Sprawdzenie, czy książka już istnieje w bazie
    $sql = "SELECT COUNT(*) as count FROM ksiazki WHERE tytul = '$tytul'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        echo "Książka już istnieje!";
    } else {
        // Przenosi przeslany plik z lokalizacji tmp na serwerze do likalizacjiw katalogu uploads po nazwa otrzymana w zmiennej $zdjecie_okladki
        move_uploaded_file($_FILES['zdjecie_okladki']['tmp_name'], "uploads/" . $zdjecie_okladki);

        // Dodanie książki do bazy danych
        $sql = "INSERT INTO ksiazki (tytul, autor, rok_wydania, kategoria, zdjecie_okladki, idUzytkownika) VALUES ('$tytul', '$autor', '$rok_wydania', '$kategoria', '$zdjecie_okladki', $idUzytkownika)";
        
        if ($conn->query($sql) === TRUE) {
            echo "Książka dodana pomyślnie!";
        } else {
            echo "Błąd: " . $conn->error;
        }
    }
}
?>
</html>
