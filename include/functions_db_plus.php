<?php
  /************************************************************************************************
   userExists: Sucht mit der Mailadresse den Benutzer in der Datenbank (damit eine Mailadresse
			   nicht 2x registriert werden kann).
   $email:	   Mailadresse, die der Benutzer eingegeben hat
   Rückgabe:   - True, falls Benutzer vorhanden
			   - False, falls Benutzer nicht vorhanden
   ************************************************************************************************/
  function userExists($email) {
	$db = getValue('cfg_db');
	$email = strtolower($email);
	$result = $db->query("SELECT * FROM user WHERE lower(email)='".$email."'");
	if ($user = $result->fetchArray()) return true;
	else return false;
  }

  /************************************************************************************************
   getUsers: Liefert alle registrierten Benutzer zurück
   Hinweis:	 Diese Funktion kann dazu benutzt werden, alle Benutzer in eine Textdatei zu exportieren
   Rückgabe: 2-dimensionales Array, 
			 - 1. Dimension = Benutzer
			 - 2. Dimension = Attribute des Benutzers
				* User-ID
				* Name, falls vorhanden (NULL-Wert möglich)
				* Mailadresse
				* md5-verschlüsseltes Passwort
   Sortierung:	 1. nach Name und 2. nach Mailadresse
   ************************************************************************************************/
  function getUsers() {
	$alle = [];
	$db = getValue('cfg_db');
	$users = $db->query("SELECT uid, name, email, password FROM user ORDER BY name, email");
	while ($user = $users->fetchArray()) {
	  $alle[] = $user;
	}
	return $alle;
  }

  /************************************************************************************************
   addUser:	  Schreibt einen neuen Benutzer in die Datenbank
   Hinweis:	  Diese Funktion kann dazu benutzt werden, Benutzer aus einer Textdatei zu importieren
   $name:	  Name des Benutzers, kann leer sein (NULL oder leerer String)
   $email:	  Mailadresse des Benutzers, NOT NULL
   $passwort: Verschlüsseltes Passwort des Benutzers, NOT NULL
   Rückgabe: - True bei Erfolg
			 - False bei Fehler
   ************************************************************************************************/
  function addUser($name, $email, $password, $role) {
	$db = getValue('cfg_db');
	$name = SQLite3::escapeString($name);
	$email = SQLite3::escapeString($email);
	$sql = "INSERT INTO user (name, email, password, role) values ('$name', '$email', '$password', $role)";
	return $db->exec($sql);
  }

  /************************************************************************************************
   getEntriesTheme:	Siehe Beschreibung "getEntries"
   Unterschied:		Es werden alle Beiträge eines Blogs zu einem bestimmten Thema zurückgegeben
   $tid:			Thema-ID (damit wird die Abfrage auf das gewünschte Thema eingeschränkt)
   ************************************************************************************************/
  function getEntriesTheme($uid, $tid) {
	$alle = [];
	$db = getValue('cfg_db');
	$entries = $db->query("SELECT eid, datetime, title, content, picture1, picture2, picture3 FROM entry WHERE uid=$uid AND tid=$tid ORDER BY eid DESC");
	while ($entry = $entries->fetchArray()) {
	  $alle[] = $entry;
	}
	return $alle;
  }

  /************************************************************************************************
   getMaxEntry: Liefert die ID des neusten Betrags zurück
   ************************************************************************************************/
  function getMaxEntryId($uid) {
	$db = getValue('cfg_db');
	$result = $db->query("SELECT max(eid) FROM entry WHERE uid=$uid");
	if ($entry = $result->fetchArray()) {
	  return $entry[0];
	} else return 0;
  }

  /************************************************************************************************
   addEntryExtended: Schreibt einen neuen Beitrag in die Datenbank, mit allen Attributen
   $uid:			 User-ID - Jeder Beitrag muss einem Benutzer/Blog zugeordnet werden
   $tid:			 Thema-ID (Falls kein Thema eingefügt wird, dann muss für $tid der String
					 "NULL" übergeben werden)
   $title:			 Der Titel des Beitrags
   $content:		 Der Inhalt des Beitrags
   $picture1:		 Pfad + Dateiname des Bildes 1-3 (Falls kein Bild 1 eingefügt wird, dann muss
					 für $picture1 ein leerer String "" übergeben werden - analog Bilder 2 und 3)
   Rückgabe:		 - True bei Erfolg
					 - False bei Fehler
   ************************************************************************************************/
  function addEntryPlus($uid, $tid, $title, $content, $picture1, $picture2, $picture3) {
	$db = getValue('cfg_db');
	$title = SQLite3::escapeString($title);
	$content = SQLite3::escapeString($content);
	$picture1 = SQLite3::escapeString($picture1);
	$picture2 = SQLite3::escapeString($picture2);
	$picture3 = SQLite3::escapeString($picture3);
	$sql = "INSERT INTO entry (uid, tid, datetime, title, content, picture1, picture2, picture3) values ($uid, $tid, ".time().", '$title', '$content', '$picture1', '$picture2', '$picture3')";
	return $db->exec($sql);
  }

  /************************************************************************************************
   getComments:	Liefert alle Kommentare eines Blog-Beitrags zurück
   $eid:		Entry-ID des gewünschten Beitrags
   Rückgabe:    2-dimensionales Array, 
			    - 1. Dimension = Kommentar
			    - 2. Dimension = Attribute des Kommentars
					* Comment-ID
					* Entry-ID
					* Datum als Unix-Timestamp (muss mit der Funktion date() in ein lesbares
					  Datum umgewandelt werden)
					* Der Inhalt des Kommentars
					* Name des Kommentarerstellers (falls Kommentare nicht den registrierten
													Benutzern zugeordnet werden)
   Sortierung: Nach Entry-ID absteigend (d.h. der aktuellste zuerst)
   ************************************************************************************************/
  function getComments($eid) {
	$alle = [];
	$db = getValue('cfg_db');
	$comments = $db->query("SELECT cid, eid, datetime, content, name FROM comment WHERE eid=$eid ORDER BY cid");
	while ($comment = $comments->fetchArray()) {
	  $alle[] = $comment;
	}
	return $alle;
  }

  /************************************************************************************************
   addCommentNoUser: Schreibt einen neuen Kommentar in die DB
   $eid:			 Entry-ID - ID des Beitrgs, zu dem der Kommentar geschrieben wird
   $content:		 Der Inhalt des Beitrags
   Rückgabe:		 - Bei Erfolg wird die Zufallszahl zurückgegeben
					 - Bei Fehler wird 0 zurückgegeben
   ************************************************************************************************/
  function addComment($eid, $name, $content) {
	$db = getValue('cfg_db');
	$name = SQLite3::escapeString($name);
	$content = SQLite3::escapeString($content);
	$randomnr = mt_rand();
	$sql = "INSERT INTO comment (eid, datetime, name, content) values ($eid, ".time().", '$name', '$content')";
	if ($db->exec($sql)) return $randomnr;
	else return 0;
  }

  /************************************************************************************************
   deleteComment: Löscht einen bestimmten Kommentar zu einem Blog-Beitrag aus der Datenbank
   $cid:		  Comment-ID des zu löschenden Kommentars
   Rückgabe:	  - True bei Erfolg
				  - False bei Fehler
   ************************************************************************************************/
  function deleteComment($cid) {
	$db = getValue('cfg_db');
	// Zuerst wird mit einem SELECT sichergestellt, dass der Datensatz existiert, denn das
	// DELETE-Statement liefert auch TRUE zurück, wenn die Comment-ID nicht vorhanden ist
	$result = $db->query("SELECT * FROM comment WHERE cid=$cid");
	if ($comment = $result->fetchArray()) {
	  $sql = "DELETE FROM comment WHERE cid=$cid";
	  return $db->exec($sql);
	} false;
  }

  /************************************************************************************************
   getTopics:	Liefert alle Themen eines Benutzers zurück
   $uid:		User-ID des gewünschten Benutzers
   Rückgabe:	2-dimensionales Array, 
				- 1. Dimension = Thema
				- 2. Dimension = Attribute des Themas
					* Topic-ID
					* User-ID
					* Name bzw. Bezeichnung des Themas, darf nicht leer sein
					* Beschreibung des Themas, kann leer sein (NULL bzw. leerer String)
   Sortierung:	Nach Name des Themas
   ************************************************************************************************/
  function getTopics($uid) {
	$alle = [];
	$db = getValue('cfg_db');
	$topics = $db->query("SELECT tid, uid, name, description FROM topic WHERE uid=$uid ORDER BY name");
	while ($topic = $topics->fetchArray()) {
	  $alle[] = $topic;
	}
	return $alle;
  }

  /************************************************************************************************
   getTopic: Liefert ein bestimmtes Thema zurück (z.B. zum Editieren)
   $tid:	 Topic-ID des gewünschten Themas
   Rückgabe:   1-dimensionales Array (Attribute des Themas)
					* Topic-ID
					* User-ID
					* Name bzw. Bezeichnung des Themas
					* Beschreibung des Themas
   ************************************************************************************************/
  function getTopic($tid) {
	$db = getValue('cfg_db');
	$result = $db->query("SELECT tid, uid, name, description FROM topic WHERE tid=$tid");
	if ($topic = $result->fetchArray()) {
	  return $topic;
	} else return "";
  }

  /************************************************************************************************
   addTopic:	 Schreibt ein neues Thema in die Datenbank
   $uid:		 User-ID - Jedes Thema muss einem Benutzer/Blog zugeordnet werden
   $name:		 Der Name bzw. die Bezeichnung des Themas
   $description: Die Beschreibung des Themas
   Rückgabe:	 - True bei Erfolg
				 - False bei Fehler
   ************************************************************************************************/
  function addTopic($uid, $name, $description) {
	$db = getValue('cfg_db');
	$name = SQLite3::escapeString($name);
	$description = SQLite3::escapeString($description);
	$sql = "INSERT INTO topic (uid, name, description) values ($uid, '$name', '$description')";
	return $db->exec($sql);
  }

  /************************************************************************************************
   updateTopic:	 Schreibt Änderungen eines bestehenden Themas in die DB
   $tid:		 Topic-ID des zu ändernden Themas
   $name:		 Der Name bzw. die Bezeichnung des Themas
   $description: Die Beschreibung des Themas
   Rückgabe:	 - True bei Erfolg
				 - False bei Fehler
   ************************************************************************************************/
  function updateTopic($tid, $name, $description) {
	$db = getValue('cfg_db');
	// Zuerst wird mit einem SELECT sichergestellt, dass der Datensatz existiert, denn das
	// UPDATE-Statement liefert auch TRUE zurück, wenn die Topic-ID nicht vorhanden ist
	$result = $db->query("SELECT * FROM topic WHERE tid=$tid");
	if ($topic = $result->fetchArray()) {
	  $title = SQLite3::escapeString($title);
	  $content = SQLite3::escapeString($content);
	  $sql = "UPDATE topic set name='$name', description='$description' WHERE tid=$tid";
	  return $db->exec($sql);
	} return false;
  }

  /************************************************************************************************
   deleteEntry:	Löscht einen bestimmten Blog-Beitrag aus der Datenbank
   $eid:		Entry-ID des zu löschenden Beitrags
   Rückgabe:	- True bei Erfolg
				- False bei Fehler
   ************************************************************************************************/
  function deleteTopic($tid) {
	$db = getValue('cfg_db');
	// Zuerst wird mit einem SELECT sichergestellt, dass der Datensatz existiert, denn das
	// DELETE-Statement liefert auch TRUE zurück, wenn die Topic-ID nicht vorhanden ist
	$result = $db->query("SELECT * FROM topic WHERE tid=$tid");
	if ($topic = $result->fetchArray()) {
	  $sql = "DELETE FROM topic WHERE tid=$tid";
	  return $db->exec($sql);
	} false;
  }
?>
