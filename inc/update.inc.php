<?php

// Include the functions so you can create a URL
include_once 'function.inc.php';


if($_SERVER['REQUEST_METHOD']=='POST'
&& $_POST['submit']=='Save Entry'
&& !empty($_POST['page'])
&& !empty($_POST['title'])
&& !empty($_POST['entry']))
	
//Start of the first loop inside php
{
		
		// Create a URL to save in the database
		$url = makeUrl($_POST['title']);
		
		// Include database credentials and connect to the database
		include_once 'db.inc.php';
	
		$db = new PDO(DB_INFO, DB_USER, DB_PASS);
		// Continue processing data...

        
		//Initialising the variables: Referred from stack overflow : to be deleted later
		// Guess what? It workded after testing
		$title=$_POST['title'];
		$entry=$_POST['entry'];
        
        // Edit an existing entry
        if(!empty($_POST['id']))
        {
            $sql = "UPDATE entries
            SET title=?, entry=?, url=?
            WHERE id=?
            LIMIT 1";
            $stmt = $db->prepare($sql);
            $stmt->execute(
            array(
            $_POST['title'],
            $_POST['entry'],
            $url,
            $_POST['id']
            )
            );
        $stmt->closeCursor();
        }
        
        // Create a new entry
        else
		{
		  
		// Save the entry into the database
		$sql = "INSERT INTO entries (page, title, entry, url)
		VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
		$stmt->execute(
		array($_POST['page'], $_POST['title'], $_POST['entry'], $url)
		);
		$stmt->closeCursor();
		
        }
        
        
        // Sanitize the page information for use in the success URL
        $page = htmlentities(strip_tags($_POST['page']));
        
/*		// Get the ID of the entry we just saved
		$id_obj = $db->query("SELECT LAST_INSERT_ID()");
		$id = $id_obj->fetch();
		$id_obj->closeCursor();
		//Commrnting out the above code after referring the pdf coded lines in the book
*/
		
		
		// Send the user to the new entry
	//	header('Location: /simple_blog/?page='.$page.'&id='.$id[0]);
		header('Location: /simple_blog/'.$page.'/'.$url);
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
