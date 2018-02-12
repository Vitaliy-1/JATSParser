<?php namespace JATSParser;

require_once("src/classes/body/Document.inc.php");
require_once("src/classes/body/Section.inc.php");
require_once("src/classes/body/Par.inc.php");
use JATSParser\Document;
$jatsDocument = new Document("document.xml");
$articleSections = $jatsDocument->getArticleSections();



foreach ($articleSections as $articleSection) {

	//echo $articleSection->getType() . " " . $articleSection->getTitle() . " " . $articleSection->hasSections() . "\n";

	/* @var $articleSection Section; */

	foreach ($articleSection->getContent() as $sectionContent) {
		if (get_class($sectionContent) === "JATSParser\Par") {
			/* @var $jatsText Text; */

			foreach ($sectionContent->getContent() as $jatsText) {
				if(get_class($jatsText) === "JATSParser\Text") {
					if (!in_array("normal", $jatsText->getType())) {
						foreach ($jatsText->getType() as $jatsType) {
							//echo "\n" . $jatsType  . "\n";
						}
						//echo $jatsText->getContent();
						foreach ($jatsText->getType() as $jatsType) {
							//echo "\n" . "/" .$jatsType  . "\n";
						}
					} else {
						//echo $jatsText->getContent();
					}
				}
			}
		} elseif (get_class($sectionContent) === "JATSParser\Listing") {
			$jatsText = exctractNestedLists($sectionContent);
		} elseif (get_class($sectionContent) === "JATSParser\Table") {
			/* @var $sectionContent Table */
			//echo $sectionContent->getLabel();
			/* @var $row Row */
			foreach ($sectionContent->getContent() as $row) {
				//echo $row->getType();
				/* @var $cell Cell */
				foreach ($row->getContent() as $cell) {
					/* @var $cellContent Text */
					foreach ($cell->getContent() as $cellContent) {
						//echo "\n";
						foreach ($cellContent->getType() as $key => $jatsTextType) {
							//echo $key . ":". $jatsTextType . "  " . $cellContent->getContent();
						}
					}
				}
			}
		} elseif (get_class($sectionContent) === "JATSParser\Figure") {
			/* @var $sectionContent Figure */
			/* @var $jatsText Text */
			foreach ($sectionContent->getTitle() as $jatsText) {
				//echo $jatsText->getContent();
			}
		}
	}
}

/**
 * @param $sectionContent
 * @return mixed
 */
function exctractNestedLists($sectionContent)
{
	foreach ($sectionContent->getContent() as $listItems) {
		if (get_class($listItems) === "JATSParser\ListItem") {
			foreach ($listItems->getContent() as $jatsText) {
				//echo $jatsText->getContent() . "\n";
			}
		} elseif (get_class($listItems) === "JATSParser\Listing") {
			$jatsText = exctractNestedLists($listItems);
			/*
			foreach ($listItems->getContent() as $subListItem) {
				foreach ($subListItem->getContent() as $jatsText) {
					//echo $jatsText->getContent() . "\n";
				}
			}
			*/
		}
	}
	return $jatsText;
}
