<?php namespace JATSParser;

require_once(__DIR__ . "/../../interfaces/JATSElement.inc.php");

use JATSParser\JATSElement as JATSElement;
use JATSParser\Document as Document;

class ListItem implements JATSElement {

	/* @var $content array - contains array of JATSText */
	private $content;

	public function __construct(\DOMElement $listItemNode) {
		$content = array();
		$xpath = Document::getXpath();

		/* form a string for XPath that contains expression with supported text formats */
		$stringForXpath = $this->getStringForXpath();
		$listItemTexts = $xpath->evaluate("./text()|" . $stringForXpath, $listItemNode);
		foreach ($listItemTexts as $listItemText) {
			$jatsText = new Text($listItemText);
			$content[] = $jatsText;
		}
		$this->content = $content;
	}

	public function getContent(): array {
		return $this->content;
	}

	/* @return string
	 * method forms a string from an array of supported text formats */

	private function getStringForXpath(): string {
		$supportedFormatsArray = Text::getNodeCheck();
		$supportedFormatsString = implode("/text()|", $supportedFormatsArray) . "/text()";
		return $supportedFormatsString;
	}
}