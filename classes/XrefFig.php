<?php

class XrefFig extends ParContent {
    private $content;
    private $rid;

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param mixed $rid
     */
    public function setRid($rid)
    {
        $this->rid = $rid;
    }

    /**
     * @return mixed
     */
    public function getRid()
    {
        return $this->rid;
    }
}