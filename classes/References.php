<?php

class References
{
    private $title;
    private $references;

    public function __construct() {
        $this->references = new ArrayObject(array());
    }

    public function getReferences(): ArrayObject
    {
        if ($this->references == null) {
            $this->references = new ArrayObject();
        }
        return $this->references;
    }

    /**
     * @return mixed
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }


}