<?php
function retrieveEntries($db, $id=NULL)
{
	
	// Get entries from database
	/* 	 * If an entry ID was supplied, load the associated entry 	*/
	
	if(isset($id))
	{
		// Load specified entry
		
		$sql = "SELECT title, entry
		FROM entries
		WHERE id=?
		LIMIT 1";
		$stmt = $db->prepare($sql);
		$stmt->execute(array($_GET['id']));
		// Save the returned entry array
		$e = $stmt->fetch();
		// Set the fulldisp flag for a single entry
		$fulldisp = 1;
	}
	//End of if statement
	
	/* * If no entry ID was supplied, load all entry titles */
		else
	{
			// Load all entry titles
			$sql = "SELECT id, title
			FROM entries
			ORDER BY created DESC";
			// Loop through returned results and store as an array
				foreach($db->query($sql) as $row) 
				{
					$e[] = array
					(
							'id' => $row['id'],
							'title' => $row['title']
					);
				}
				
				// Set the fulldisp flag for multiple entries
				$fulldisp = 0;
				
				/*
				 * If no entries were returned, display a default
				* message and set the fulldisp flag to display a
				* single entry
				*/
				
						if(!is_array($e))
						{
							$fulldisp = 1;
							$e = array
							(
									'title' => 'No Entries Yet',
									'entry' => '<a href="/admin.php">Post an entry!</a>'
							);
						}
			
	}
	//End of the else Loop
	
	// Return loaded data
	
	// Add the $fulldisp flag to the end of the array
	array_push($e, $fulldisp);
	return $e;
	
}
//Function retriveEntries Closed

function sanitizeData($data)
{
	// If $data is not an array, run strip_tags()
	if(!is_array($data))
	{
		// Remove all tags except <a> tags
		return strip_tags($data, "<a>");
	}
	// If $data is an array, process each element
	else
	{
		// Call sanitizeData recursively for each array element
		return array_map('sanitizeData', $data);
	}
}

?>
