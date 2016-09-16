Solr Fluid Widget
=================

> TYPO3 Extension to render an AJAX-enabled Solr search field as either plugin or Fluid Widget.

## Introduction

This extension is able to dispatch a search query to a Solr server configured in TYPO3. It uses `EXT:solr` for all communication
with the Solr backend server. A default result display strategy which uses Twitter Bootstrap's `tooltip` and `popover` methods
is implemented (you can easily replace this with any approach you like) and depends on jQuery for AJAX and event listening.

## Installation

Download extension files, use Extension Manager or manually place the files in your extensions folder. Load static TypoScript
provided by the extension - and make sure it is available on the page where you want to place the plugin or Fluid Widget.

## Integration

Integrating the extension consists of only a few standard tasks:

1. Replace the template file used by the plugin and widget.
2. Confirm any Solr search filters etc. you need have been added in solr's TypoScript.
3. Optionally, plug in a Javascript method used to display results.
4. Optionally, plug in additional QueryProviders for extended results

Each is described in more detail below.

### Replacing template files

The main template file used by this Widget is `Widget/Index` under the `Resources/Private/Templates` folder which by default is
in the `templateRootPath` set in `plugin.tx_solrwidget.view.templateRootPath`. The plugin internally uses the Widget and the
Widget will use this template. This template then (as a default approach) renders the Partial template `Widget.html` which has
the actual HTML for the Widget.

In almost all use cases you will only have to override one piece of TS configuration:

```
plugin.tx_solrwidget.view.partialRootPath = EXT:myext/Resources/Private/Partials
```

This means that the directory `EXT:myext/Resources/Private/Partials/Widget.html` should contain a copy of the original file
`EXT:solrwidget/Resources/Private/Partials/Widget.html` which you then modify to your needs.

Inside the template file is placed a div with class `result-template` - if it exists, its HTML content gets used as template when
outputting each result; to change this requirement and/or behavior, you need to register your own Javascript results display.

### Confirm Solr search related TypoScript setup

This part of the integration is easily managed: due to `EXT:solrwidget` reusing every search-related TypoScript setting from
`EXT:solr` itself it is very likely your search will "just work" - but should you have problems with getting search results shown
or incorrect results, inspect your `plugin.tx_solr.search` configuration array. Any filters, preset queries, pagination, forced
facets etc. will be respected by both the plugin and Fluid Widget.

The initial intention for this search feature is **site search with typeahead and simple results display** which means the Widget
does not have any extended support for facets, frequent searches and suggested keywords in searches - and there are other settings
which no longer apply, for example the result highlight function. As such, the search is intended to be quite basic, but can be
extended to your heart's desires as long as you are prepared to write a more complex search dislay Javascript function.

### Replace search result display Javascript function

Since `EXT:solrwidget` is intended to only to AJAX search requests, your desired search result layout may require that you write
a replacement Javascript function which can process your results differently. Replacing the default results display function is
easy; on the `<form>` tag which contains the search field, add the HTML attribute `data-result-formatter="mySmartMethod"` which
will then require the function called `mySmartMethod` to be defined in Javascript and included by your own TypoScript or whichever
inclusion method you prefer. The `mySmartMethod` must then accept two arguments: `result` and `form` - the `result` argument will
contain an array of result objects with data from Solr (and if you register additional QueryProviders, the `result` argument will
instead contain an array groups of results. This means you have to test for the type - and this is most easily done by asserting
if the first result contains an array called `results`:

```
// given <form ... data-result-formatter="window.myFormatter">
myFormatter = function(results, form) {
	if ('array' == typeof results[0].results) {
		// result is multidimensional, coming from multiple Solr queries
	} else {
		// result is one Solr query's result output
	}
};
```

> Note: it is **not** a mistake that `var myFormatter` is not used; leaving the `var` out ensures the method can be fetched from
the `window` scope which was used in the `<form>` tag's `data-result-formatter` attribute value. The `form` argument contains a
live jQuery reference to the `<form>` DOM element which contains the search field that was used.

#### Example `results` values

```javascript
// plain, single-query results - objects contain much more data than shown here.
// passed to Javascritpt results display function as:
[{title: 'Result 1', url: '...'}, {title: 'Result 2', url: '...'}]

// grouped, multi-query results - when using QueryProviders, query's results get grouped
// according to how many queries have been provided.
[
	{title: 'Group one default query', results: [{title: 'Result 1.1'}, {title: 'Result 1.2'}]},
	{title: 'Group two from extra query A', results: [{title: 'Result 2.1'}, {title: 'Result 2.2'}]},
	{title: 'Group two from extra query B', results: [{title: 'Result 3.1'}, {title: 'Result 3.2'}]}
]

```

### Register additional Queries

By creating a custom class which can process Solr query objects it is possible to extend your results (or change the original
Query used by the Widget before it gets dispatched). For example, you can choose to search just two main "categories" of results
and return each set of results in a group by itself. Or you can manipulate the original Query, for example checking if the user
entered "product" as first part of their search and then limit results to products only.

In order to achieve this goal you must have a class implementing the proper interface from `EXT:solrwidget`:

```php
namespace Myvendor\Myext\QueryProvider;

use ApacheSolrForTypo3\Solr\Query;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DualCategoryQueryProvider
	implements QueryProviderInterface {

	/**
	 * Returns a human-readable title/label for this result
	 * group. Should use localisation features to return a
	 * translated label, for example. Can, but is not required
	 * to, use the $queryString and $originalQuery to determine
	 * which label to return, for example changing the label
	 * according to which category the first Query is searching.
	 *
	 * @param string $queryString User's search query
     * @param Query $originalQuery NULL or instance of first Query
     * @return Query|NULL
     */
	public function getTitle($queryString, $originalQuery) {
		$label = LocalizationUtility::translate('myGroupTitle', 'Myext');
		return NULL === $label ? 'Default label' : $label;
	}

	/**
	 * Process query; turning original query into a category-
	 * restricted search and returning a second query with a
	 * search in a second category, with a second set of
	 * restrictions. If the function instead returned NULL,
	 * only the modifications to the $originalQuery are used.
	 *
	 * This results in a grouped result being available to the
	 * Javascript function displaying the results; each group
	 * containing the result of each query.
	 *
	 * @param string $queryString User's search query
	 * @param Query $originalQuery NULL or instance of first Query
	 * @return Query|NULL
	 */
	public function processQuery($queryString, $originalQuery) {
		$firstCategory = 'products';
		$secondCategory = 'general';
		// force the Widget's original query to restrict on category:
		$originalQuery->addFilter('category:' . $firstCategory);
		// create a second Query instance with second search:
		$secondQuery = GeneralUtility::makeInstance(Query::class);
		$secondQuery->setQueryString($queryString);
		$secondQuery->addFilter('category:' . $secondCategory);
		return $secondQuery;
	}

}

```

To inform `EXT:solrwidget` that you want your QueryProvider taken into consideration, simply add it as follows in your
`ext_localconf.php` file:

```php
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['solrwidget']['queryProviders'][] = 'Myvendor\\Myext\\QueryProvider\\DualCategoryQueryProvider';
```

The search mechanism will then read this object, create an instance and ask it to manipulate the original query and/or return a
second query which must also be sent to Solr. Your QueryProvider class can use Extbase mechanisms like `initializeObject` and
Dependency Injection methods; the only requirement is that it must implement the proper interface.
