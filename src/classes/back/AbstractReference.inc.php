<?php namespace JATSParser;

require_once (__DIR__ . "/../../interfaces/Reference.inc.php");

use JATSParser\Reference as Reference;
use JATSParser\Document as Document;

abstract class AbstractReference implements Reference {

	protected $xpath;

	abstract public function getId();

	abstract public function getTitle();

    abstract public function getAuthors();

	abstract public function getYear();

	protected function __construct() {
		$this->xpath = Document::getXpath();
	}

	protected function extractOneProperty(\DOMElement $reference, string $xpathExpression) {
		$property = null;
		$searchNodes = $this->xpath->query($xpathExpression, $reference);
		if ($searchNodes->length > 0) {
			foreach ($searchNodes as $searchNode) {
				$property = $searchNode->nodeValue;
			}
		}
		return $property;
	}

	protected function extractPubId(\DOMElement $reference, string $pubIdName) {
		$pubIdValue = null;
		$pubIdNodes = $this->xpath->query(".//pub-id" , $reference);
		if ($pubIdNodes->length > 0) {
			foreach ($pubIdNodes as $pubIdNode) {
				/* @var $pubIdNode \DOMElement */
				if($pubIdNode->hasAttribute("pub-id-type")) {
					$pubAttributeValue = $pubIdNode->getAttribute("pub-id-type");
					if ($pubAttributeValue == $pubIdName) {
						$pubIdValue = $pubIdNode->nodeValue;
					}
				}
			}
		}
		return $pubIdValue;
	}

	protected function extractId(\DOMElement $reference){
		$id = null;
		if ($reference->hasAttribute("id")) {
			$id = $reference->getAttribute("id");
		}
		return $id;
	}

	protected function extractAuthors(\DOMElement $reference) {
		$authors = array();
		$ifAuthors = $this->xpath->query(".//person-group", $reference);
		foreach ($ifAuthors as $ifAuthor) {
			/* @var $ifAuthor \DOMElement */
			if (!$ifAuthor->hasAttribute("person-group-type") || $ifAuthor->getAttribute("person-group-type") == "author")  {
				$authorNodes = $this->xpath->query(".//name|.//collab", $ifAuthor);
				if ($authorNodes->length > 0) {
					foreach ($authorNodes as $authorNode) {
						$author = new Author($authorNode);
						$authors[] = $author;
					}
				}
			}
		}
		return $authors;
	}

	protected function extractEditors(\DOMElement $reference) {
		$editors = array();
		$ifEditors = $this->xpath->query(".//person-group", $reference);
		foreach ($ifEditors as $ifEditor) {
			/* @var $ifEditor \DOMElement */
			if ($ifEditor->getAttribute("person-group-type") == "editor")  {
				$authorNodes = $this->xpath->query(".//name|.//collab", $ifEditor);
				if ($authorNodes->length > 0) {
					foreach ($authorNodes as $authorNode) {
						$editor = new Editor($authorNode);
						$editors[] = $editor;
					}
				}
			}
		}
		return $editors;
	}
}