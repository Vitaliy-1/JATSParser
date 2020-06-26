<?php

require_once __DIR__ . '/../vendor/autoload.php';

use JATSParser\Body\Document as JATSDocument;
use JATSParser\HTML\Document as HTMLDocument;
use JATSParser\PDF\TCPDFDocument;

/*
 * @var $jatsDocument JATSDocument object representation of JATS XML document
 */
$jatsDocument = new JATSDocument("example.xml");

/*
 * @var $htmlDocument HTMLDocument conversion to HTML
 */
$htmlDocument = new HTMLDocument($jatsDocument);

//the first parameter is citation style format: https://github.com/citation-style-language/styles
$htmlDocument->setReferences('apa', 'en-US', true);

$htmlDocument->saveAsValidHTMLFile('example.html', 'Arbitrary HTML document title', true);

/*
 * @var $pdfDocument TCPDFDocument class that extends TCDPF
 */
$pdfDocument = new TCPDFDocument();
$pdfDocument->AddPage();
$pdfDocument->SetFont('dejavusans');
$htmlString = $htmlDocument->getHtmlForTCPDF();
$pdfHeaderLogo = __DIR__ . "/../logo/logo.jpg";
$pdfDocument->SetHeaderData($pdfHeaderLogo, PDF_HEADER_LOGO_WIDTH, "Some Text Here", "Another text here");
$pdfDocument->writeHTML($htmlString, true, false, true, false, '');
$pdfDocument->Output(__DIR__ . '/example.pdf', 'F');

