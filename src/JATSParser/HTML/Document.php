<?php namespace JATSParser\HTML;

use JATSParser\Body\Document as JATSDocument;
use JATSParser\HTML\Par as  Par;
use JATSParser\HTML\Listing as Listing;

class Document extends \DOMDocument {

	public function __construct(JATSDocument $jatsDocument, , bool $parseReferences = true) {
		parent::__construct('1.0', 'utf-8');
		$this->preserveWhiteSpace = false;
		$this->formatOutput = true;

		$articleSections = $jatsDocument->getArticleSections();
		$this->extractContent($articleSections);

		if ($jatsDocument->getReferences() && $parseReferences) $this->extractReferences($jatsDocument->getReferences());
	}
	
	public function getHmtlForGalley() {
		return $this->saveHTML();
	}

	public function getHtmlForTCPDF() {

		// set text-wide styles;
		$xpath = new \DOMXPath($this);
		$referenceLinks = $xpath->evaluate("//a[@class=\"bibr\"]");
		foreach ($referenceLinks as $referenceLink) {
			$referenceLink->setAttribute("style", "background-color:#e6f2ff; color:#1B6685; text-decoration:none;");
		}

		$tableAndFigureLinks = $xpath->evaluate("//a[@class=\"table\"]|//a[@class=\"fig\"]");
		foreach ($tableAndFigureLinks as $tableAndFigureLink) {
			$tableAndFigureLink->setAttribute("style", "background-color:#c6ecc6; color:#495A11; text-decoration:none;");
		}

		$headerOnes = $xpath->evaluate("//h2");
		foreach ($headerOnes as $headerOne) {
			$headerOne->setAttribute("style", "color: #343a40; font-size:20px;");
		}

		$headerTwos = $xpath->evaluate("//h3");
		foreach ($headerTwos as $headerTwo) {
			$headerTwo->setAttribute("style", "color: #343a40; font-size: 16px;");
		}
		
		// set style for figures and table
		$tableNodes = $xpath->evaluate("//table");
		foreach ($tableNodes as $tableNode) {
			$tableNode->setAttribute("style", "font-size:10px;");
			$tableNode->setAttribute("border", "1");
			$tableNode->setAttribute("cellpadding", "2");
		}
		
		$captionNodes = $xpath->evaluate("//figure/p[@class=\"caption\"]|//table/caption");
		
		foreach ($captionNodes as $captionNode) {
			$captionNode->setAttribute("style", "font-size:10px;display:block;");
			$forBoldNodes = $xpath->evaluate("span[@class=\"label\"]", $captionNode);
			foreach ($forBoldNodes as $forBoldNode) {
				$forBoldNode->setAttribute("style", "font-weight:bold;font-size:10px;");
				$emptyTextNode = $this->createTextNode(" ");
				$forBoldNode->appendChild($emptyTextNode);
			}
			$forItalicNodes = $xpath->evaluate("span[@class=\"title\"]", $captionNode);
			foreach ($forItalicNodes as $forItalicNode) {
				$forItalicNode->setAttribute("style", "font-style:italic;font-size:10px;");
				$emptyTextNode = $this->createTextNode(" ");
				$forItalicNode->appendChild($emptyTextNode);
			}
			$forNotesNodes = $xpath->evaluate("span[@class=\"notes\"]", $captionNode);
			foreach ($forNotesNodes as $forNotesNode) {
				$forNotesNode->setAttribute("style", "font-size:10px;");
			}
		}
		
		$tableCaptions = $xpath->evaluate("//table/caption");
		foreach ($tableCaptions as $tableCaption) {
			/* @var $tableNode \DOMNode */
			$tableNode = $tableCaption->parentNode;
			$divNode = $this->createElement("div");
			$nextToTableNode = $tableNode->nextSibling;
			if ($nextToTableNode) {
				$tableNode->parentNode->insertBefore($divNode, $nextToTableNode);
			}
			$divNode->appendChild($tableCaption);
			
		}

		// final preparations
		$htmlString = $this->saveHTML();
		/* For HTML editing in UTF-8 should be used: $htmlString = $this->saveHTML($this); */

		$htmlString = preg_replace("/<li>\s*/", "<li>", $htmlString);

		return $htmlString;
	}

	/**
	 * @param $articleSections array;
	 */
	private function extractContent(array $articleSections): void
	{
		foreach ($articleSections as $articleSection) {

			switch (get_class($articleSection)) {
				case "JATSParser\Body\Par":
					$par = new Par();
					$this->appendChild($par);
					$par->setContent($articleSection);
					break;
				case "JATSParser\Body\Listing":
					$listing = new Listing($articleSection->getStyle());
					$this->appendChild($listing);
					$listing->setContent($articleSection);
					break;
				case "JATSParser\Body\Table":
					$table = new Table();
					$this->appendChild($table);
					$table->setContent($articleSection);
					break;
				case "JATSParser\Body\Figure":
					$figure = new Figure();
					$this->appendChild($figure);
					$figure->setContent($articleSection);
					break;
				case "JATSParser\Body\Media":
					$media = new Media();
					$this->appendChild($media);
					$media->setContent($articleSection);
					break;
				case "JATSParser\Body\Section":
					if ($articleSection->getTitle()) {
						$sectionElement = $this->createElement("h" . ($articleSection->getType() + 1), $articleSection->getTitle());
						$sectionElement->setAttribute("class", "article-section-title");
						$this->appendChild($sectionElement);
					}
					$this->extractContent($articleSection->getContent());
					break;
			}
		}
	}

	private function extractReferences (array $references): void {

		$referencesHeading = $this->createElement("h2");
		$referencesHeading->setAttribute("class", "article-section-title");
		$referencesHeading->setAttribute("id", "reference-title");
		$referencesHeading->nodeValue = "References";
		$this->appendChild($referencesHeading);

		$referenceList = $this->createElement("ol");
		$referenceList->setAttribute("class", "references");
		$this->appendChild($referenceList);

		foreach ($references as $reference) {
			$htmlReference = new Reference();
			$referenceList->appendChild($htmlReference);
			$htmlReference->setContent($reference);
		}
	}
}
