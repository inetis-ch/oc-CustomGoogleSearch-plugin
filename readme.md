# Custom Google Search
This plugin allows you to add Google's Custom Search Engine (CSE) Search to your OctoberCMS site using the Google API.


## Results
Here is the default template, you can format it any way you like.

```
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

You can embed this search in your site. Add the CustomGoogleSearch component


### Components
Name | Page variable | Description 
------------- | ------------- | -------------
Search | `{% component 'search' %}` | Outputs the search form

**Properties**

Property | Description | Default Value
------------- | ------------- | -------------
apikey  | Google API Key | -
cx  | Custom search engine id | format 1232342342344:xxxxxx
resultPerPage  | Result per page | 20


## Form
You need also to create a form to send the request to the display page.
```
<form action="/your_search_page" method="get">
	<div class="search">
	<input name="q" type="text"/>
	<input type="submit" value="Search">
	</div>
</form>
```
The search field muss have q as name.

## Prerequisites

### Search engine ID

By calling the API user issues requests against an existing instance of a Custom Search Engine. Therefore, before using the API, you need to create one in the [Control Panel](http://cse.google.com/manage/all) . Follow the [tutorial](https://developers.google.com/custom-search/docs/tutorial/creatingcse) to learn more about different configuration options. You can find the engine's ID in the **Setup > Basics > Details** section of the Control Panel.

### API key

Custom Search API requires the use of an API key. You need to fill it in the settings of your site.

Custom Search Engine (free edition) users can obtain the key from the [Google API Console](https://console.developers.google.com/) .

The API provides 100 search queries per day for free. If you need more, you may sign up for billing in the API Console. Additional requests cost $5 per 1000 queries, up to 10k queries per day.

## Author
inetis is a webdesign agency in Vufflens-la-Ville, Switzerland. We love coding and creating powerful apps and sites  [see our website](https://inetis.ch).
