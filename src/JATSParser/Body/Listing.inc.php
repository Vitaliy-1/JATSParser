<?php namespace JATSParser\Body;

use JATSParser\Body\JATSElement as JATSElement;
use JATSParser\Body\Document as Document;
use JATSParser\Body\ListItem as ListItem;

class Listing implements JATSElement {

	/*
	 * @var int
	 * type of a list: 1, 2, 3, 4 -> list, sublist, subsublist, etc.
	 * default is 1
	 */
	public $type;

	/* @var string: "unordered", "ordered" */
	public $style;

	public $content;

	public function __construct(\DOMElement $list) {
		$xpath = Document::getXpath();
		$content = array();
		$this->style = $list->getAttribute("list-type");
		$this->type = 1;

		$listChildNodes = $xpath->evaluate("list-item/p|list-item", $list);

		foreach ($listChildNodes as $listChildNode) {
			if ($this->isTextNode($listChildNode) === TRUE) {
				$listItem = new ListItem($listChildNode);
				$content[] = $listItem;
			}
			$nestedListNodes = $xpath->evaluate("list", $listChildNode);
			foreach ($nestedListNodes as $nestedListNode) {
				$listing = new Listing($nestedListNode);
				$content[] = $listing;
			}
		}

		$this->content = $content;
	}

	public function getContent(): array {
		return $this->content;
	}

	/**
	 * @return int
	 */
	public function getType(): int {
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getStyle(): string {
		return $this->style;
	}

	private function isTextNode($listChildNode): bool {
		if ($listChildNode->nodeType != XML_TEXT_NODE) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}