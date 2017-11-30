<?php

$entries = getEntries($blogId);
$blogs = getUserNames();

foreach ($entries as $entry){
    $titel = $entry['title'];
    $inhlat = $entry['content'];
}

if (isset($_POST['titel']) and isset($_POST['inhalt'])) {
    updateEntry($userId, $_POST['titel'], $_POST['inhalt']);
    header ("Location: index.php?function=entries_public");
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