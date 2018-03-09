<?php namespace JATSParser\Body;

use JATSParser\Body\JATSElement as JATSElement;
use JATSParser\Body\Text as Text;
use JATSParser\Body\Par as Par;

class Cell implements JATSElement {

	/* @var array Can contain Par and Text */
	private $content = array();

	/* @var $type string  */
	private $type;

	function __construct(\DOMElement $cellNode) {
		$this->type = $cellNode->nodeName;

		$content = array();
		$xpath = Document::getXpath();
		$childNodes = $xpath->query("child::node()", $cellNode);
		foreach ($childNodes as $childNode) {
			if ($childNode->nodeName === "p") {
				$par = new Par($childNode);
				$content[] = $par;
			} else {
				$jatsTextNodes = $xpath->query(".//self::text()", $childNode);
				foreach ($jatsTextNodes as $jatsTextNode){
					$jatsText = new Text($jatsTextNode);
					$content[] = $jatsText;
				}
			}
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
