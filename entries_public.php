<?php
  // Alle Blogeinträge holen, die Blog-ID ist in der Variablen $blogId gespeichert (wird in index.php gesetzt)
  // Hier Code... (Schlaufe über alle Einträge dieses Blogs)

  // Nachfolgend das Beispiel einer Ausgabe in HTML, dieser Teil muss mit einer Schlaufe über alle Blog-Beiträge und der Ausgabe mit PHP ersetzt werden

    $entries = getEntries($blogId);
    $blogs = getUserNames();
    if (isset($_GET['eid'])) {
    $eid = $_GET['eid'];
    $shown_entry = getEntry($eid);
    }

    foreach ($blogs as $blog) {
        if ($blog['uid'] == $blogId) {
            echo '<div class="blog">';
            echo "<p class='blog_name'>Einträge von " . $blog['name']."</p>";
            echo '</div>';
        }
    }

    echo '<div class="container">';
    echo '<div class="row">';
    echo '<div class="col col-sm-4">';

    foreach ($entries as $entry){
        if (isset($entry['eid'])){
        echo '<div class="list-group">';
        if (isset($eid) && $eid == $entry['eid']) {
            echo '<a class="list-group-item active" href="index.php?function=entries_public&bid=' . $blogId . '&eid=' . $entry['eid'] . '">';
        } else {
            echo '<a class="list-group-item" href="index.php?function=entries_public&bid=' . $blogId . '&eid=' . $entry['eid'] . '">';
        }
        echo '<div class="entry" value="' . $entry['eid'] .'">';
        echo "<h4>".$entry['title']."</h4>";
        echo "<p>".gmdate("d.m.Y, H:i:s", $entry['datetime'])."</p>";
        echo nl2br(substr($entry['content'], 0, 100). "...");
        echo '</div>';
        echo '</a>';
        echo '</div>';
        }
    }

    echo '</div>';
    echo '<div class="col col-sm-8">';
    
    if (empty($eid)){
        echo "<h2>Hoppla! Keine Blogeinträge gefunden.</h2>";
    }
    else {
        if ($eid){
            echo "<h2>".$shown_entry['title'].", ".gmdate("Y.m.d, H:i:s", $shown_entry['datetime']). "</h2>";
            echo nl2br($shown_entry['content']);
            echo '<form method="post">';
            echo '</form>';
        }
    }

    echo '</div>';
    echo '</div>';
?>