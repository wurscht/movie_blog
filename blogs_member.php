<?php

  $blogs = getUserNames();

  if (is_array($blogs) || is_object($blogs)){
    foreach ($blogs as $blog) {
      if ($blog['uid'] == $userId) {
        $blogId = $userId;
        header("Location: index.php?function=entries_member&bid=" . $blogId);
      }
    }
  }
?>