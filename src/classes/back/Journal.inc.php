<?php namespace JATSParser;

require_once ("AbstractReference.inc.php");
require_once ("Author.inc.php");
require_once (__DIR__ . "/../body/Document.inc.php");

use JATSParser\AbstractReference as AbstractReference;
use JATSParser\Author as Author;
use JATSParser\Document as Document;

class Journal extends AbstractReference {

	/* @var $id string */
	private $id;

	/* @var $title string */
	private $title;

	/* @var $authors array of Authors */
	private $authors;

	/* @var $year string */
	private $year;

	/* @var $volume string */
	private $volume;

	/* @var $issue string */
	private $issue;

	/* @var $fpage string */
	private $fpage;

	/* @var $lpage string */
	private $lpage;

	/* @var $doi string */
	private $doi;

	/* @var $pmid string */
	private $pmid;

	/* @var $url string */
	private $url;

	public function __construct(\DOMElement $reference) {

		parent::__construct();

		$this->title = $this->extractOneProperty($reference, ".//article-title[1]");
		$this->year = $this->extractOneProperty($reference, ".//year[1]");
		$this->volume = $this->extractOneProperty($reference, ".//volume[1]");
		$this->issue = $this->extractOneProperty($reference, ".//issue[1]");
		$this->fpage = $this->extractOneProperty($reference, ".//fpage[1]");
		$this->lpage = $this->extractOneProperty($reference, ".//lpage[1]");
		$this->doi = $this->extractPubId($reference, "doi");
		$this->pmid = $this->extractPubId($reference, "pmid");
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

	public function getFpage(): string {
		return $this->fpage;
	}

	public function getLpage(): string {
		return $this->lpage;
	}

	public function getIssue(): string {
		return $this->issue;
	}

	public function getVolume(): string {
		return $this->volume;
	}

	public function getDoi(): string {
		return $this->doi;
	}

	public function getPmid(): string {
		return $this->pmid;
	}

	public function getUrl(): string {
		return $this->url;
	}
}