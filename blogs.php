<?php
  // Alle Blogs bzw. Benutzernamen holen und falls Blog bereits ausgewählt, entsprechenden Namen markieren
  // Hier Code....
  $blogs = getUserNames();
  // Schlaufe über alle Blogs bzw. Benutzer
  // Hier Code....
  if (is_array($blogs) || is_object($blogs)){
    foreach ($blogs as $blog) {
      echo "<div><a href='index.php?function=blogs&bid=$blogId' title='Blog auswählen'><h2>" . $blog['name'] . "</h2></a></div>";
    }
  }

  // Nachfolgend das Beispiel einer Ausgabe in HTML, dieser Teil muss mit einer Schlaufe über alle Blogs und der Ausgabe mit PHP ersetzt werden
?>

