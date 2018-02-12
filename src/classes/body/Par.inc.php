<?php namespace JATSParser;

require_once("Text.inc.php");

use JATSParser\JATSElement as JATSElement;
use JATSParser\Document as Document;
use JATSParser\Text as Text;

class Par implements JATSElement {

	/**
	 *   @var $content array
	 */

	private $content;

	function __construct(\DOMElement $paragraph) {
		$xpath = Document::getXpath();
		$content = array();
		$parTextNodes = $xpath->query(".//text()", $paragraph);
		foreach ($parTextNodes as $parTextNode) {
			$jatsText = new Text($parTextNode);
			$content[] = $jatsText;
		}
		$this->content = $content;
	}

	public function getContent(): array {
		return $this->content;
	}
}