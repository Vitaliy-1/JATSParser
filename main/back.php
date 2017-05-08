<?php
require_once ("../classes/BibitemJournal.php");
require_once ("../classes/BibName.php");
require_once ("../classes/References.php");
require_once ("../classes/BibitemChapter.php");
require_once ("../classes/BibitemBook.php");
require_once ("../classes/BibitemConf.php");

function Back(DOMXPath $xpath): References {
    $references = new References();
    foreach ($xpath->evaluate("/article/back/ref-list/title") as $refenceSectionTitle) {
        $references->setTitle($refenceSectionTitle->nodeValue);
    }
    foreach ($xpath->evaluate("/article/back/ref-list/ref") as $bibitemNode) {
        foreach ($xpath->evaluate("element-citation[1]", $bibitemNode) as $elementCitation) {

            /* Nodes for evaluation */

            /* check nodes specific for article */
            $counterForArticle = 0;
            foreach ($xpath->evaluate("article-title", $elementCitation) as $articleTitleCheck) {
               $counterForArticle++;
            }

            /* check nodes specific for book and chapter */
            $counterForBook = 0;
            $counterForChapter = 0;
            foreach ($xpath->evaluate("source", $elementCitation) as $bookTitleCheck) {
                $counterForBook++;
                foreach ($xpath->evaluate("preceding-sibling::chapter-title|following-sibling::chapter-title", $bookTitleCheck) as $chapterTitleCheck) {
                    if ($bookTitleCheck != null && $chapterTitleCheck != null) {
                        $counterForChapter++;
                    }
                }
            }
            /*
            if ($counterForArticle == 0 && $counterForBook > 0 && $counterForChapter > 0) {
                echo "chapter! ";
            } elseif ($counterForArticle == 0 && $counterForBook > 0 && $counterForChapter == 0) {
                echo "book! ";
            }
            */
            /* Parsing reference list elements */
            if ($elementCitation->getAttribute("publication-type") == "journal" || $counterForArticle != 0) {
                $bibitemJournal = new BibitemJournal();
                $references->getReferences()->offsetSet(null, $bibitemJournal);
                $bibitemJournal->setType("journal");
                $bibitemJournal->setId($bibitemNode->getAttribute("id"));

                parsingNamesAndCollab($xpath, $elementCitation, $bibitemJournal);

                foreach ($xpath->evaluate("article-title", $elementCitation) as $articleTitle) {
                    $bibitemJournal->setTitle(trim($articleTitle->nodeValue));
                }
                foreach ($xpath->evaluate("source", $elementCitation) as $journalArticleSource) {
                    $bibitemJournal->setSource(trim($journalArticleSource->nodeValue));
                }
                foreach ($xpath->evaluate("year", $elementCitation) as $journalArticleYear) {
                    $bibitemJournal->setYear(trim($journalArticleYear->nodeValue));
                }
                foreach ($xpath->evaluate("volume", $elementCitation) as $journalArticleVolume) {
                    $bibitemJournal->setVolume(trim($journalArticleVolume->nodeValue));
                }
                foreach ($xpath->evaluate("issue", $elementCitation) as $journalArticleIssue) {
                    $bibitemJournal->setIssue(trim($journalArticleIssue->nodeValue));
                }
                foreach ($xpath->evaluate("fpage", $elementCitation) as $journalArticleFpage) {
                    $bibitemJournal->setFpage(trim($journalArticleFpage->nodeValue));
                }
                foreach ($xpath->evaluate("lpage", $elementCitation) as $journalArticleLpage) {
                    $bibitemJournal->setLpage(trim($journalArticleLpage->nodeValue));
                }

                parsingUrlDoiPmid($xpath, $elementCitation, $bibitemJournal);

            } elseif ($elementCitation->getAttribute("publication-type") == "book" || ($counterForArticle == 0 && $counterForBook > 0 && $counterForChapter == 0)) {
                $bibitemBook = new BibitemBook();
                $references->getReferences()->offsetSet(null, $bibitemBook);
                $bibitemBook->setType("book");
                $bibitemBook->setId($bibitemNode->getAttribute("id"));

                parsingNamesAndCollab($xpath, $elementCitation, $bibitemBook);

                foreach ($xpath->evaluate("source", $elementCitation) as $bookTitle) {
                    $bibitemBook->setSource(trim($bookTitle->nodeValue));
                }
                foreach ($xpath->evaluate("publisher-loc", $elementCitation) as $bookPublisherLoc) {
                    $bibitemBook->setPublisherLoc(trim($bookPublisherLoc->nodeValue));
                }
                foreach ($xpath->evaluate("publisher-name", $elementCitation) as $bookPublisherName) {
                    $bibitemBook->setPublisherName(trim($bookPublisherName->nodeValue));
                }
                foreach ($xpath->evaluate("year", $elementCitation) as $bookYear) {
                    $bibitemBook->setYear(trim($bookYear->nodeValue));
                }

                parsingUrlDoiPmid($xpath, $elementCitation, $bibitemJournal);

            }
        }
    }
    return $references;
}

