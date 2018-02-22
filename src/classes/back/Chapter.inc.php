<?php namespace JATSParser;

require_once ("AbstractReference.inc.php");
require_once ("Author.inc.php");
require_once ("Editor.inc.php");

use JATSParser\AbstractReference as AbstractReference;
use JATSParser\Author as Author;
use JATSParser\Editor as Editor;
use JATSParser\Document as Document;

class Chapter extends AbstractReference {

	/* @var $id string */
	private $id;

	/* @var $title string */
	private $title;

	/* @var $authors array of Authors */
	private $authors;

	/* @var $year string */
	private $year;

	/* @var $book string */
	private $book;

	/* @var $editors array */
	private $editors;

	/* @var $publisherLoc string */
	private $publisherLoc;

	/* @var $publisherName string */
	private $publisherName;


	public function __construct(\DOMElement $reference) {

		parent::__construct();

		$this->id = $this->extractId($reference);
		$this->authors = $this->extractAuthors($reference);
		$this->editors = $this->extractEditors($reference);
		$this->title = $this->extractOneProperty($reference, ".//chapter-title");
		$this->year = $this->extractOneProperty($reference, ".//year");
		$this->book = $this->extractOneProperty($reference, ".//source");
		$this->publisherLoc = $this->extractOneProperty($reference, ".//publisher-loc");
		$this->publisherName = $this->extractOneProperty($reference, ".//publisher-name");
	}

	public function getId(): string {
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