<?php namespace JATSParser\Back;

use JATSParser\Back\Reference as Reference;
use JATSParser\Back\Collaboration as Collaboration;
use JATSParser\Body\Document as Document;

abstract class AbstractReference implements Reference {

	protected $xpath;

	/* @var $id string */
	protected $id;

	/* @var array can contain instances of Individual and Collaboration class */
	protected $personGroup;

	/* @var $year string */
	protected $year;

	/* @var $url string */
	protected $url;

	/* @var $pubIdType array publication Identifier for a cited publication */
	protected $pubIdType;

	abstract public function getId();

	abstract public function getTitle();

	abstract public function getPersonGroup();

	abstract public function getYear();

	abstract public function getUrl();

	abstract public function getPubIdType();

	protected function __construct(\DOMElement $reference)
	{
		$this->xpath = Document::getXpath();
		$this->personGroup = $this->extractPersonGroup($reference);
		$this->id = $this->extractId($reference);
		$this->year = $this->extractFromElement($reference,'.//year[1]');
		$this->url = $this->extractFromElement($reference, './/ext-link[@ext-link-type="uri"]');
		$this->pubIdType = $this->extractPubIdType($reference);
	}

	protected function extractFromElement(\DOMElement $reference, string $xpathExpression)
	{
		$property = null;
		$searchNodes = $this->xpath->query($xpathExpression, $reference);
		if ($searchNodes->length > 0) {
			foreach ($searchNodes as $searchNode) {
				$property = $searchNode->nodeValue;
			}
		} else {
			return false;
		}
		return $property;
	}

	private function extractId(\DOMElement $reference)
	{
		$id = null;
		if ($reference->hasAttribute("id")) {
			$id = $reference->getAttribute("id");
		} else {
			return false;
		}
		return $id;
	}

	private function extractPersonGroup(\DOMElement $reference)
	{
		$personGroup = array();

		$nameNodes = $this->xpath->query(".//name", $reference);
		if ($nameNodes->length > 0) {
			foreach ($nameNodes as $nameNode) {
				$individual = new Individual($nameNode);
				$personGroup[] = $individual;
			}
		}
		$collabNodes = $this->xpath->query(".//collab", $reference);
		if ($collabNodes->length > 0) {
			foreach ($collabNodes as $collabNode) {
				$collaborator = new Collaboration($collabNode);
				$personGroup[] = $collaborator;
			}
		}

		return $personGroup;
	}

	private function extractPubIdType(\DOMElement $reference)
	{
		$pubIdType = array();

		$pubIdNodes = $this->xpath->query('.//pub-id', $reference);
		if ($pubIdNodes->length > 0) {
			/* @var $pubIdNode \DOMElement */
			foreach ($pubIdNodes as $pubIdNode) {
				if ($pubIdNode->getAttribute('pub-id-type')) {
					$pubIdType[$pubIdNode->getAttribute('pub-id-type')] = $pubIdNode->nodeValue;
				}
			}
		} else {
			return false;
		}

		return $pubIdType;
	}
}