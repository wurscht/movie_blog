
<?php
  // Alle Blogeinträge holen, die Blog-ID ist in der Variablen $blogId gespeichert (wird in index.php gesetzt)
  // Hier Code... (Schlaufe über alle Einträge dieses Blogs)

  // Nachfolgend das Beispiel einer Ausgabe in HTML, dieser Teil muss mit einer Schlaufe über alle Blog-Beiträge und der Ausgabe mit PHP ersetzt werden

    $entries = getEntries($blogId);
    $blogs = getUserNames();

    foreach ($blogs as $blog) {
        if ($blog['uid'] == $blogId) {
            echo '<div class="blog">';
            echo "<p>" . $blog['name']."</p>";
            echo '</div>';
        }
    }
    echo '<div class="container">';
    echo '<div class="row">';
    echo '<div class="col col-sm-4">';

    foreach ($entries as $entry){
        if (isset($entry['eid'])){
        echo '<a href="index.php?function=entries_public&bid=' . $blog['uid'] . '&eid=' . $entry['eid'] . '">';
        var_dump($entry['eid']);
        echo '<div class="entry" value="' . $entry['eid'] .'">';
        echo "<h4>".$entry['title'].", ".gmdate("Y.m.d, H:i:s", $entry['datetime'])."</h4>";
        echo nl2br(substr($entry['content'], 0, 100). "...");
        echo '</div>';
        echo '</a>';
        }
    }

    echo '</div>';
    echo '<div class="col col-sm-8">';

    $shown_entry = getEntry($entry['eid']);

    if (empty($shown_entry)){
        echo "<h2>Hoppla! Keine Blogeinträge gefunden.</h2>";
        }
    else
        if (isset($_GET['eid'])){
            echo "<h2>".$shown_entry['title'].", ".gmdate("Y.m.d, H:i:s", $shown_entry['datetime'])."</h2>";
            echo nl2br($shown_entry['content']);
            echo '<form method="post">';
            echo '<button type="submit" name="delete-entry" value=' . $shown_entry['eid'] . 'id="delete-entry">Lösche diesen Beitrag</button>';
            echo '</form>';
        }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['delete-entry'])) {
            deleteEntry($entry['eid']);
            // header("Location: index.php?function=blogs&bid=" . $blogId);
        }
    }

    echo '</div>';
    echo '</div>';
?>

<!--include "connection file";
$query = "SELECT * FROM Blog";
$result = mysqli_query($query);
$num_results = mysqli_num_rows($result);
for($i=0; $i<$num_results; $i++) {
$row = mysqli_fetch_assoc($result);
echo "<div class='blogEntry'><h4>" . $row['title'] . "</h4><h5>" . $row['date'] . "</h5><p>" . $row['text'] . "</p></div>";
} -->