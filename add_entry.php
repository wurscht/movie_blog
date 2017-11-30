<?php

if (isset($_POST['titel']) and isset($_POST['inhalt'])) {
    addEntry($userId, $_POST['titel'], $_POST['inhalt']); 
}

?>

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
	<button type="submit">speichern</button>
  </div>
</form>
<form method="get" action="index.php?function=blogs&bid=0">
  <input type="submit" value="abbrechen">
</form>