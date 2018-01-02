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
    
      addUser($words[0], $words[1], $words[2], 1);
    }
  }

  // Exportiert User in export.csv Datei
  function exportUsers() {
      $data = getUsers();
      $fileName = "export.csv";
      
      header("Content-Disposition: attachment; filename=\"$fileName\"");
      header("Content-Type: text/csv");
      
      $flag = false;
      foreach($data as $row) {
        if(!$flag) {
            // display column names as first row
            echo implode("\t", array_keys($row)) . "\n";
            $flag = true;
        }
        // filter data
        array_walk($row, 'filterData');
        echo implode("\t", array_values($row)) . "\n";

    }
    exit;
  }

  // Filtert String
  function filterData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }
?>
