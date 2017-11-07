<?php
  /************************************************************************************************
   getUserIdFromDb:	Sucht mit der Mailadresse und dem Passwort den Benutzer in der Datenbank
					(Authentifizierung mit den Login-Daten).
   $email:			Mailadresse, die der Benutzer eingegeben hat
   $password:		Passwort, das der Benutzer eingegeben hat
   md5():			Verschlüsselt das Passwort mit md5()
   Rückgabe:		- User-ID uid, falls erfolgreich
					- 0, falls Benutzer nicht gefunden
   ************************************************************************************************/
  function getUserIdFromDb($email, $password) {
	$db = getValue('cfg_db');
	$email = strtolower($email);
	$result = $db->query("SELECT uid FROM user WHERE lower(email)='".$email."' AND password='".md5($password)."'");
	if ($user = $result->fetchArray()) return $user[0];
	else return 0;
  }

  /************************************************************************************************
   getUserName:	Liefert den Namen der übergebenen User-ID zurück
   Hinweis:		Ist nützlich, um den Benutzer z.B. mit "Willkommen 'Marc Muster'" zu begrüssen
   $uid:		User-ID des gewünschten Benutzers
   Rückgabe:	- Name, falls vorhanden (NULL-Wert möglich)
				- Mailadresse, falls Name = NULL
				- Leerer String, falls Benutzer-ID nicht vorhanden
   ************************************************************************************************/
  function getUserName($uid) {
	$db = getValue('cfg_db');
	$result = $db->query("SELECT name, email FROM user WHERE uid=".$uid);
	if ($user = $result->fetchArray()) {
	  if (strlen($user[0]) > 0) return $user[0];
	  else return $user[1];
	} else return "";
  }

  /************************************************************************************************
   getUserNames: Liefert die Namen aller registrierter Benutzer zurück
   Hinweis:		 Jeder Benutzer hat einen Blog, der auf seinen Namen lautet. Mit der Liste können
				 demzufolge alle Blogs angezeigt werden. Die Funktion könnte auch getBlogs() heissen.
   Rückgabe:	 2-dimensionales Array, 
				 - 1. Dimension = Benutzer
				 - 2. Dimension = Attribute des Benutzers
   Sortierung:	 1. nach Name und 2. nach Mailadresse
   ************************************************************************************************/
  function getUserNames() {
	$alle = [];
	$db = getValue('cfg_db');
	$users = $db->query("SELECT uid, name, email FROM user ORDER BY name, email");
	while ($user = $users->fetchArray()) {
	  $alle[] = $user;
	}
	return $alle;
  }

  /************************************************************************************************
   getEntries: Liefert alle Beiträge eines Benutzers/Blogs zurück
   Hinweis:	   Möglichkeit 1: Es werden in einem ersten Schritt nur die Titel der Beiträge angezeigt. In diesem
			   Fall sind nur Entry-ID, Datum und Titel relevant.
			   Möglichkeit 2. Es werden gleich alle Blog-Beiträge untereinander angezeigt.
   $uid:	   User-ID des gewünschten Benutzers
   Rückgabe:   2-dimensionales Array, 
			   - 1. Dimension = Blog-Beitrag
			   - 2. Dimension = Attribute des Beitrags
					* Entry-ID
					* Datum als Unix-Timestamp (muss mit der Funktion date() in ein lesbares
					  Datum umgewandelt werden)
					* Titel
					* Inhalt (der eigentliche Beitrag)
					* Pfad und Dateiname der Bilder 1-3
   Sortierung: Nach Entry-ID absteigend (d.h. der aktuellste zuerst)
   ************************************************************************************************/
  function getEntries($uid) {
	$alle = [];
	$db = getValue('cfg_db');
	$entries = $db->query("SELECT eid, datetime, title, content, picture1, picture2, picture3 FROM entry WHERE uid=$uid ORDER BY eid DESC");
	while ($entry = $entries->fetchArray()) {
	  $alle[] = $entry;
	}
	return $alle;
  }

  /************************************************************************************************
   getEntry: Liefert einen bestimmten Beitrag zurück
   Hinweis:	 Falls in einem ersten Schritt nur die Titel der Beiträge angezeigt werden, kann mit
			 dieser Funktion ein einzelner Beitrag zur Anzeige zurückgeliefert werden.
   $eid:	 Entry-ID eines Blog-Beitrags
   Rückgabe:   1-dimensionales Array (Attribute des Beitrags)
					* Entry-ID
					* Datum als Unix-Timestamp (muss mit der Funktion date() in ein lesbares
					  Datum umgewandelt werden)
					* Titel
					* Inhalt (der eigentliche Beitrag)
					* Pfad und Dateiname der Bilder 1-3
   ************************************************************************************************/
  function getEntry($eid) {
	$db = getValue('cfg_db');
	$result = $db->query("SELECT eid, datetime, title, content, picture1, picture2, picture3 FROM entry WHERE eid=$eid");
	if ($entry = $result->fetchArray()) {
	  return $entry;
	} else return "";
  }

  /************************************************************************************************
   addEntry: Schreibt einen neuen Beitrag in die Datenbank, mit den min. erforderlichen Attributen
   $uid:	 User-ID - Jeder Beitrag muss einem Benutzer/Blog zugeordnet werden
   $title:	 Der Titel des Beitrags
   $content: Der Inhalt des Beitrags
   time():	 Erstellt den aktuellen UNIX-Timestamp
   Rückgabe: - True bei Erfolg
			 - False bei Fehler
   ************************************************************************************************/
  function addEntry($uid, $title, $content) {
	$db = getValue('cfg_db');
	$title = SQLite3::escapeString($title);
	$content = SQLite3::escapeString($content);
	$sql = "INSERT INTO entry (uid, datetime, title, content) values ($uid, ".time().", '$title', '$content')";
	return $db->exec($sql);
  }

  /************************************************************************************************
   updateEntry:	Schreibt Änderungen eines bestehenden Blog-Beitrags in die DB - minimale Variante
   $eid:		Entry-ID des zu ändernden Beitrags
   $title:		Der Titel des Beitrags
   $content:	Der Inhalt des Beitrags
   Rückgabe:	- True bei Erfolg
				- False bei Fehler
   ************************************************************************************************/
  function updateEntry($eid, $title, $content) {
	$db = getValue('cfg_db');
	// Zuerst wird mit einem SELECT sichergestellt, dass der Datensatz existiert, denn das
	// UPDATE-Statement liefert auch TRUE zurück, wenn die Entry-ID nicht vorhanden ist
	$result = $db->query("SELECT * FROM entry WHERE eid=$eid");
	if ($entry = $result->fetchArray()) {
	  $title = SQLite3::escapeString($title);
	  $content = SQLite3::escapeString($content);
	  $sql = "UPDATE entry set title='$title', content='$content' WHERE eid=$eid";
	  return $db->exec($sql);
	} return false;
  }

  /************************************************************************************************
   deleteEntry:	Löscht einen bestimmten Blog-Beitrag aus der Datenbank
   $eid:		Entry-ID des zu löschenden Beitrags
   Rückgabe:	- True bei Erfolg
				- False bei Fehler
   ************************************************************************************************/
  function deleteEntry($eid) {
	$db = getValue('cfg_db');
	// Zuerst wird mit einem SELECT sichergestellt, dass der Datensatz existiert, denn das
	// DELETE-Statement liefert auch TRUE zurück, wenn die Entry-ID nicht vorhanden ist
	$result = $db->query("SELECT * FROM entry WHERE eid=$eid");
	if ($entry = $result->fetchArray()) {
	  $sql = "DELETE FROM entry WHERE eid=$eid";
	  return $db->exec($sql);
	} false;
  }
?>