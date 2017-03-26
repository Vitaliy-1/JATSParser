<?php

class ParContent extends Section {
	public function getContent() {
		return new ArrayObject($this);
	}
}