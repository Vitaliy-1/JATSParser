<?php 

require_once __DIR__ . '/vendor/autoload.php';

use JATSParser\Body\Document as Document;

$jatsDocument = new Document("document.xml");
$articleSections = $jatsDocument->getArticleSections();
var_dump($articleSections);