/**
 * @param DOMXPath $xpath
 * @param DOMElement $elementCitation
 * @param BibitemJournal|BibitemChapter|BibitemBook|BibitemConf $bibitemJournal
 */
function parsingUrlDoiPmid(DOMXPath $xpath, DOMElement $elementCitation, $bibitemJournal)
{
    foreach ($xpath->evaluate("ext-link", $elementCitation) as $journalArticleUrl) {
        $bibitemJournal->setUrl($journalArticleUrl->nodeValue);
    }
    foreach ($xpath->evaluate("pub-id", $elementCitation) as $journalArticlePubId) {
        if ($journalArticlePubId->getAttribute("pub-id-type") == "doi") {
            if (strpos($journalArticlePubId->nodeValue, "http") !== false) {
                $bibitemJournal->setDoi($journalArticlePubId->nodeValue);
            } else {
                $bibitemJournal->setDoi("https://doi.org/" . trim($journalArticlePubId->nodeValue));
            }
        } elseif ($journalArticlePubId->getAttribute("pub-id-type") == "pmid") {
            if (strpos($journalArticlePubId->nodeValue, "http") !== false) {
                $bibitemJournal->setPmid(trim($journalArticlePubId->nodeValue));
            } else {
                $bibitemJournal->setPmid("https://www.ncbi.nlm.nih.gov/pubmed/" . trim($journalArticlePubId->nodeValue));
            }
        }
    }
}

/**
 * @param DOMXPath $xpath
 * @param DOMElement $elementCitation
 * @param BibitemJournal|BibitemChapter|BibitemBook|BibitemConf $bibitemJournal
 */
function parsingNamesAndCollab(DOMXPath $xpath, DOMElement $elementCitation, $bibitemJournal)
{
    $authorNamesNodes = $xpath->evaluate("person-group/name", $elementCitation);
    if ($authorNamesNodes != null) {
        foreach ($authorNamesNodes as $authorNameNode) {
            $bibName = new BibName();
            $bibitemJournal->getName()->offsetSet(null, $bibName);
            foreach ($xpath->evaluate("surname", $authorNameNode) as $surnameNode) {
                $bibName->setSurname($surnameNode->nodeValue);
            }
            foreach ($xpath->evaluate("given-names", $authorNameNode) as $givenNamesNode) {
                $givenNamesText = trim($givenNamesNode->nodeValue);

                /* check if upper case; if true treat as initials */
                if (ctype_upper($givenNamesText) == true) {
                    $bibName->setInitials(str_split($givenNamesText));
                } else {
                    $bibName->setGivenname($givenNamesText);
                }
            }
        }
    }
    $authorCollabNodes = $xpath->evaluate("person-group/collab", $elementCitation);
    if ($authorCollabNodes != null) {
        foreach ($authorCollabNodes as $authorCollabNode) {
            $bibitemJournal->setCollab(trim($authorCollabNode->nodeValue));
        }
    }
}