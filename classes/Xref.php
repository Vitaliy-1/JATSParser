<?php

class Xref extends ParContent {
	private $ref;
	
	public function setRef ($ref) {
		$this->ref = $ref;
	}
	
	public function getRef() {
		return $this->ref;
	}
}