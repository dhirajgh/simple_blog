<?php
function retrieveEntries($db, $page, $url=NULL)
{
	
	// Get entries from database
	/* 	 * If an entry ID was supplied, load the associated entry 	*/
	
	if(isset($url))
	{
		// Load specified entry
		
		$sql = "SELECT id, page, title, entry
		FROM entries
		WHERE url=?
		LIMIT 1";
		$stmt = $db->prepare($sql);
		$stmt->execute(array($url));
		
		// Save the returned entry array
		$e = $stmt->fetch();
		// Set the fulldisp flag for a single entry
		$fulldisp = 1;
	}
	//End of if statement
	
	/* * If no entry ID was supplied, load all entry titles */
		else
	{
				$sql = "SELECT id, page, title, entry, url
				FROM entries
				WHERE page=?
				ORDER BY created DESC";
                $stmt = $db->prepare($sql);
                $stmt->execute(array($page));
                $e = NULL; // Declare the variable to avoid errors
                
			// Loop through returned results and store as an array
			while($row = $stmt->fetch()) {
			if($page=='blog')
			{
			$e[] = $row;
			$fulldisp = 0;
			}
			else
			{
			$e = $row;
			$fulldisp = 1;
			}
			}

				
				// Set the fulldisp flag for multiple entries
			//	$fulldisp = 0;
				
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
			
	}//End of the else Loop
	
	// Return loaded data
	// Add the $fulldisp flag to the end of the array
	array_push($e,$fulldisp);
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

function makeUrl($title)
{
	$patterns = array(
			'/\s+/',
			'/(?!-)\W+/'
	);
	$replacements = array('-', '');
	return preg_replace($patterns, $replacements, strtolower($title));
}

//Writting the administrative link function for adminLinks 
function adminLinks($page, $url)
        {
            // Format the link to be followed for each option
            $editURL = "/simple_blog/admin/$page/$url";
            $deleteURL = "/simple_blog/admin/delete/$url";
            // Make a hyperlink and add it to an array
            $admin['edit'] = "<a href=\"$editURL\">edit</a>";
            $admin['delete'] = "<a href=\"$deleteURL\">delete</a>";
            return $admin;
        }

//writing the confirm delete button fuction 
function confirmDelete($db, $url)
{
$e = retrieveEntries($db, '', $url);
return <<<FORM
<form action="/simple_blog/admin.php" method="post">
<fieldset>
<legend>Are You Sure?</legend>
<p>Are you sure you want to delete the entry "$e[title]"?</p>
<input type="submit" name="submit" value="Yes" />
<input type="submit" name="submit" value="No" />
<input type="hidden" name="action" value="delete" />
<input type="hidden" name="url" value="$url" />
</fieldset>
</form>
FORM;
}

//writing the delete entry 
function deleteEntry($db, $url)
{
$sql = "DELETE FROM entries
WHERE url=?
LIMIT 1";
$stmt = $db->prepare($sql);
return $stmt->execute(array($url));
}





?>
