<?php


class Section {
    private $title;
	
	public function setTitle ($title) {
		$this->title = $title;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function getContent() {
		return new ArrayObject($this);
	}
	
}