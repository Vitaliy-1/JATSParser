<?php

class Cell {
    private $colspan;
    private $rowspan;

    /**
     * @return mixed
     */
    public function getColspan()
    {
        return $this->colspan;
    }

    /**
     * @param mixed $colspan
     */
    public function setColspan($colspan)
    {
        $this->colspan = $colspan;
    }

    /**
     * @return mixed
     */
    public function getRowspan()
    {
        return $this->rowspan;
    }

    /**
     * @param mixed $rowspan
     */
    public function setRowspan($rowspan)
    {
        $this->rowspan = $rowspan;
    }

    /* for ParContent as an array */
    public function getContent() {
        return new ArrayObject($this);
    }
}