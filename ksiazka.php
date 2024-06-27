<?php
include('config.php');
session_start();


?>

<!DOCTYPE html>
<html>
<head>
    <title>Szczegóły książki</title>
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
            <a href="index.php" class="button">Strona Główna</a> | <a href="logowanie.php" class="button">Zaloguj się</a> | <a href="rejestracja.php" class="button">Zarejestruj się</a>
            
        <?php endif; ?></div>  
</div>
<div class="container2"> 
    <div class="page-content">
        <div class="page-book-details">
            <?php
            $id = isset($_GET['id']) ? $_GET['id'] : '';

            $sql = "SELECT k.id, k.tytul, k.autor, k.rok_wydania, k.zdjecie_okladki, kat.kategoria, kat.id as idKat, u.login as dodal
                    FROM ksiazki k
                    JOIN kategorie kat ON k.kategoria = kat.id
                    JOIN uzytkownicy u ON k.idUzytkownika = u.id
                    WHERE k.id = '$id'";
            $result = $conn->query($sql) or die($conn->error);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                echo "<h1>" . $row['tytul'] . "</h1>";
                echo "<img src='uploads/" . $row['zdjecie_okladki'] . "' alt='Okładka' class='book-cover-large'><br>";
                echo "<p><strong>Autor:</strong> " . $row['autor'] . "</p>";
                echo "<p><strong>Rok wydania:</strong> " . $row['rok_wydania'] . "</p>";
                echo "<p><strong>Kategoria:</strong> <a href='index.php?search=&kategoria=" . $row['idKat'] . "'>" . $row['kategoria'] . "</a></p>";
                echo "<p><strong>Książkę dodał:</strong> " . $row['dodal'] . "</p>"; 
                 if (isset($_SESSION['login'])):{
                    echo "<a href='dodaj_recenzje.php?id=".$row['id']."' class='button'>Dodaj recenzję</a>";
                 }
               endif; 
                
                    // Przycisk do usunięcia książki widoczny tylko dla admina
                if (isset($_SESSION['rola']) && $_SESSION['rola'] == 'admin') {
                echo "<form method='POST' action='usun_ksiazke.php' style='display:inline;'>";
                echo "<input type='hidden' name='id' value='".$row['id']."'>";
                echo "<input type='submit' value='Usuń książkę' class='button' onclick='return confirm(\"Czy na pewno chcesz usunąć tę książkę?\");'>";
                echo "</form>";
    }
            } else {
                echo "<p>Nie znaleziono książki.</p>";
            }
            ?>
        </div>
        <div class="page-reviews">
            <h4>Recenzje</h4>
            <?php
                $sql = "SELECT r.id, r.nick, r.ocena, r.tresc, r.data
                FROM recenzje r
                WHERE r.idKsiazki = '$id'";

                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                echo "<div class='review'>";
                echo "<p><strong>Autor recenzji:</strong> " . $row['nick'] . "</p>";
                echo "<p><strong>Ocena:</strong> " . $row['ocena'] . "</p>";
                echo "<p><strong>Treść:</strong> " . $row['tresc'] . "</p>";
                echo "<p><strong>Data:</strong> " . $row['data'] . "</p>";

                // Przycisk do usunięcia recenzji widoczny dla admina i autora recenzji
                if ((isset($_SESSION['rola']) && $_SESSION['rola'] == 'admin') || (isset($_SESSION['login']) && $_SESSION['login'] == $row['nick'])) {
                    echo "<form method='POST' action='usun_recenzje.php' style='display:inline;'>";
                    echo "<input type='hidden' name='recenzja_id' value='".$row['id']."'>";
                    echo "<input type='hidden' name='idKsiazki' value='".$id."'>";
                    echo "<input type='submit' value='Usuń recenzję' class='button' onclick='return confirm(\"Czy na pewno chcesz usunąć tę recenzję?\");'>";
                    echo "</form>";
                }

                echo "</div>";
                }
                } else {
                echo "<p>Brak recenzji dla tej książki.</p>";
                }
            ?>
        </div>
    </div>
</div>
</body>
</html>
