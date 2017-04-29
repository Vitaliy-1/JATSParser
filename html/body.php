<?php

/**
 * @param $html
 * @param $sections
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
            foreach ($sect->getContent() as $secCont) {
                if (get_class($secCont) == "ParContent" && $secCont->getType() == "paragraph") {
                    $pForSections = $html->createElement("div");
                    $pForSections->setAttribute("class", "for-sections");
                    $divPanelBody->appendChild($pForSections);
                    foreach ($secCont as $parCont) {
                        if (get_class($parCont) == "ParText") {
                            $parTextNode = $html->createTextNode($parCont->getContent());
                            $pForSections->appendChild($parTextNode);
                        }
                    }
                }
            }
        }
    }
}