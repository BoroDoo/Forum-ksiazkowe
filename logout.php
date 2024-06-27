<?php
session_start(); // Rozpoczęcie sesji
session_destroy(); // Zniszczenie sesji
header("Location: index.php"); // Przekierowanie na stronę logowania
exit;
?>
