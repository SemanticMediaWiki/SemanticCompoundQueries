# Semantic Compound Queries

[![Build Status](https://secure.travis-ci.org/SemanticMediaWiki/SemanticCompoundQueries.svg?branch=master)](http://travis-ci.org/SemanticMediaWiki/SemanticCompoundQueries)
[![Code Coverage](https://scrutinizer-ci.com/g/SemanticMediaWiki/SemanticCompoundQueries/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/SemanticMediaWiki/SemanticCompoundQueries/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/SemanticMediaWiki/SemanticCompoundQueries/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/SemanticMediaWiki/SemanticCompoundQueries/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/mediawiki/semantic-compound-queries/version.png)](https://packagist.org/packages/mediawiki/semantic-compound-queries)
[![Packagist download count](https://poser.pugx.org/mediawiki/semantic-compound-queries/d/total.png)](https://packagist.org/packages/mediawiki/semantic-compound-queries)

Semantic Compound Queries (a.k.a. SCQ) is a [Semantic Mediawiki][smw] extension that defines the `#compound_query` parser function which can display results of multiple `#ask` queries (as compound construct) at the same time.

## Requirements

- PHP 5.6 or later
- MediaWiki 1.27 or later
- [Semantic MediaWiki][smw] 3.0 or later

## Installation

The recommended way to install Semantic Compound Queries is using [Composer](http://getcomposer.org) with
[MediaWiki's built-in support for Composer](https://www.mediawiki.org/wiki/Composer).

Note that the required extension Semantic MediaWiki must be installed first according to the installation
instructions provided.

### Step 1

Change to the base directory of your MediaWiki installation. This is where the "LocalSettings.php"
file is located. If you have not yet installed Composer do it now by running the following command
in your shell:

    wget https://getcomposer.org/composer.phar

### Step 2
    
If you do not have a "composer.local.json" file yet, create one and add the following content to it:

```
{
	"require": {
		"mediawiki/semantic-compound-queries": "~2.0"
	}
}
```

If you already have a "composer.local.json" file add the following line to the end of the "require"
section in your file:

    "mediawiki/semantic-compound-queries": "~2.0"

Remember to add a comma to the end of the preceding line in this section.

### Step 3

Run the following command in your shell:

    php composer.phar update --no-dev

Note if you have Git installed on your system add the `--prefer-source` flag to the above command. Also
note that it may be necessary to run this command twice. If unsure do it twice right away.

### Step 4

Add the following line to the end of your "LocalSettings.php" file:

    wfLoadExtension( 'SemanticCompoundQueries' );

### Verify installation success

As final step, you can verify SCI got installed by looking at the "Special:Version" page on your wiki and
check that it is listed in the semantic extensions section.

## Usage

The syntax of `#compound_query` resembles that of `#ask`, but with more than one query, and with the elements
of each sub-query delimited by semicolons instead of pipes. Elements that are common across all sub-queries,
like `format=` and `width=` (for maps) should be placed after all sub-queries.

### Example

A sample call to `#compound query`, which retrieves both biographies, along
with their subject; and fiction books, along with their author; is:

```
{{#compound_query:
  [[Category:Books]]
  [[Has genre::Biography]]
  ;?Covers subject=Subject
  |
  [[Category:Books]]
  [[Has genre::Fiction]]
  ;?Has author=Author
  |format=list
}}
```

For more information, see the extension's homepage at [MediaWiki.org][homepage].

## Contribution and support

Original author: Yaron Koren (Version 0.4.1)

If you want to contribute work to the project please subscribe to the developers mailing list and
have a look at the contribution guideline.

* [File an issue](https://github.com/SemanticMediaWiki/SemanticCompoundQueries/issues)
* [Submit a pull request](https://github.com/SemanticMediaWiki/SemanticCompoundQueries/pulls)
* Ask a question on [the mailing list](https://www.semantic-mediawiki.org/wiki/Mailing_list)
* Ask a question on the #semantic-mediawiki IRC channel on Freenode.

## Tests

This extension provides unit and integration tests that are run by a [continues integration platform][travis]
but can also be executed using `composer phpunit` from the extension base directory.

## License

[GNU General Public License, version 2 or later][gpl-licence].

[smw]: https://github.com/SemanticMediaWiki/SemanticMediaWiki
[contributors]: https://github.com/SemanticMediaWiki/SemanticCompoundQueries/graphs/contributors
[travis]: https://travis-ci.org/SemanticMediaWiki/SemanticCompoundQueries
[gpl-licence]: https://www.gnu.org/copyleft/gpl.html
[composer]: https://getcomposer.org/
[homepage]: https://www.mediawiki.org/wiki/Extension:Semantic_Compound_Queries
