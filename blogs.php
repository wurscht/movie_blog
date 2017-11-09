<?php
  // Alle Blogs bzw. Benutzernamen holen und falls Blog bereits ausgewählt, entsprechenden Namen markieren
  // Hier Code....
  $blogs = getUserNames();
  // Schlaufe über alle Blogs bzw. Benutzer
  // Hier Code....
  if (is_array($blogs) || is_object($blogs)){
    foreach ($blogs as $blog) {
      if ($blog['uid'] == $blogId) {
        echo "<div><h2>" . $blog['name'] . "</h2></div>";
      } else {
        echo "<div><a href='index.php?function=blogs&bid=" . $blog['uid'] . "' title='Blog auswählen'><h2>" . $blog['name'] . "</h2></a></div>";
      }
    }
  }
  
  

  // Nachfolgend das Beispiel einer Ausgabe in HTML, dieser Teil muss mit einer Schlaufe über alle Blogs und der Ausgabe mit PHP ersetzt werden
?>
	<!-- <div><a href='index.php?function=blogs&bid=4' title='Blog auswählen'><h4>Anna Abegglen</h4></a></div>
	<div><a href='index.php?function=blogs&bid=2' title='Blog auswählen'><h4>Hans Hinterseer</h4></a></div>
	<div><a href='index.php?function=blogs&bid=1' title='Blog auswählen'><h4>Marc Muster</h4></a></div>
	<div><a href='index.php?function=blogs&bid=3' title='Blog auswählen'><h4>Sonja Sauser</h4></a></div> -->


