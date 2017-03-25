<?php
require("classes/Section.php");
require("classes/ParContent.php");
require ("classes/ParText.php");
require ("classes/Xref.php");
require ("classes/Lists.php");

/* iterating through JATS XML nodes and write data to Objects*/
$xml = new DOMDocument();
$xml->load("D:\workphp\JATSParser/test.xml");
$xpath = new DOMXPath($xml);
$sections = new ArrayObject();
foreach ($xpath->evaluate("/article/body/sec") as $sec) {
	// echo "\n";
	$section = new Section();
	foreach ($xpath->evaluate("title|p|fig|sec|table-wrap|list", $sec) as $secContent) {
		$paragraphContent = new ParContent();
		if ($secContent->tagName == "title") {
			//echo $secContent->nodeValue, "\n";
			$section->setTitle($secContent->nodeValue);
		} else if ($secContent->tagName == "list") {
			//echo "List will appear next \n";
			$list = new Lists();
			foreach ($xpath->evaluate("list-item/p", $secContent) as $listItem) {
				//echo "listItem: ", $listItem->nodeValue, "\n";
			}
			$section->getSecContent()->offsetSet(null, $list);
		} else if ($secContent->tagName == "p") {
			//echo "\n";
			foreach ($secContent->childNodes as $parContent) {
				if ($parContent->nodeType == XML_TEXT_NODE) {
					//echo $parContent->nodeValue;
					$parText = new ParText();
					$parText->setContent($parContent->nodeValue);
					$paragraphContent->getParContent()->offsetSet(null, $parText);
				} else if ($parContent->tagName == "xref") {
					if ($parContent->getAttribute("ref-type") == "bibr") {
						$ref = new Xref();
						$ref->setRef($parContent->nodeValue);
						$paragraphContent->getParContent()->offsetSet(null, $ref);
						//echo "Citation: ", $parContent->nodeValue;
					} else if ($parContent->getAttribute("ref-type") == "table") {
						//echo "Table: ", $parContent->nodeValue;
					} else if ($parContent->getAttribute("ref-type") == "fig") {
						//echo "Figure: ", $parContent->nodeValue;
					}
				} else if ($parContent->tagName == "italic") {
					//echo "<i>", $parContent->nodeValue, "</i>";
				} else if ($parContent->tagName == "bold") {
					//echo "<b>", $parContent->nodeValue, "</b>";
				}
			}
			
		} else if ($secContent->tagName == "sec") {
			//echo "\n";
			foreach ($xpath->evaluate("title|p|fig|sec|table-wrap|list", $secContent) as $subSecContent) {
				if ($subSecContent->tagName == "title") {
					//echo $subSecContent->nodeValue;
				} else if ($subSecContent->tagName == "list") {
					//echo "List will appear next \n";
					foreach ($xpath->evaluate("list-item/p", $subSecContent) as $listItem) {
						//echo "listItem: ", $listItem->nodeValue, "\n";
					}
				} else if ($subSecContent->tagName == "p") {
					//echo "\n";
					foreach ($subSecContent->childNodes as $parContent) {
						if ($parContent->nodeType == XML_TEXT_NODE) {
							//echo $parContent->nodeValue;
						} else if ($parContent->tagName == "xref") {
							if ($parContent->getAttribute("ref-type") == "bibr") {
								//echo "Citation: ", $parContent->nodeValue;
							} else if ($parContent->getAttribute("ref-type") == "table") {
								//echo "Table: ", $parContent->nodeValue;
							} else if ($parContent->getAttribute("ref-type") == "fig") {
								//echo "Figure: ", $parContent->nodeValue;
							}
						} else if ($parContent->tagName == "italic") {
							//echo "<i>", $parContent->nodeValue, "</i>";
						} else if ($parContent->tagName == "bold") {
							//echo "<b>", $parContent->nodeValue, "</b>";
						}
					}
				}
			}
		}
		$section->getSecContent()->offsetSet(null, $paragraphContent);
	}
	$sections->append($section);
}

/* getting data from Objects */
foreach ($sections as $sec) {
	echo "\n", $sec->getTitle(), "\n";
	foreach ($sec->getSecContent() as $secCont) {
		
		if (get_class($secCont) == "ParContent") {
			foreach ($secCont as $parCont) {
				if (get_class($parCont) == "ParText") {
					echo $parCont->getContent();
				} else if (get_class($parCont) == "Xref") {
					echo $parCont->getRef();
				}
			}
		} else if (get_class($secCont) == "Lists") {
			 echo "Here is a list \n";
			
		}
		/*
		foreach ($secCont as $parCont) {
			echo $parCont;
		}
		*/
	}
}