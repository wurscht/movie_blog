<?php
session_start();
require_once("include/functions.php");
require_once("include/functions_db.php");
require_once("include/functions_db_plus.php");
define("DBNAME", "db/blog.db");
// Prüfung ob User eingeloggt ist
$userId = getUserIdFromSession();
// Datenbankverbindung herstellen, diesen Teil nicht ändern!
if (!file_exists(DBNAME)) exit("Die Datenbank 'blog.db' konnte nicht gefunden werden!");
$db = new SQLite3(DBNAME);
setValue("cfg_db", $db);
// Einfacher Dispatcher: Aufruf der Funktionen via index.php?function=xy
if (isset($_GET['function'])) $function = $_GET['function'];
else $function = "login";
// Prüfung, ob bereits ein Blog ausgewählt worden ist
if (isset($_GET['bid'])) $blogId = $_GET['bid'];
else $blogId = 0;
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <!--
      Die nächsten 4 Zeilen sind Bootstrap, falls nicht gewünscht entfernen.
    -->
    <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <script type="text/javascript" src="js/jquery-3.2.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="https://use.fontawesome.com/a951fb7605.js"></script>
    <script src="include/functions.js"></script>
    <link href="css/style.css" rel="stylesheet" />
    <title>Blog-Projekt</title>
</head>

<body>
<!--
  nav, div und ul class="..." ist Bootstrap, falls nicht gewünscht entfernen oder anpassen.
  Die Einteilung der Website in verschiedene Bereiche (Menü-, Content-Bereich, usw.) kann auch selber mit div realisiert werden.
-->
<img class="header" alt="Header Movie Blog" src="images/Filmstrip-Logo.png" />
<nav class="navbar navbar-inverse bg-dark">
    <div class="container">
        <ul class="navbar navbar-dark sticky-top bg-faded">
            <?php
            if ($userId == 0) {
              echo "<li class='navbar-li'><a href='index.php?function=login&bid=$blogId'>Login</a></li>";
              echo "<li class='navbar-li'><a href='index.php?function=blogs_public&bid=$blogId'>Blog wählen</a></li>";
              echo "<li class='navbar-li'><a href='index.php?function=entries_public&bid=$blogId'>Beiträge anzeigen</a></li>";
            } else {
              echo "<li class='navbar-li'><a href='index.php?function=blogs_member&bid=$blogId'>Blog wählen</a></li>";
              echo "<li class='navbar-li'><a href='index.php?function=entries_member&bid=$blogId'>Beiträge anzeigen</a></li>";
              echo "<li class='navbar-li'><a href='index.php?function=add_entry&bid=$blogId'>Beitrag hinzufügen</a></li>";
              echo "<div class='logout-area'>";
              echo "<li class='navbar-li'><a href='index.php?function=import_export'>Import/Export</a></li>";
              echo "<li class='loggedin navbar-li'>Youe are logged in as" . " " . getUserName($userId) . "</li>";
              echo "<button type='button' class='btn btn-primary'><a href='index.php?function=logout&bid=$blogId'>Logout</a></button>";
              echo "</div>";
            }
            ?>
        </ul>
    </div>
</nav>

<br>

<div class="content">
    <?php
    // Für jede Funktion, die mit ?function=xy in der URL übergeben wird, muss eine Datei (in diesem Fall xy.php) existieren.
    // Diese Datei wird aufgerufen, um den Content der Seite aufzubereiten und anzuzeigen.
    if (!file_exists("$function.php")) exit("Die Datei '$function.php' konnte nicht gefunden werden!");
    require_once("$function.php");
    ?>
</div>
<footer class="footer">
    <div class="container">
        <div class="col-md-6 footer-left">Made by Jonas, Mirjam, Hava</div>
        <div class="col-md-6 footer-right">SWAG</div>
    </div>
</footer>
</body>
</html>
<?php
// Datenbankverbindung schliessen, diesen Teil nicht ändern!
$db = getValue('cfg_db');
$db->close();
?>
