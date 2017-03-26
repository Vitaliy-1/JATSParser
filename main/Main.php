<?php

require("Body.php");
require("../test/Body.php");

/* iterating through JATS XML nodes and write data to Objects*/
$xml = new DOMDocument();
$xml->load("D:\workphp\JATSParser/test.xml");
$xpath = new DOMXPath($xml);

/* parsing of sections inside the body */
$sections = Body($xpath);

/* testing body output */
testSections($sections);