<?php
  // Schreibt einen Wert in den globalen Array
  function setValue($key, $value) {
    global $params;
    $params[$key] = $value;
  }

  // Holt einen Wert aus dem globalen Array
  function getValue($key) {
    global $params;
	if (isset($params[$key])) return $params[$key];
	else return "";
  }

  // Prüft, ob der Benutzer angemeldet ist
  function getUserIdFromSession() {
	if (isset($_SESSION['userId'])) return $_SESSION['userId'];
	else return 0;
  }

  // Importiert User aus import.csv Datei
  function importUsers() {
    $importFile = fopen("exchange/import.csv", "r");
    fgets($importFile);

    while($line = fgets($importFile)){
      $words = explode (";", $line);
      
      if (userExists($words[1])) {
        return false;
      } else {
        addUser($words[0], $words[1], $words[2], 1);
        return true;
      }
    }
  }

  // Exportiert User in export.csv Datei
  function exportUsers() {
      $exportfile = fopen("exchange/export.csv", "w");
      $data = getUsers();
      foreach ($data as $key => $value){
          $user = $value;
          foreach ($user as $key => $value){
              $string = $value;
              fwrite($exportfile, $string);
              fclose($exportfile);
          }
      }
  }
?>