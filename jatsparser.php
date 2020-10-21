<?php

require_once __DIR__ . '/vendor/autoload.php';

use JATSParser\Body\Document as JATSDocument;
use JATSParser\HTML\Document as HTMLDocument;

$inputPath = null;
$outputPath = null;
if ($argc == 3) {
	$inputPath = $argv[1];
	$outputPath = $argv[2];
	$citStyle = null;
} elseif($argc == 4) {
	$inputPath = $argv[1];
	$outputPath = $argv[2];
	$citStyle = $argv[3];
} else {
	throw new InvalidArgumentException("requires valid input and output paths" . "\n" .
		"Basic usage: php jatsparser.php [path/to/file.xml or path/to/input/dir] [path/to/output/file.html or path/to/output/dir] [citation style format name]" ."\n");
}

$inputPathParts = pathinfo($inputPath);
if (array_key_exists("extension", $inputPathParts) && $inputPathParts["extension"] == "xml") {
	$inputs["singleFile"] = $inputPath;
} elseif (is_dir($inputPath)) {
	$inputs = scandir($inputPath);
} else {
	throw new UnexpectedValueException("the input must be a file with extension .xml or existing directory");
}

$outputPathParts = pathinfo($outputPath);

if (!array_key_exists("extension", $outputPathParts)) {
	$outputDir = $outputPath;
} else {
	$outputDir = $outputPathParts["dirname"];
}

$outputDir = rtrim($outputDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

if (!is_dir($outputDir)) {
	mkdir($outputDir, 0777, true);
}

if (array_key_exists("singleFile", $inputs)) {
	writeOutput($inputPath, $outputPathParts, $inputPathParts, $outputDir, false, $citStyle);
} else {
	foreach ($inputs as $input) {
		$inputFilePath = rtrim($inputPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $input;
		$inputFilePathParts = pathinfo($inputFilePath);
		if (array_key_exists("extension", $inputFilePathParts) && $inputFilePathParts["extension"] == "xml") {
			writeOutput($inputFilePath, $outputPathParts, $inputFilePathParts, $outputDir, true, $citStyle);
		}
	}
}

/**
 * @param $inputFilePath string the path to the input file
 * @param $outputPathParts array from pathinfo()
 * @param $inputPathParts array from pathinfo
 * @param $outputDir string directory where to write file(s)
 * @param $isDir bool is the input path dir or single file?
 * @param $citStyle null|string the name of citation style format, see https://github.com/citation-style-language/styles:
 */
function writeOutput(string $inputFilePath, array $outputPathParts, array $inputPathParts, string $outputDir, bool $isDir, ?string $citStyle): void
{
	$jatsDocument = new JATSDocument($inputFilePath);
	$htmlDocument = new HTMLDocument($jatsDocument);

	if (array_key_exists("extension", $outputPathParts) && !$isDir) {
		$filename = $outputPathParts["filename"];
	} else {
		$filename = $inputPathParts["filename"];
	}

	if ($citStyle) {
		$htmlDocument->setReferences($citStyle, 'en-US', true);
	}

	if (!$isDir) {
		$htmlDocument->saveAsValidHTMLFile($outputDir . $filename . ".html", "HTML Document");
	} else {
		if (!is_dir($outputDir . $filename)) {
			mkdir($outputDir . $filename);
		}
		$htmlDocument->saveAsValidHTMLFile($outputDir . $filename . DIRECTORY_SEPARATOR . $filename . ".html", "HTML Document");
	}
}

