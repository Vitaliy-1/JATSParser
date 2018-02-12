<?php namespace JATSParser;

require_once (__DIR__ . "/../../interfaces/Reference.inc.php");
require_once ("Author.inc.php");

use JATSParser\Reference as Reference;
use JATSParser\Author as Author;

class Conference implements Reference {

	/* @var $id string */
	private $id;

	/* @var $title string */
	private $title;

	/* @var $authors array of Authors */
	private $authors;

	/* @var $year string */
	private $year;

	public function __construct(\DOMElement $reference) {

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

