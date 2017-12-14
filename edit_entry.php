<?php

if (isset($_GET['eid'])) {
    $eid = $_GET['eid'];
    $shown_entry = getEntry($eid);
}

if (isset($_POST['titel']) and isset($_POST['inhalt'])) {
    if (strlen($_POST['titel']) >= 3 and strlen($_POST['inhalt']) >= 10) {
        updateEntry($eid, $_POST['titel'], $_POST['inhalt']);
        header("Location: index.php?function=entries_member&bid=" . $blogId);
    } else {
        echo "<p class='error-message'>Der Titel muss mindestens 3 Zeichen, der Inhalt mindestens 10 Zeichen beinhalten</p>";
    }
}

$titel = $shown_entry['title'];
$inhalt = $shown_entry['content'];

?>

<form method="post" action="">
    <label for="titel">Titel</label>
    <div>
        <input type="text" id="titel" class="titel" required="required" name="titel" placeholder="Gib einen Titel ein" value="<?php echo $titel ?>" />
    </div>
    <label for="inhalt">Inhalt</label>
    <div>
        <textarea type="text" id="inhalt" class="inhalt" required="required" name="inhalt" placeholder="Gib einen Inhalt ein" value=""><?php echo $inhalt ?></textarea>
    </div>
    <div>
        <button type="submit" class="btn btn-success">speichern</button>
        <a href="index.php?function=entries_member&bid=<?php echo $blogId; ?>" class="btn btn-danger">abbrechen</a> 
    </div>
</form>