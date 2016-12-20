# Semantic Compound Queries

[![Build Status](https://secure.travis-ci.org/SemanticMediaWiki/SemanticCompoundQueries.svg?branch=master)](http://travis-ci.org/SemanticMediaWiki/SemanticCompoundQueries)
[![Code Coverage](https://scrutinizer-ci.com/g/SemanticMediaWiki/SemanticCompoundQueries/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/SemanticMediaWiki/SemanticCompoundQueries/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/SemanticMediaWiki/SemanticCompoundQueries/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/SemanticMediaWiki/SemanticCompoundQueries/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/mediawiki/semantic-compound-queries/version.png)](https://packagist.org/packages/mediawiki/semantic-compound-queries)
[![Packagist download count](https://poser.pugx.org/mediawiki/semantic-compound-queries/d/total.png)](https://packagist.org/packages/mediawiki/semantic-compound-queries)
[![Dependency Status](https://www.versioneye.com/php/mediawiki:semantic-compound-queries/badge.png)](https://www.versioneye.com/php/mediawiki:semantic-compound-queries)

Semantic Compound Queries (a.k.a. SCQ) is a [Semantic Mediawiki][smw] extension that defines the `#compound_query` parser function which can display results of multiple `#ask` queries (as compound construct) at the same time.

## Requirements

- PHP 5.3.2 or later
- MediaWiki 1.23 or later
- [Semantic MediaWiki][smw] 2.1 or later

## Installation

The recommended way to install Semantic Compound Queries is by using [Composer][composer] with an entry in MediaWiki's `composer.json` or alternatively `composer.local.json`.

```json
{
	"require": {
		"mediawiki/semantic-compound-queries": "~1.0"
	}
}
```
1. From your MediaWiki installation directory, execute  
   `composer require mediawiki/semantic-compound-queries:~1.0`
2. Navigate to _Special:Version_ on your wiki and verify that the extension
   has been successfully installed.
   
Note that the required extension Semantic MediaWiki must be installed first according to the installation
instructions provided with it.

## Usage

The syntax of `#compound_query` resembles that of `#ask`, but with more than
one query, and with the elements of each sub-query delimited by semicolons
instead of pipes. Elements that are common across all sub-queries, like
`format=` and `width=` (for maps) should be placed after all sub-queries.

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
