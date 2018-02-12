<?php namespace JATSParser;

use JATSParser\JATSElement as JATSElement;
use JATSParser\Text as Text;

class Cell implements JATSElement {

	private $content = array();

	/* @var $type string  */
	private $type;

	function __construct(\DOMElement $rowNode) {
		$this->type = $rowNode->nodeName;

		$content = array();
		$xpath = Document::getXpath();

		$jatsTextNodes = $xpath->query(".//text()", $rowNode);
		foreach ($jatsTextNodes as $jatsTextNode){
			$jatsText = new Text($jatsTextNode);
			$content[] = $jatsText;
		}

		$this->content = $content;
	}

	public function getContent(): array {
		return $this->content;
	}

	public function getType(): string {
		return $this->type;
	}
}
