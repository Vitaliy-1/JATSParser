<?php namespace JATSParser;

interface Reference {

	public function getId();

	public function getTitle();

	public function getAuthors();

	public function getYear();

}