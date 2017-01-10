<?php namespace Inetis\GoogleCustomSearch\Components;

use Cms\Classes\ComponentBase;
use Http;
use Inetis\GoogleCustomSearch\Models\Settings;

class SearchResults extends ComponentBase
{

    var $search;
    const APIURL = 'https://www.googleapis.com/customsearch/v1';

    public function componentDetails()
    {
        return [
            'name' => 'Search Result',
            'description' => 'Search Result'
        ];
    }

    public function onRun()
    {
        $this->search = get('q');
        $url = $this->buildAPIUrl();

        $response = json_decode(Http::get($url));

        $this->page['search'] = $this->search;
        $this->page['totalResults'] = $response->searchInformation->totalResults;

        if ($this->page['totalResults']) {
            $this->page['results'] = $response->items;
        }

        $resultPerPage  = Settings::get('resultPerPage', 25);
    }

    /**
     * @return string
     */
    private function buildAPIUrl()
    {
        $start = get('start', 1);

        $apiKey         = Settings::get('apikey');
        $cx             = Settings::get('cx');

        $params = array('key' => $apiKey,
            'cx' => $cx,
            'start' => $start,
            'q' => $this->search);

        return self::APIURL . '?' . http_build_query($params);
    }
}
