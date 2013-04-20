<?php
if($_SERVER['REQUEST_METHOD']=='POST'
&& $_POST['submit']=='Save Entry'
&& !empty($_POST['page'])
&& !empty($_POST['title'])
&& !empty($_POST['entry']))
	
//Start of the first loop inside php
{
		
		// Include database credentials and connect to the database
		include_once 'db.inc.php';
		$db = new PDO(DB_INFO, DB_USER, DB_PASS);
		// Continue processing data...

        
		//Initialising the variables: Referred from stack overflow : to be deleted later
		// Guess what? It workded after testing
		$title=$_POST['title'];
		$entry=$_POST['entry'];
		
		// Save the entry into the database
        $sql = "INSERT INTO entries (page, title, entry)
        VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute(
        array($_POST['page'],$_POST['title'],$_POST['entry'])
        );
		$stmt->closeCursor();
		
        // Sanitize the page information for use in the success URL
        $page = htmlentities(strip_tags($_POST['page']));
        
		// Get the ID of the entry we just saved
		$id_obj = $db->query("SELECT LAST_INSERT_ID()");
		$id = $id_obj->fetch();
		$id_obj->closeCursor();
		
		
		
		// Send the user to the new entry
		header('Location: /simple_blog/?page='.$page.'&id='.$id[0]);
		exit;
		
// Continue processing information . . .
}
//End of first loop inside php

// If both conditions aren't met, sends the user back to the main page
else
{
header('Location: /simple_blog/admin.php');
exit;
}

?>
