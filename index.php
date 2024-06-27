<?php
include('config.php');
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>E-Biblioteka</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            <a href="index.php" class="button">Strona Główna</a> | <a href="logowanie.php" class="button">Zaloguj się</a> | <a href="rejestracja.php" class="button">Zarejestruj się</a>
             
            
        <?php endif; ?>
        </header>

    
    <div class="form-container">
<br><br><br>
    <form method="get" action="index.php">
        <label for="search"class="form-select">Tytuł:</label>
        <input type="text" name="search" class="form-select"><br><br>
        <label for="kategoria" class="form-select">Kategoria:</label>
        <select name="kategoria" class="form-select">
            <option value="">Wszystkie</option>
            <?php
            $sql = "SELECT id, kategoria FROM kategorie";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['kategoria'] . "</option>";
            }
            ?>
        </select><br>
        <input type="submit" value="Filtruj" class="button"><br><br>
        ------------------------------------------------------<br><br>
        <?php if (isset($_SESSION['login'])):?>
            <a href="dodaj_ksiazke.php" class="button">Dodaj książkę</a>

    <?php endif; ?>
        
    </form>
</div>
</div>
<h1 style="text-align: center;">Lista książek</h1>
    <div class="book-container">
        <?php
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $kategoria = isset($_GET['kategoria']) ? $_GET['kategoria'] : '';

        $sql = "SELECT k.id, k.tytul, k.autor, k.rok_wydania, k.zdjecie_okladki, kat.kategoria
                FROM ksiazki k
                JOIN kategorie kat ON k.kategoria = kat.id
                WHERE k.tytul LIKE '%$search%'";

        if ($kategoria) {
            $sql .= " AND k.kategoria = '$kategoria'";
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='book'>";
                echo "<a href='ksiazka.php?id=" . $row['id'] . "'>";
                echo "<img src='uploads/" . $row['zdjecie_okladki'] . "' alt='Okładka'></a><br>";
                echo "<strong>Tytuł:</strong> " . $row['tytul'] . "<br>";
                echo "<strong>Autor:</strong> " . $row['autor'] . "<br>";
                echo "<strong>Rok wydania:</strong> " . $row['rok_wydania'] . "<br>";
                echo "<strong>Kategoria:</strong> " . $row['kategoria'] . "<br>";
                

                if (isset($_SESSION['login'])) {
                    $nick = $_SESSION['login'];
                    $sqlUlubione = "SELECT idKsiazki FROM ulubione u JOIN uzytkownicy uzy ON u.idUzytkownika = uzy.id WHERE uzy.login = '$nick' AND u.idKsiazki = " . $row['id'];
                    $resultUlubione = $conn->query($sqlUlubione);
                    if ($resultUlubione->num_rows > 0) {
                        echo "<button class='ulubione' data-id='" . $row['id'] . "'>Usuń z ulubionych</button><br><br>";
                    } else {
                        echo "<button class='ulubione' data-id='" . $row['id'] . "'>Dodaj do ulubionych</button><br><br>";
                    }
                }

                echo "</div>";
            }
        } else {
            echo "<p>Brak książek spełniających kryteria.</p>";
        }
        ?>
    </div>

    <script>
    $(document).ready(function() {
    //wykonanie fuknkcji po pełnym załadowaniu dokumentu HTML
        $('.ulubione').on('click', function() {
            //wywołam funkcje po klieknieciu na element klasy "ulubione"
            var bookId = $(this).data('id');
            //pobranie id ksiazki z atrybutu data-id kliknietego przycisku
            var action = $(this).text() === 'Dodaj do ulubionych' ? 'add' : 'remove';
            //jeśli klik w opcje "Dodaj do ulubioncyh" to zapisuje akcje ==add, jeslicos innego to ==remove
            var button = $(this);
            $.ajax({
                //start asynchronicznej komunikacji z serwerem
                url: 'ulubioneScript.php', //adres do ktorego wyslane bedzie zadanie
                type: 'POST',
                data: { idKsiazki: bookId, action: action },
                success: function(response) {
                    if (response == 'added') {
                        
                        button.text('Usuń z ulubionych');
                    } else if (response == 'removed') {
                        
                        button.text('Dodaj do ulubionych');
                    }
                }
            });
        });
    });
    </script>
</body>
</html>
