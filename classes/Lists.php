<?php

class Lists extends Section {
	private $type;
    private $content = array();

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getContent(): array
    {
        return $this->content;
    }

    public function setContent(array $content)
    {
        $this->content = $content;
    }
}