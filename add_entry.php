<?php

if (isset($_POST['titel']) and isset($_POST['inhalt'])) {
    if (strlen($_POST['titel']) >= 3 and strlen($_POST['inhalt']) >= 10) {
        addEntry($blogId, $_POST['titel'], $_POST['inhalt']);
        header("Location: index.php?function=entries_member&bid=" . $blogId);
    } else {
        echo "<div class='error-message col-md-6 offset-md-3'>";
        echo "<p class='alert alert-danger'>Der Titel muss mindestens 3 Zeichen, der Inhalt 10 Zeichen beinhalten</p>";
        echo "</div>";
    }
}

?>

<div class="col-sm-6 offset-md-3">
  <form method="post" action="">
    <label for="titel">Titel</label>
    <div>
      <input type="text" id="titel" class="titel" name="titel" placeholder="Gib einen Titel ein" value="" />
    </div>
    <label for="inhalt">Inhalt</label>
    <div>
      <textarea type="text" id="inhalt" class="inhalt" name="inhalt" placeholder="Gib einen Inhalt ein" value=""></textarea>
    </div>
    <div>
      <button type="submit" class="btn btn-success">speichern</button>
      <a href="index.php?function=blogs_member&bid=<?php echo $blogId; ?>" class="btn btn-danger">abbrechen</a>  
    </div>
  </form>
</div>
