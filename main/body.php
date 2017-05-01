<?php
require ("../classes/Section.php");
require ("../classes/ParContent.php");
require ("../classes/ParText.php");
require ("../classes/Xref.php");
require ("../classes/Italic.php");
require ("../classes/XrefFig.php");
require ("../classes/XrefTable.php");
require ("../classes/Bold.php");
require ("../classes/Fig.php");

function Body($xpath): ArrayObject
{
    $sections = new ArrayObject();
    $subsections = new ArrayObject();
    $subsubsections = new ArrayObject();
    foreach ($xpath->evaluate("/article/body/sec") as $sec) {
        sectionParsing($xpath, $sec, $sections, $subsections, $subsubsections);
    }
    return $sections;
}

/**
 * @param $xpath
 * @param $sec -> our section DOM Node
 * @param $sections -> our section as ArrayObject
 * @param $subsections - our subsection as ArrayObject
 * @param $subsubsections - our subsubsection as ArrayObject
 */
function sectionParsing($xpath, $sec, $sections, $subsections, $subsubsections)
{
    $section = new Section();
    $ifSubSecs = $xpath->evaluate("parent::sec", $sec);
    $ifSubSubSecs = $xpath->evaluate("parent::sec/parent::sec", $sec);
    foreach ($ifSubSecs as $ifSubSec) {
    }
    foreach ($ifSubSubSecs as $ifSubSubSec) {

    }
    if ($ifSubSec == null) {
        $section->setType("sec");
        $sections->append($section);
    } elseif ($ifSubSec != null && $ifSubSubSec == null) {
        $section->setType("sub");
        $subsections->append($section);
    } elseif ($ifSubSec != null && $ifSubSubSec != null) {
        $section->setType("subsub");
        $subsections->append($section);
    }

    foreach ($xpath->evaluate("title|p|fig|sec|table-wrap|list", $sec) as $secContent) {

        if ($secContent->tagName == "title") {
            $section->setTitle(trim($secContent->nodeValue));
        } elseif ($secContent->tagName == "fig") {
            $fig = new Fig();
            $section->getContent()->offsetSet(null, $fig);
            $fig->setId($secContent->getAttribute("id"));
            foreach ($xpath->evaluate("label", $secContent) as $label) {
                $fig->setLabel($label->nodeValue);
            }
            foreach ($xpath->evaluate("caption/title", $secContent) as $title) {
                $fig->setTitle($title->nodeValue);
            }
            foreach ($xpath->evaluate("caption/p", $secContent) as $caption) {
                $fig->setCaption($caption->nodeValue);
            }
            foreach ($xpath->evaluate("graphic", $secContent) as $graphic) {
                $fig->setHref($graphic->getAttribute("xlink:href"));
            }
        } elseif ($secContent->tagName == "list") {
            $listContent = new ParContent();

            if ($secContent->getAttribute("list-type") == "ordered") {
                $listContent->setType("list-ordered");
            } elseif ($secContent->getAttribute("list-type") == "unordered") {
                $listContent->setType("list-unordered");
            }
            foreach ($xpath->evaluate("list-item/p", $secContent) as $listItem) {
                paragraphParsing($listItem, $listContent);
            }
            $section->getContent()->offsetSet(null, $listContent);
        } elseif ($secContent->tagName == "p") {
            $paragraphContent = new ParContent();
            $paragraphContent->setType("paragraph");
            $section->getContent()->offsetSet(null, $paragraphContent);
            paragraphParsing($secContent, $paragraphContent);

        } elseif ($secContent->tagName == "sec") {
            if ($section->getType() == "sec") {
                $section->getContent()->offsetSet(0, $subsections);
            }
            if ($section->getType() == "sub") {
                $section->getContent()->offsetSet(0, $subsubsections);
            }

            /* Recursion for parsing subsections and subsubsection from XML */

            sectionParsing($xpath, $secContent, $sections, $subsections, $subsubsections);
        }
    }
}

/**
 * @param $secContent -> XML section Node content
 * @param $paragraphContent -> XML paragraph Node content
 */
function paragraphParsing($secContent, $paragraphContent)
{
    foreach ($secContent->childNodes as $parContent) {
        if ($parContent->nodeType == XML_TEXT_NODE) {
            $parText = new ParText();
            $parText->setContent($parContent->nodeValue);
            $paragraphContent->getContent()->offsetSet(null, $parText);
        } else if ($parContent->tagName == "xref") {
            if ($parContent->getAttribute("ref-type") == "bibr") {
                $ref = new Xref();
                $ref->setRid($parContent->getAttribute("rid"));
                $ref->setContent($parContent->nodeValue);
                $paragraphContent->getContent()->offsetSet(null, $ref);
            } else if ($parContent->getAttribute("ref-type") == "table") {
                $ref = new XrefTable();
                $ref->setRid($parContent->getAttribute("rid"));
                $ref->setContent($parContent->nodeValue);
                $paragraphContent->getContent()->offsetSet(null, $ref);
            } else if ($parContent->getAttribute("ref-type") == "fig") {
                $ref = new XrefFig();
                $ref->setRid($parContent->getAttribute("rid"));
                $ref->setContent($parContent->nodeValue);
                $paragraphContent->getContent()->offsetSet(null, $ref);
            }
        } else if ($parContent->tagName == "italic") {
            $italic = new Italic();
            $italic->setContent(trim($parContent->nodeValue));
            $paragraphContent->getContent()->offsetSet(null, $italic);
        } else if ($parContent->tagName == "bold") {
            $bold = new Bold();
            $bold->setContent($parContent->nodeValue);
            $paragraphContent->getContent()->offsetSet(null, $bold);
        }
    }
}