<?php

function testBody($sections)
{
    foreach ($sections as $sect) {
        echo $sect->getTitle(), "\n";
        foreach ($sect->getContent() as $secCont) {

            if (get_class($secCont) == "ParContent") {

                foreach ($secCont as $parCont) {
                    if (get_class($parCont) == "ParText") {
                        echo $parCont->getContent();
                    } elseif (get_class($parCont) == "Xref") {
                        echo "Citation: ", $parCont->getContent();
                    } elseif (get_class($parCont) == "XrefFig") {
                        echo "Figure: ", $parCont->getContent();
                    } elseif (get_class($parCont) == "XrefTable") {
                        echo "Table: ", $parCont->getContent();
                    } elseif (get_class($parCont) == "Italic") {
                        echo "<i>", $parCont->getContent(), "</i>";
                    } elseif (get_class($parCont) == "Bold") {
                        echo "<b>", $parCont->getContent(), "</b>";
                    }
                }
            } elseif (get_class($secCont) == "Lists") {
                echo "List: ", $secCont->getType(), "\n";
                foreach ($secCont->getContent() as $item) {
                    echo $item, "\n";
                }
            } elseif (get_class($secCont) == "Fig") {
                echo "\n", $secCont->getLabel(), "\n";
            } elseif (get_class($secCont) == "Section") {
                echo $secCont->getTitle(),"\n";
                foreach ($secCont->getContent() as $secCont1) {
                    if (get_class($secCont1) == "ParContent") {
                        foreach ($secCont1 as $parCont) {
                            if (get_class($parCont) == "ParText") {
                                echo $parCont->getContent();
                            } elseif (get_class($parCont) == "Xref") {
                                echo "Citation: ", $parCont->getContent();
                            } elseif (get_class($parCont) == "XrefFig") {
                                echo "Figure: ", $parCont->getContent();
                            } elseif (get_class($parCont) == "XrefTable") {
                                echo "Table: ", $parCont->getContent();
                            } elseif (get_class($parCont) == "Italic") {
                                echo "<i>", $parCont->getContent(), "</i>";
                            } elseif (get_class($parCont) == "Bold") {
                                echo "<b>", $parCont->getContent(), "</b>";
                            }
                        }
                    } elseif (get_class($secCont1) == "Lists") {
                        echo "List: ", $secCont1->getType(), "\n";
                        foreach ($secCont1->getContent() as $item) {
                            echo $item, "\n";
                        }
                    }
                }
            }
        }
    }
}