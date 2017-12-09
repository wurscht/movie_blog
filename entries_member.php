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

    //delete and edit icon
    if (isset($shown_entry['eid'])) {
      $edit_icon = "<a href='index.php?function=edit_entry&bid=$blogId&eid=$eid'><i class=\"fa fa-pencil-square-o \"></i></a>";
      $delete_icon = "<button type=\"submit\" name=\"delete-entry\" value='" . $shown_entry['eid'] . "'id=\"delete-entry\"><i class=\"fa  fa-trash-o \"></i></button>";
    }

    foreach ($blogs as $blog) {
        if ($blog['uid'] == $blogId) {
            echo '<div class="blog">';
            echo "<p class='blog_name'>Entries of " . $blog['name']."</p>";
            echo '</div>';
        }
    }
    echo '<div class="container">';
    echo '<div class="row">';
    echo '<div class="col col-sm-4">';

    foreach ($entries as $entry){
        if (isset($entry['eid'])){
        echo '<a href="index.php?function=entries_member&bid=' . $blogId . '&eid=' . $entry['eid'] . '">';
        echo '<div class="entry" value="' . $entry['eid'] .'">';
        echo "<h4>".$entry['title'].", ".gmdate("Y.m.d, H:i:s", $entry['datetime'])."</h4>";
        echo nl2br(substr($entry['content'], 0, 100). "...");
        echo '</div>';
        echo '</a>';
        }
    }

    echo '</div>';
    echo '<div class="col col-sm-8">';
    
    

    if (empty($eid)){
        echo "<h2>Hoppla! Keine Blogeinträge gefunden.</h2>";
        }
    else {
        if ($eid){
            echo "<h2>".$shown_entry['title'].", ".gmdate("Y.m.d, H:i:s", $shown_entry['datetime']). $edit_icon . $delete_icon . "</h2>";
            echo nl2br($shown_entry['content']);
            echo '<form method="post">';
            echo '<button type="submit" name="delete-entry" value=' . $shown_entry['eid'] . 'id="delete-entry">Lösche diesen Beitrag</button>';
            echo '</form>';
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['delete-entry'])) {
            deleteEntry($shown_entry['eid']);
            header("Location: index.php?function=entries_public&bid=" . $blogId);
        }
    }

    echo '</div>';
    echo '</div>';
?>