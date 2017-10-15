<?php
// ONLY UPDATES
    // if date in cache and date in xml are different.
// Reports if there was an update or not, and
// displays the cache

// by James Fulford

$cache_dir = "cache.html";  // the file the user requests
$backup_dir = "Resources/backcache.html";  // backup, just in case.

$dom = new DOMDocument();
@$dom->loadHTML(file_get_contents($cache_dir));
$cache_ran = $dom->documentElement->getAttribute("lastrun");

include "Resources/get_xml.php";  // get $xml and $daterun of current xml file

if (strcasecmp($daterun, $cache_ran) != 0) { // updates if dates are different
    include "Resources/force_update.php";
} else {
    echo "<h1>Not Updated.</h1>";
    echo "<h3><br/>If you wish to update the cache, replace the \"Course Data/Classes.xml\" with the new file. </h3>";
    include $cache_dir;
}

?>