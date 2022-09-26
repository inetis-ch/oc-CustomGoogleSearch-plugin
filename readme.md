# About
OctoberCMS plugin to add Google's Custom Search Engine (CSE) Search using the Google API.


## Prerequisites

### Search engine ID

When calling the API, the user issues requests against an existing instance of a CSE. Therefore, before using this API you will need to create a CSE in the [CSE Google Control Panel](https://programmablesearchengine.google.com/controlpanel/all). Follow the [tutorial](https://developers.google.com/custom-search/docs/tutorial/creatingcse) to learn more about the different configuration options. Once you have setup a CSE, you can find the CSE's ID in the **Setup > Basics > Details** section of the Control Panel for the CSE.

### API key

The Custom Search API requires the use of an API key. It will need to be added to the `searchResults` component API Key property. Custom Search Engine (free edition) users can obtain the key from the [Google API Console](https://console.developers.google.com/).

The API provides 100 search queries per day for free. If you need more, you may sign up for billing in the API Console. Additional requests cost $5 per 1000 queries, up to 10k queries per day.

## Usage
To use this plugin you will need the `searchResults` component added to a CMS Page:
```twig
url="/results"
layout="default"

[searchResults]
apikey=XXXXXXXXXXXXXXXXXXX
cx=1232342342344:xxxxxx
resultsPerPage=10
sendReferer=true
==
{% component 'searchResults' %}
```
The default template for rendering the results is
```twig
<h1>{{ totalResults }} Results for {{search}}</h1>
{% if results %}
<ul>
    {% for result in results %}
    <li>
        <h3><a href="{{ result.link }}">{{ result.htmlTitle|raw }}</a></h3>
        {{ result.htmlSnippet|raw }}
    </li>
    {% endfor %}
</ul>
{{ results.render|raw }}
{% endif %}
```
To modify it for your own theme, place it in `themes/yourtheme/partials/searchresults/default.htm` and edit it accordingly.

Once you have the CMS Search Results page configured, you will need to create a search form and add it to your pages accordingly. The best option is to put the following code into `themes/yourtheme/partials/theme/search.htm` and then reference it with `{% partial 'theme/search.htm' %}` anywhere you need to include it:
```twig
<form action="{{ 'your-search-results-page' | page }}" method="get">
    <div class="search">
    <input name="q" type="text"/>
    <input type="submit" value="Search">
    </div>
</form>
```

## Author
inetis is a webdesign agency in Vufflens-la-Ville, Switzerland. We love coding and creating powerful apps and sites  [see our website](https://inetis.ch).
