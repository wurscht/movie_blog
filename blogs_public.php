<?php

  $blogs = getUserNames();

  if (is_array($blogs) || is_object($blogs)){
    foreach ($blogs as $blog) {
      if ($blog['uid'] == $blogId) {
        echo "<div><h2>" . $blog['name'] . "</h2></div>";
      } else {
        echo "<div><a href='index.php?function=blogs_public&bid=" . $blog['uid'] . "' title='Blog auswählen'><h2>" . $blog['name'] . "</h2></a></div>";
      }
    }
  }
?>