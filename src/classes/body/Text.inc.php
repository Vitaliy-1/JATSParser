<?php namespace JATSParser;

require_once(__DIR__ . "/../../interfaces/JATSElement.inc.php");

use JATSParser\JATSElement as JATSElement;

class Text implements JATSElement {

	/* @var array
	 * defines the type of a paragraph content, possible options are:
	 * normal
	 * bold
	 * italic
	 * ref-table
	 * ref-figure
	 * ref-citation
	 * sup
	 * sub
	 */

	private $type;

	private $content;

	private static $nodeCheck = array("bold", "italic", "sup", "sub", "xref", "underline");

	public function __construct(\DOMText $paragraphContent) {
		$this->content = $paragraphContent->textContent;
		$this->extractTextNodeModifiers($paragraphContent);
		/* assign normal as a value to a text run if it has non */
		if ($this->type === NULL) {
			$this->type[] = "normal";
		}

	}

	/**
	 * @return string
	 */

	public function getContent() : string {
		return $this->content;
	}

	/**
	 * @return string[]
	 */
	public function getType(): array {
		return $this->type;
	}

	/**
	 * @return string[]
	 */
	public static function getNodeCheck(): array {
		return self::$nodeCheck;
	}

	/**
	 * @param DOMText \DOMElement
	 */
	private function extractTextNodeModifiers($paragraphContent) {
		$parentNode = $paragraphContent->parentNode;
		if (in_array($parentNode->nodeName, self::$nodeCheck)) {
			$this->extractTextNodeModifiers($parentNode);
			$this->type[] = $parentNode->nodeName;
		}

		/* text inside table cells needs special treatment */
		if ($parentNode->nodeName == "p") {
			$parentNodeOfParent = $parentNode->parentNode;
			if ($parentNodeOfParent->nodeName == "th" || $parentNodeOfParent->nodeName == "td") {
				$this->type[] = $parentNode->nodeName;
				$this->extractTextNodeModifiers($parentNode);
			}
		}
	}
}