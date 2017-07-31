<?php namespace Inetis\GoogleCustomSearch\Components;

use Cms\Classes\ComponentBase;
use Http;
use Flash;
use Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Inetis\GoogleCustomSearch\Models\Settings;

class SearchResults extends ComponentBase
{

    const APIURL = 'https://www.googleapis.com/customsearch/v1';

    var $search;
    var $currentPage;
    var $resultPerPage;

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
        $this->resultPerPage = $this->property('resultPerPage');

        $http     = $this->buildRequest();
        $response = json_decode($http->send());

        $this->page['search'] = $this->search;

        if (property_exists($response, 'error'))
        {
            Flash::error($response->error->message);
        }
        elseif ($response->searchInformation->totalResults)
        {
            $this->page['totalResults'] = $response->searchInformation->totalResults;
            $result                     = new LengthAwarePaginator($response->items, $response->searchInformation->totalResults, $this->resultPerPage, $this->currentPage);
            $result->setPath($this->page['baseFileName']);
            $result->appends('q', $this->search);
            $this->page['results'] = $result;
        }
    }

    /**
     * Calculate the API url call
     *
     * @return string
     */
    private function buildAPIUrl()
    {

        $apiKey = $this->property('apikey');
        $cx     = $this->property('cx');

        $start  = ($this->currentPage - 1) * $this->resultPerPage + 1;
        $params = array('key'   => $apiKey,
                        'cx'    => $cx,
                        'start' => $start,
                        'q'     => $this->search,
                        'num'   => $this->resultPerPage);

        return self::APIURL . '?' . http_build_query($params);
    }

    private function buildRequest()
    {
        $http = Http::make($this->buildAPIUrl(), 'GET');

        if ($this->property('sendReferer'))
        {
            $http->header('Referer', Request::url());
        }

        return $http;
    }
}
