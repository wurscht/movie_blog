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
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="https://use.fontawesome.com/a951fb7605.js"></script>
    <script src="include/functions.js"></script>
    <title>Blog-Projekt</title>
</head>

<body>
<!--
  nav, div und ul class="..." ist Bootstrap, falls nicht gewünscht entfernen oder anpassen.
  Die Einteilung der Website in verschiedene Bereiche (Menü-, Content-Bereich, usw.) kann auch selber mit div realisiert werden.
-->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand"><?php echo "Movie Blog"; ?></a>
        </div>
        <ul class="nav navbar-nav">
            <?php
            if ($userId == 0) {
              echo "<li><a href='index.php?function=login&bid=$blogId'>Login</a></li>";
              echo "<li><a href='index.php?function=blogs&bid=$blogId'>Blog wählen</a></li>";
              echo "<li><a href='index.php?function=entries_public&bid=$blogId'>Beiträge anzeigen</a></li>";
            } else {
              echo "<li><a href='index.php?function=blogs&bid=$blogId'>Blog wählen</a></li>";
              echo "<li><a href='index.php?function=entries_public&bid=$blogId'>Beiträge anzeigen</a></li>";
              echo "<li><a href='index.php?function=logout&bid=$blogId'>Logout</a></li>";
              echo "<li>Youe are logged in as" . " " . getUserName($userId) . "</li>";
            }
            ?>
        </ul>
    </div>
    <!--<img class="header_fw" src="images/header.jpg" alt="header">-->
</nav>
<div class="carousel slide" id="carousel-662871">
    <ol class="carousel-indicators">
        <li class="active" data-slide-to="0" data-target="#carousel-662871">
        </li>
        <li data-slide-to="1" data-target="#carousel-662871">
        </li>
        <li data-slide-to="2" data-target="#carousel-662871">
        </li>
    </ol>
    <div class="carousel-inner">
        <div class="item active">
            <img alt="Carousel Bootstrap First" src="http://lorempixel.com/1200/300" />
            <div class="carousel-caption">
                <h4>
                    First Thumbnail label
                </h4>
            </div>
        </div>
        <div class="item">
            <img alt="Carousel Bootstrap Second" src="http://lorempixel.com/1200/300" />
            <div class="carousel-caption">
                <h4>
                    Second Thumbnail label
                </h4>
            </div>
        </div>
        <div class="item">
            <img alt="Carousel Bootstrap Third" src="http://lorempixel.com/1200/300" />
            <div class="carousel-caption">
                <h4>
                    Third Thumbnail label
                </h4>
            </div>
        </div>
    </div>
    <a class="left carousel-control" href="#carousel-662871" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a> <a class="right carousel-control" href="#carousel-662871" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
</div>
<div class="container" style="margin-top:80px">
    <?php
    // Für jede Funktion, die mit ?function=xy in der URL übergeben wird, muss eine Datei (in diesem Fall xy.php) existieren.
    // Diese Datei wird aufgerufen, um den Content der Seite aufzubereiten und anzuzeigen.
    if (!file_exists("$function.php")) exit("Die Datei '$function.php' konnte nicht gefunden werden!");
    require_once("$function.php");
    ?>
</div>
</body>
</html>
<?php
// Datenbankverbindung schliessen, diesen Teil nicht ändern!
$db = getValue('cfg_db');
$db->close();
?>
