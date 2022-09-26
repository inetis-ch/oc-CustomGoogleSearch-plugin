<?php namespace Inetis\GoogleCustomSearch\Components;

use Cms\Classes\ComponentBase;
use Flash;
use Illuminate\Pagination\LengthAwarePaginator;
use Inetis\GoogleCustomSearch\Classes\HttpClient;
use Request;

class SearchResults extends ComponentBase
{
    const APIURL = 'https://customsearch.googleapis.com/customsearch/v1';

    var $search;
    var $currentPage;
    var $resultPerPage;
    var $maxResults;

    public function componentDetails()
    {
        return [
            'name'        => 'Search Result',
            'description' => 'Search Result'
        ];
    }

    public function defineProperties()
    {
        return [
            'apikey'        => [
                'title'       => 'Google API Key',
                'description' => 'API key from the Google Console',
                'default'     => '',
                'type'        => 'string',
                'required'    => true,
            ],
            'cx'            => [
                'title'       => 'Custom search engine id',
                'description' => 'Engine id format 1234:xxxxxx',
                'default'     => '',
                'type'        => 'string',
                'required'    => true,
            ],
            'resultPerPage' => [
                'title'       => 'Results per page',
                'description' => 'Number of results per page. This is limited to max 10 by Google API',
                'default'     => 10,
                'type'        => 'string',
                'required'    => true,
                'validationPattern' => '^([1-9]|10)$',
                'validationMessage' => 'Maximum results per page is limited to 10 by Google API',
            ],
            'sendReferer' => [
                'title' => 'Send HTTP Referer',
                'description' => 'If you are using "HTTP Referer" to restrict access to your Google API Key, please enable this option to make the plugin send the url of the viewed page as referer.',
                'default' => true,
                'type' => 'checkbox'
            ]
        ];
    }

    /**
     * Executed when this component is bound to a page or layout.
     */
    public function onRun()
    {
        $this->search        = get('q');
        $this->currentPage   = (int) get('page', 1);
        // maximum per page results allowed from Google API
        $this->resultPerPage = max(1, min(10, $this->property('resultPerPage')));
        // maximum results allowed from Google API
        $this->maxResults    = 100;

        $http     = $this->buildRequest();
        $response = json_decode($http->send());

        $this->page['search'] = $this->search;

        if (property_exists($response, 'error'))
        {
            Flash::error($response->error->message);
        }
        elseif ($totalResults = data_get($response, 'searchInformation.totalResults'))
        {
            // set the hard limit to all results and pagination
            $totalResponseResults = min($this->maxResults, $totalResults);

            $this->page['totalResults'] = $totalResponseResults;
            $result = new LengthAwarePaginator($response->items, $totalResponseResults, $this->resultPerPage, $this->currentPage);
            $result->setPath($this->page['baseFileName']);
            $result->appends('q', $this->search);
            $this->page['results'] = $result;
        }
    }

    private function buildRequest()
    {
        $http = HttpClient::make(self::APIURL);

        $http->setData([
            'key'   => $this->property('apikey'),
            'cx'    => $this->property('cx'),
            'start' => min($this->maxResults, ($this->currentPage - 1) * $this->resultPerPage + 1),
            'q'     => $this->search,
            'num'   => $this->resultPerPage,
        ]);

        if ($this->property('sendReferer')) {
            $http->header('Referer', Request::url());
        }

        return $http;
    }
}
