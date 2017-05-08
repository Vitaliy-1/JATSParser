<?php


class Section {
    private $title;
    private $type;
    private $content;

    public function __construct() {
        $this->content = new ArrayObject(array());
    }

	public function setTitle ($title) {
		$this->title = $title;
	}
	
	public function getTitle() {
		return $this->title;
	}

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

    public function getContent(): ArrayObject
    {
        if ($this->content == null) {
            $this->content = new ArrayObject();
        }
        return $this->content;
    }
}