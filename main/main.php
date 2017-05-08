<?php

require_once("body.php");
require_once ("back.php");
require_once("../html/general.php");
require_once("../html/body.php");
require_once ("../html/back.php");

/* iterating through JATS XML nodes and write data to Objects */
$xml = new DOMDocument();
$xml->load("../test.xml");
$xpath = new DOMXPath($xml);

/* parsing sections inside the body */
$sections = Body($xpath);

/* parsing references */
$references = Back($xpath);

/* generating
 * html
 * */

/* generating the structure of html */
$html = htmlGeneralStructure();

/* add article body to html */
htmlBodyStructure($html, $sections); // sections -> ArrayObject
htmlBackStructure($html, $references);  // $references -> References class


/* saving html to a file */
$html->saveHTML();
$output = $html->saveHTML();
$the_file = "../test.html";
$html->save($the_file);
file_put_contents($the_file, preg_replace('/<\?xml[^>]+>\s+/', '<!DOCTYPE html>' . "\n", file_get_contents($the_file)));
