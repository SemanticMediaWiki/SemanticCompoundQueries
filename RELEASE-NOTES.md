These are the RELEASE NOTES for the **Semantic Compound Queries** (a.k.a. SCQ) extension.

## Semantic Compound Queries 2.0.0

Released on January 29, 2019.

* Minimum requirement for Semantic MediaWiki changed to version 3.0 and later
* #39 Adds support for extension registration via "extension.json"  
  → Now you have to use `wfLoadExtension( 'SemanticCompoundQueries' );` in the "LocalSettings.php" file to invoke the extension
* Localization updates from https://translatewiki.net

## Semantic Compound Queries 1.2.0

Released on Oktober 8, 2018

* Minimum requirement for
  * PHP changed to version 5.6 and later
  * MediaWiki changed to version 1.27 and later
  * Semantic MediaWiki changed to version 2.5 and later
* Localization updates from https://translatewiki.net

## Semantic Compound Queries 1.1.0

Released on June 24, 2017.

* Minimum requirement for
  * PHP changed to version 5.5 and later
  * Semantic MediaWiki changed to version 2.4 and later
* Made general repository cleanup ... continued
* Localization updates from https://translatewiki.net

## Semantic Compound Queries 1.0.1

Released on December 21, 2016

* Fixed issue with the extension not loading properly

## Semantic Compound Queries 1.0.0

Released on September 21, 2016.

* Minimum requirement for
  * PHP changed to version 5.3 and later
  * MediaWiki changed to version 1.23 and later
  * Semantic MediaWiki changed to version 2.1 and later
* Added `compoundquery` API endpoint
* Added `unsorted` parameter
* Added composer support
* Made general repository cleanup
* Added tests

## Semantic Compound Queries 0.4.1

Released on June 15, 2016.

* Replaced deprecated wfMsgForContent() call; other small fixes

## Semantic Compound Queries 0.4.0

Released on May 26, 2014.

* i18n messages moved into JSON files

## Semantic Compound Queries 0.3.4

Released on November 19, 2012.

* Fixed handling for identical page names in different namespaces

## Semantic Compound Queries 0.3.3

Released on October 22, 2012.

* Added support for SMW 1.8

## Semantic Compound Queries 0.3.2

Released on March 27, 2012.

* Fixed handling for the display of multiple properties
* Removed support for MW 1.15 and earlier
* Removed support for SMW 1.5

## Semantic Compound Queries 0.3.1

Released on January 9, 2012.

* Fixed compatibility for PHP < 5.3

## Semantic Compound Queries 0.3.0

Released on October 4, 2011.

* Made results now sorting by page name
* Added further support for SMW 1.6.2

## Semantic Compound Queries 0.2.10

Released on September 20, 2011.

* Added support for SMW 1.6.2

## Semantic Compound Queries 0.2.9

Released on August 23, 2011.

* Removed support for MW 1.13 and earlier
* Removed support for SMW 1.4 and earlier
* Made small fixes

## Semantic Compound Queries 0.2.8

Released on July 25, 2011.

* Added support for SMW 1.6

## Semantic Compound Queries 0.2.7

Released on April 4, 2011.

* Fixed the display of map icons
* Added support for HipHop

## Semantic Compound Queries 0.2.6

Released on September 23, 2010.

* Made improvements to PHP syntax
* Made minor fix for SMW < 1.5

## Semantic Compound Queries 0.2.5

Released on May 13, 2010.

* Fixed handling of query format aliases

## Semantic Compound Queries 0.2.4

Released on March 11, 2010.

* Fixed handling for extra whitespace in queries
* Removed duplication of values for SMW 1.5

## Semantic Compound Queries 0.2.3

Released on October 13, 2009.

* Added support for SMW 1.5
* Removed support for MW 1.11 and earlier

## Semantic Compound Queries 0.2.2

Released on June 4, 2009.

* Added internationalization for extension description

## Semantic Compound Queries 0.2.1

Released on December 1, 2008.

* Fixed handling of map icons with MW 1.12 or earlier

## Semantic Compound Queries 0.2.0

Released on November 25, 2008.

* Moved two defined classes to their own files
* Fixed handling of semicolons contained within query filters

## Semantic Compound Queries 0.1.0

Released on November 19, 2008.

* Initial version
