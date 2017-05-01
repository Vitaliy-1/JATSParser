<?php

/**
 * @param $html -> our html as DOM object
 * @param $sections -> sections of our article as ArrayObject
 */
function htmlBodyStructure($html, $sections)
{
    $path = new DOMXPath($html);
    $divArticletexts = $path->evaluate("/html/body/main/div/div/div/div/div/div[@class='article-text']");
    foreach ($divArticletexts as $divArticletext) {
        foreach ($sections as $sect) {
            $divPanwrap = $html->createElement("div");
            $divPanwrap->setAttribute("class", "panwrap");
            $divArticletext->appendChild($divPanwrap);

            $divSection = $html->createElement("div");
            $divSection->setAttribute("class", "section");
            $divPanwrap->appendChild($divSection);

            $hTitle = $html->createElement("h2", $sect->getTitle());
            $hTitle->setAttribute("class", "title");
            $divSection->appendChild($hTitle);

            $divForpan = $html->createElement("div");
            $divForpan->setAttribute("class", "forpan");
            $divPanwrap->appendChild($divForpan);

            $divPanelBody = $html->createElement("div");
            $divPanelBody->setAttribute("class", "panel-body");
            $divForpan->appendChild($divPanelBody);
            sectionWriting($html, $sect, $divPanelBody);
        }
    }
}

/**
 * @param $html
 * @param $sect -> single section of our article
 * @param $divPanelBody
 */
function sectionWriting($html, $sect, $divPanelBody)
{
    foreach ($sect->getContent() as $secCont) {
        if (get_class($secCont) == "ParContent" && $secCont->getType() == "paragraph") {
            $pForSections = $html->createElement("p");
            $pForSections->setAttribute("class", "for-sections");
            $divPanelBody->appendChild($pForSections);
            foreach ($secCont as $parCont) {
                paragraphWriting($html, $parCont, $pForSections);
            }
        } elseif ((get_class($secCont) == "ParContent") && (($secCont->getType() == "list-ordered") || ($secCont->getType() == "list-unordered"))) {
            if ($secCont->getType() == "list-ordered") {
                $pForSections = $html->createElement("ol");
                $pForSections->setAttribute("class", "intext2");
                $divPanelBody->appendChild($pForSections);
            } elseif ($secCont->getType() == "list-unordered") {
                $pForSections = $html->createElement("ul");
                $pForSections->setAttribute("class", "intext1");
                $divPanelBody->appendChild($pForSections);
            }
            foreach ($secCont->getContent() as $parCont) {
                $liInside = $html->createElement("li");
                $pForSections->appendChild($liInside);
                $pInsideLi = $html->createElement("p");
                $pInsideLi->setAttribute("class", "inlist");
                $liInside->appendChild($pInsideLi);
                paragraphWriting($html, $parCont, $pInsideLi);
            }
        } elseif (get_class($secCont) == "ArrayObject") {
            foreach ($secCont as $subsec) {

                /* check section type */
                if ($subsec->getType() == "sub") {
                    $divSubSection = $html->createElement("div");
                    $divSubSection->setAttribute("class", "subsection");
                    $divPanelBody->appendChild($divSubSection);

                    $hTitle = $html->createElement("h3", $subsec->getTitle());
                    $hTitle->setAttribute("class", "subtitle");
                    $divSubSection->appendChild($hTitle);

                    /* Recursion for parsing subSections */
                    sectionWriting($html, $subsec, $divSubSection);

                } elseif ($subsec->getType() == "subsub") {
                    $divSubSubSection = $html->createElement("div");
                    $divSubSubSection->setAttribute("class", "subsubsection");
                    $divSubSection->appendChild($divSubSubSection);

                    $hTitle = $html->createElement("h4", $subsec->getTitle());
                    $hTitle->setAttribute("class", "subsubtitle");
                    $divSubSubSection->appendChild($hTitle);

                    /* Recursion for parsing subsubSections */
                    sectionWriting($html, $subsec, $divSubSubSection);
                }
            }
        }
    }
}

/**
 * @param $html
 * @param $parCont
 * @param $pForSections
 */
function paragraphWriting($html, $parCont, $pForSections)
{
    if (get_class($parCont) == "ParText") {
        $parTextNode = $html->createTextNode($parCont->getContent());
        $pForSections->appendChild($parTextNode);
    } elseif (get_class($parCont) == "Xref") {
        $parXrefNode = $html->createElement("a", $parCont->getContent());
        $parXrefNode->setAttribute("class", "ref-tip btn btn-info");
        $parXrefNode->setAttribute("rid", $parCont->getRid());
        $pForSections->appendChild($parXrefNode);
    } elseif (get_class($parCont) == "XrefFig") {
        $parXrefNode = $html->createElement("a", $parCont->getContent());
        $parXrefNode->setAttribute("href", "#" . $parCont->getRid());
        $parXrefNode->setAttribute("class", "reffigure");
        $pForSections->appendChild($parXrefNode);
    } elseif (get_class($parCont) == "XrefTable") {
        $parXrefNode = $html->createElement("a", $parCont->getContent());
        $parXrefNode->setAttribute("href", "#" . $parCont->getRid());
        $parXrefNode->setAttribute("class", "reftable");
        $pForSections->appendChild($parXrefNode);
    } elseif (get_class($parCont) == "Italic") {
        $parXrefNode = $html->createElement("i", $parCont->getContent());
        $pForSections->appendChild($parXrefNode);
    } elseif (get_class($parCont) == "Bold") {
        $parXrefNode = $html->createElement("b", $parCont->getContent());
        $pForSections->appendChild($parXrefNode);
    }
}