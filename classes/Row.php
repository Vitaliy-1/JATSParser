<?php

class Row {
    private $type;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /* for Cell as an array */
    public function getContent() {
        return new ArrayObject($this);
    }
}