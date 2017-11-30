<?php

$entries = getEntries($blogId);
$blogs = getUserNames();

foreach ($blogs as $blog) {
    if ($blog['uid'] == $blogId) {
        //echo "<p>" . $blog['name']."</p>";
    }
}

foreach ($entries as $entry){
    $titel = $entry['title'];
    $inhlat = $entry['content'];
}

?>

<form method="post" action="">
    <label for="titel">Titel</label>
    <div>
        <input type="text" id="titel" class="titel" required="required" name="titel" placeholder="Gib einen Titel ein" value="<?php echo $titel ?>" />
    </div>
    <label for="inhalt">Inhalt</label>
    <div>
        <textarea type="text" id="inhalt" class="inhalt" required="required" name="inhalt" placeholder="Gib einen Inhalt ein" value=""><?php echo $inhlat ?></textarea>
    </div>
    <div>
        <button type="submit">speichern</button>
        <form action="index.php?function=blogs&bid=0">
            <input type="submit" value="abbrechen">
        </form>
    </div>
</form>