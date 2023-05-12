<?php

namespace JATSParser\Front;

use JATSParser\Body\Document as Document;

class JournalMeta {
    
    protected $journalTitle;
    
    protected $abbrevJournalTitle;
    
    protected $issn;
    
    protected $publisherName;

    public function __construct(\DOMElement $journalmeta) {

        $this->xpath = Document::getXpath();
        $this->journalTitle = $this->extractFromElement($journalmeta, './/journal-title[1]');
        $this->abbrevJournalTitle = $this->extractFromElement($journalmeta, './/abbrev-journal-title[1]');
        $this->issn = $this->extractFromElement($journalmeta, './/issn[1]');
        $this->publisherName = $this->extractFromElement($journalmeta, './/publisher-name[1]');
    }
    
    public function getJournalTitle() {
        return $this->journalTitle;
    }
    
    public function getAbbrevJournalTitle() {
        return $this->abbrevJournalTitle;
    }
    
    public function getIssn() {
        return $this->issn;
    }
    
    public function getPublisher() {
        return $this->publisherName;
    }

    protected function extractFromElement(\DOMElement $reference, string $xpathExpression) {
        $property = '';
        $searchNodes = $this->xpath->query($xpathExpression, $reference);
        if ($searchNodes->length > 0) {
            foreach ($searchNodes as $searchNode) {
                $property = $searchNode->nodeValue;
            }
        }
        return $property;
    }
    
    

}
