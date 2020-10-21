# JATSParser
JATSParser is aimed to be integrated with Open Journal Systems 3.0+ for transforming JATS XML to various formats
## Usage
* Install composer dependencies
* See [example.php](examples/example.php)
* Doesn't deal with JATS XML metadata as it by design it should be transfered from OJS
* Transforms JATS to HTML and PDF, uses TCPDF for the latter conversion
* Has dependency from citeproc-php for support for different citation style formats 

### Cli usage
Clone the project and enter it:
```
git clone https://github.com/Vitaliy-1/JATSParser.git
cd JATSParser
```
Usage (requires php-cli 7.3 or higher):
```
php jatsparser.php examples/example.xml examples/example.html vancouver
```
* first argument (examples/example.xml) -  path to the JATS XML file or folder that contains JATS XML files for conversion; folder must exist
* second argument (examples/example.html) - path to the output HTML file or folder; folder must exist
* third argument (vancouver) - citation syle format, see the full list of styles available: https://github.com/citation-style-language/styles; don't specify trailing csl extension
