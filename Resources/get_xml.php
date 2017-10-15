<?php
    // filename may change...
    // make it use the .xml file
    // perhaps validate it, make sure its the right one.
    $xml=simplexml_load_file(dirname(__FILE__)."/../Course Data/Classes.xml") or die("Error: Cannot access xml file!"); // this may get more complicated later.
    $daterun = $xml->rec->DateRun;
?>