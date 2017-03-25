<?php

class ParContent extends Section {
	public function getParContent() {
		return new ArrayObject($this);
	}
}