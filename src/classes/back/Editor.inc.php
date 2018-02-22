<?php namespace JATSParser;

require_once (__DIR__ . "/../../interfaces/PersonGroup.inc.php");

use JATSParser\PersonGroup as PersonGroup;

class Editor implements PersonGroup {

	/* private $type string : individual, collaboration */
	private $type;

	public function __construct() {

	}

	public function getType(): string {
		return $this->type;
	}
}