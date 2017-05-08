<?php

class BibName {
    private $surname;
    private $initials;
    private $givenname;

    public function __construct() {
        $this->initials = array();
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname(string $surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return array
     */
    public function getInitials(): array
    {
        return $this->initials;
    }

    /**
     * @param array $initials
     */
    public function setInitials(array $initials)
    {
        $this->initials = $initials;
    }

    /**
     * @return mixed
     */
    public function getGivenname()
    {
        return $this->givenname;
    }

    /**
     * @param mixed $givenname
     */
    public function setGivenname($givenname)
    {
        $this->givenname = $givenname;
    }
}