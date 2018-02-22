<?php namespace JATSParser;

require_once ("AbstractReference.inc.php");
require_once ("Author.inc.php");

use JATSParser\AbstractReference as AbstractReference;
use JATSParser\Author as Author;
use JATSParser\Document as Document;

class Book extends AbstractReference {

	/* @var $id string */
	private $id;

	/* @var $title string */
	private $title;

	/* @var $authors array of Authors */
	private $authors;

	/* @var $year string */
	private $year;

	/* @var $url string */
	private $url;

	/* @var $publisherLoc string */
	private $publisherLoc;

	/* @var $publisherName string */
	private $publisherName;

	/* @var $fpage string */
	private $fpage;

	/* @var $lpage string */
	private $lpage;

	public function __construct(\DOMElement $reference) {

		parent::__construct();

		$this->title = $this->extractOneProperty($reference, ".//source[1]");
		$this->year = $this->extractOneProperty($reference,".//year[1]");
		$this->publisherLoc = $this->extractOneProperty($reference, ".//publisher-name[1]");
		$this->publisherName = $this->extractOneProperty($reference, ".//publisher-loc[1]");
		$this->url = $this->extractOneProperty($reference, ".//ext-link[1]");
		$this->id = $this->extractId($reference);
		$this->authors = $this->extractAuthors($reference);
	}

	public function getId() {
		return $this->id;
	}

	public function getTitle(): string {
		return $this->title;
	}

	public function getAuthors(): array {
		return $this->authors;
	}

	public function getYear(): string {
		return $this->year;
	}

}