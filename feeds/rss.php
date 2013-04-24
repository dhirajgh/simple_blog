<?php

// Add a content type header to ensure proper execution
header('Content-Type: application/rss+xml');

// Output the XML declaration
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

//This does not work in Chrome but works in Internet Explorer
//Works perfectly well in Mozilla Browser

?>


<rss version="2.0">
<channel>
		<title>My Simple Blog</title>
		<link>http://localhost/simple_blog/</link>
		<description>This blog is awesome.</description>
		<language>en-us</language>
</channel>
</rss>
