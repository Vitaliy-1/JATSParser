<?php


class Section {
    private $title;
    private $type;

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

	public function getContent() {
		return new ArrayObject($this);
	}

}