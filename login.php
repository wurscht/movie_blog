<?php
  $meldung = "";
  $email = "";
  $passwort = "";

// Prüft, meldet den Benutzer an
    if (isset($_POST['email']) and isset($_POST['passwort'])) {
        if (getUserIdFromDb($_POST['email'], $_POST['passwort']) == 0) {
            echo "Fehler";
        } elseif (getUserIdFromDb($_POST['email'], $_POST['passwort']) > 0) {
            $_SESSION['userId'] = getUserIdFromDb($_POST['email'], $_POST['passwort']);
            header ("Location: index.php?function=blogs&bid=0");
        }
    }
  // $_SERVER['PHP_SELF'] = login.php in diesem Fall (also die PHP-Datei, die gerade ausgeführt wird).
  // Mit andern Worten: Nach Senden des Formulars wird wieder login.php aufgerufen.
  // Die Funktionen zur Überprüfung, ob die Login-Daten gültig sind, muss also hier oben im PHP-Teil stehen!
  // Wenn Login-Daten korrekt sind:
  // Session-Variable mit Benutzer-ID setzen und Wechsel in Memberbereich
  // $_SESSION['uid'] = $uid;	
  // header('Location: index.php?function=entries_member');
  // Wenn Formular gesendet worden ist, die Login-Daten aber nicht korrekt sind:
  // Unten auf der Seite Anzeige der Fehlermeldung.

?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']."?function=login"; ?>">
  <label for="email">Benutzername</label>
  <div>
	<input type="email" id="email" name="email" placeholder="E-Mail" value="" />
  </div>
  <label for="passwort">Passwort</label>
  <div>
	<input type="password" id="passwort" name="passwort" placeholder="Passwort" value="" />
  </div>
  <div>
	<button type="submit">senden</button>
  </div>
</form>
