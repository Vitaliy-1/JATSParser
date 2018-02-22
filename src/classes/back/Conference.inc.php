<?php namespace JATSParser;

require_once ("AbstractReference.inc.php");
require_once ("Author.inc.php");

use JATSParser\AbstractReference as AbstractReference;
use JATSParser\Author as Author;

class Conference extends AbstractReference {

	/* @var $id string */
	private $id;

	/* @var $title string */
	private $title;

	/* @var $authors array of Authors */
	private $authors;

	/* @var $year string */
	private $year;

	/* @var $confName string */
	private $confName;

	/* @var $confLoc string */
	private $confLoc;

	/* @var $confDate string */
	private $confDate;


	public function __construct(\DOMElement $reference) {

		parent::__construct();

		$this->id = $this->extractId($reference);
		$this->authors = $this->extractAuthors($reference);
		$this->title = $this->extractOneProperty($reference, ".//source");
		$this->year = $this->extractOneProperty($reference, ".//year");
		$this->confName = $this->extractOneProperty($reference, ".//conf-name");
		$this->confLoc = $this->extractOneProperty($reference, ".//conf-loc");
		$this->confDate = $this->extractOneProperty($reference, ".//conf-date");
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

