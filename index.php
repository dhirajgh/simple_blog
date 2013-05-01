<?php

session_start();

// Include the necessary files
include_once 'inc/function.inc.php';
include_once 'inc/db.inc.php';

// Open a database connection
$db = new PDO(DB_INFO, DB_USER, DB_PASS);

        /*
        * Figure out what page is being requested (default is blog)
        * Perform basic sanitization on the variable as well
        */
        if(isset($_GET['page']))
        {
        $page = htmlentities(strip_tags($_GET['page']));
        }
        else
        {
        $page = 'blog';
        }

// Determine if an entry URL was passed
$url = (isset($_GET['url'])) ? $_GET['url'] : NULL;
// Load the entries
$e = retrieveEntries($db, $page, $url);

// Get the fulldisp flag and remove it from the array
$fulldisp = array_pop($e);

// Sanitize the entry data
$e = sanitizeData($e);

?>

<!--   Start of the html code ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->

<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<!--  Start of the head tag of html -->

<head><meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<!-- Linking of the css stylesheet from other source -->

<link rel="stylesheet" href="/css/default.css" type="text/css" />
<link rel="alternate" type="application/rss+xml"
title="My Simple Blog - RSS 2.0"
href="/simple_blog/feeds/rss.php" />
<title> Simple Blog</title>
</head>

<!-- Start of the body element -->
<body>
	<h1> Simple Blog Application </h1>
    <ul id="menu">
    <li><a href="/simple_blog/blog/">Blog</a></li>
    <li><a href="/simple_blog/about/">About the Author</a></li>
    </ul>
    
		<div id="entries">
			<?php
				// Format the entries from the database


				// If the full display flag is set, show the entry
				if($fulldisp==1)
					{
						
						// Get the URL if one wasn't passed
						$url = (isset($url)) ? $url : $e['url'];
						
						
						if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1)
						{
							// Build the admin links
							$admin = adminLinks($page, $url);
						}
						else
						{
							$admin = array('edit'=>NULL, 'delete'=>NULL);
						}
						
						                       
                        // Build the admin links
               // Commented out because included in the upper loop 
               //if not working you can check this line of code in the first instance    
               //     $admin = adminLinks($page, $url);
                        
                        // Format the image if one exists
                        $img = formatImage($e['image'], $e['title']);
                        
                        if($page=='blog')
                        {
                        	// Load the comment object
                        	include_once 'inc/comments.inc.php';
                        	$comments = new Comments();
                        	$comment_disp = $comments->showComments($e['id']);
                        	$comment_form = $comments->showCommentForm($e['id']);
                        }
                        else
                        {
                        	$comment_form = NULL;
                        }
                        
                        
			?>
					<h2> <?php echo $e['title'] ?> </h2>
					<p> <?php echo $img, $e['entry'] ?> </p>
					<p> <?php echo $e['entry'] ?> </p>
                    <p>
                    <?php echo $admin['edit'] ?>
                    <?php if($page=='blog') echo $admin['delete'] ?>
                    </p>
                    
                    <?php if($page=='blog'): ?>
					<p class="backlink">
					<a href="./">Back to Latest Entries</a>
					</p>
					<h3> Comments for This Entry </h3>
					<?php echo $comment_disp, $comment_form; endif; ?>
								
							
			
		
			<?php

					} // End the if statement


// If the full display flag is 0, format linked entry titles
					else
					{
						// Loop through each entry
						foreach($e as $entry) 
							{

			?>
					<p><a href="/simple_blog/<?php echo $entry['page'] ?>/<?php echo $entry['url'] ?>">
					<?php echo $entry['title'] ?></a></p>
					
					<?php
							} // End the foreach loop
					} // End the else

					?>
					
                   
					<p class="backlink">
					
					<?php  // Start of if Loop
					if($page=='blog'
					&& isset($_SESSION['loggedin'])
					&& $_SESSION['loggedin'] == 1):
					?>
					
					<a href="/simple_blog/admin/<?php echo $page ?>">
					Post a New Entry
					</a>
					<?php endif;  // End of Php if Loop ?>
					</p>
					
					<p>
					<a href="/simple_blog/feeds/rss.php">
					Subscribe via RSS!
					</a>
					</p>
                    
	</div>
	
</body>


</html>
