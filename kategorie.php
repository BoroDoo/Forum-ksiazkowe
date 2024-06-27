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
        <h1>Zarzadzanie kategoriami</h1>



        <!-- Add kat -->
        <div class="form-container">
            <h2>Dodaj Kategorię</h2>
            <form method="post" action="kategorie.php">
                <label for="category">Kategoria:</label><br>
                <input type="text" name="category" class="form-input" required><br><br>
                <label for="category">Opis:</label><br>
                <input type="text" name="opis" class="form-input" required><br><br>
                <input type="submit" name="addCategory" value="Dodaj kategorię" class="button"><br><br>
            </form>
        </div>

        <!-- Delete kat -->
        <div class="form-container">
            <h2>Usuń Kategorię</h2>
            <form method="post" action="kategorie.php">
                <label for="categoryId">Kategoria:</label><br>
                <select name="categoryId" class="form-select">
                    <?php
                    $sql = "SELECT id, kategoria FROM kategorie";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "' class='form-option'>" . $row['kategoria'] . "</option>";
                    }
                    ?>
                </select><br><br>
                <input type="submit" name="deleteCategory" value="Usuń kategorię" class="button"><br><br>
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


// Dodawanie kategorii
if (isset($_POST['addCategory'])) {
    $category = $_POST['category'];
    $opis = $_POST['opis'];
    $sql = "INSERT INTO kategorie (kategoria, opis) VALUES ('$category', '$opis')";
    if ($conn->query($sql) === TRUE) {
        echo "Kategoria została dodana.";
        header("Location: kategorie.php");
        
    } else {
        echo "Błąd: " . $conn->error;
        

    }
    
}

// Usuwanie kategorii
if (isset($_POST['deleteCategory'])) {
    $categoryId = $_POST['categoryId'];
    $sql = "DELETE FROM kategorie WHERE id = '$categoryId'";
    if ($conn->query($sql) === TRUE) {
        echo "Kategoria została usunięta.";
        header("Location: kategorie.php");
       
    } else {
        echo "Nie możesz usunąć kategorii, jeśli istnieją filmy do niej przypisane";
    }
    
}

?>