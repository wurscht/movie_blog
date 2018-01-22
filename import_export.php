<?php
if(isset($_POST['import'])){
    if (importUsers()) {
      echo "<p class='col-sm-6 offset-md-3 alert alert-success'>User wurden aus der Datei import.csv in die Datenbank importiert</p>";
    } else {
      echo "<p class='col-sm-6 offset-md-3 alert alert-danger'>User sind bereits in der Datenbank</p>";
    }
}elseif(isset($_POST['export'])){
    exportUsers();
    echo "<p class='col-sm-6 offset-md-3 alert alert-success'>User wurden aus der Datenbank in die Datei export.csv exportiert</p>";
}
?>

<h2 class='col-sm-6 offset-md-3'>Import/Export</h2>
<p class='col-sm-6 offset-md-3'>Importiere User von der Datei import.csv in die Datenbank oder exportiere bestehende User in die Datei export.csv</p>

<form class="eximport" method="post" action="">
    <input type='submit' class='btn btn-primary col-sm-6 offset-md-3 import' name='import' value='import' /><br>
    <input type='submit' class='btn btn-primary col-sm-6 offset-md-3 export' name='export' value='export' />
</form>