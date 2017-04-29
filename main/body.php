<?php
require ("../classes/Section.php");
require ("../classes/ParContent.php");
require ("../classes/ParText.php");
require ("../classes/Xref.php");
require ("../classes/Lists.php");
require ("../classes/Italic.php");
require ("../classes/XrefFig.php");
require ("../classes/XrefTable.php");
require ("../classes/Bold.php");
require ("../classes/Fig.php");

function Body($xpath): ArrayObject
{
    $sections = new ArrayObject();
    foreach ($xpath->evaluate("/article/body/sec") as $sec) {
        //echo "\n";
        $section = new Section();
        $sections->append($section);
        foreach ($xpath->evaluate("title|p|fig|sec|table-wrap|list", $sec) as $secContent) {
            if ($secContent->tagName == "title") {
                //echo $secContent->nodeValue, "\n";
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
                $list = new Lists();
                if ($secContent->getAttribute("list-type") == "ordered") {
                    $list->setType("ordered");
                } elseif ($secContent->getAttribute("list-type") == "unordered") {
                    $list->setType("unordered");
                }
                foreach ($xpath->evaluate("list-item/p", $secContent) as $listItem) {
                    $listItems[]= trim($listItem->nodeValue);
                    $list->setContent($listItems);
                }
                $section->getContent()->offsetSet(null, $list);
            } else if ($secContent->tagName == "p") {
                $paragraphContent = new ParContent();
                $paragraphContent->setType("paragraph");
                $section->getContent()->offsetSet(null, $paragraphContent);
                //echo "\n";
                foreach ($secContent->childNodes as $parContent) {
                    if ($parContent->nodeType == XML_TEXT_NODE) {
                        //echo $parContent->nodeValue;
                        $parText = new ParText();
                        $parText->setContent(trim($parContent->nodeValue));
                        $paragraphContent->getContent()->offsetSet(null, $parText);
                    } else if ($parContent->tagName == "xref") {
                        if ($parContent->getAttribute("ref-type") == "bibr") {
                            $ref = new Xref();
                            $ref->setContent($parContent->nodeValue);
                            $paragraphContent->getContent()->offsetSet(null, $ref);
                            //echo "Citation: ", $ref->getContent();
                        } else if ($parContent->getAttribute("ref-type") == "table") {
                            $ref = new XrefTable();
                            $ref->setContent($parContent->nodeValue);
                            $paragraphContent->getContent()->offsetSet(null, $ref);
                           // echo "Table: ", $ref->getContent();
                        } else if ($parContent->getAttribute("ref-type") == "fig") {
                            $ref = new XrefFig();
                            $ref->setContent($parContent->nodeValue);
                            $paragraphContent->getContent()->offsetSet(null, $ref);
                           // echo "Figure: ", $ref->getContent();
                        }
                    } else if ($parContent->tagName == "italic") {
                        $italic = new Italic();
                        $italic->setContent(trim($parContent->nodeValue));
                        $paragraphContent->getContent()->offsetSet(null, $italic);
                        //echo "<i>", $italic->getContent(), "</i>";
                    } else if ($parContent->tagName == "bold") {
                        $bold = new Bold();
                        $bold->setContent($parContent->nodeValue);
                        $paragraphContent->getContent()->offsetSet(null, $bold);
                        //echo "<b>", $bold->getContent(), "</b>";
                    }
                }

            } else if ($secContent->tagName == "sec") {
                $subSection = new Section();
                $section->getContent()->offsetSet(null, $subSection);
                //echo "\n";
                foreach ($xpath->evaluate("title|p|fig|sec|table-wrap|list", $secContent) as $subSecContent) {
                    if ($subSecContent->tagName == "title") {
                        $subSection->setTitle($subSecContent->nodeValue);
                        //echo $subSecContent->nodeValue;
                        $subSection->getContent()->offsetSet(null, $list);
                    } else if ($subSecContent->tagName == "list") {
                        $list = new Lists();

                       // echo "List will appear next \n";
                        if ($subSecContent->getAttribute("list-type") == "ordered") {
                            $list->setType("ordered");
                        } elseif ($subSecContent->getAttribute("list-type") == "unordered") {
                            $list->setType("unordered");
                        }
                        foreach ($xpath->evaluate("list-item/p", $secContent) as $listItem) {
                            $listItems1[]= trim($listItem->nodeValue);
                            $list->setContent($listItems1);
                        }

                    } else if ($subSecContent->tagName == "p") {
                        //echo "\n";
                        $paragraphContent = new ParContent();
                        $subSection->getContent()->offsetSet(null, $paragraphContent);
                        foreach ($subSecContent->childNodes as $parContent) {
                            if ($parContent->nodeType == XML_TEXT_NODE) {
                                $parText = new ParText();
                                $parText->setContent($parContent->nodeValue);
                                $paragraphContent->getContent()->offsetSet(null, $parText);
                            } else if ($parContent->tagName == "xref") {
                                if ($parContent->getAttribute("ref-type") == "bibr") {
                                    $ref = new Xref();
                                    $ref->setContent($parContent->nodeValue);
                                    $paragraphContent->getContent()->offsetSet(null, $ref);
                                } else if ($parContent->getAttribute("ref-type") == "table") {
                                    $ref = new XrefTable();
                                    $ref->setContent($parContent->nodeValue);
                                    $paragraphContent->getContent()->offsetSet(null, $ref);
                                } else if ($parContent->getAttribute("ref-type") == "fig") {
                                    $ref = new XrefFig();
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
                }
            }
        }
    }
    return $sections;
}