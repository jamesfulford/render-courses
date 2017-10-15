<!-- Resources/force_update.php -->
<?php
// ALWAYS UPDATES
// displays the cache and time of update.
// by James Fulford

$cache_dir = dirname(__FILE__) . "/../cache.html";  // the file the user requests
$backup_dir = dirname(__FILE__) . "/backcache.html";  // backup, just in case.


include "get_xml.php";  // get $xml and $daterun of current xml file

function get_result($filename) {  // found this idea online.
    if (is_file($filename)) {
        ob_start();
        include $filename;
        return ob_get_clean();
    }
    return false;
}
$string = get_result(dirname(__FILE__).'/process.php');
$cache = fopen($cache_dir, 'r');
file_put_contents($backup_dir, fread($cache, filesize($cache_dir)));
fclose($cache);
file_put_contents($cache_dir, $string);


date_default_timezone_set("EST");
$date = date("h:i a, m/d/Y");
echo "<h1>Updated $date</h1>";
include $cache_dir;

?>