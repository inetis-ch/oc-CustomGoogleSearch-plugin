<?php namespace Inetis\GoogleCustomSearch\Components;

use Cms\Classes\ComponentBase;
use Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Inetis\GoogleCustomSearch\Models\Settings;

class SearchResults extends ComponentBase
{

    var $search;
    var $currentPage;
    var $resultPerPage;
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
        $this->currentPage = (int) get('page', 1);
        $this->resultPerPage  = (int) Settings::get('resultPerPage', 25);
        $url = $this->buildAPIUrl();

        $response = json_decode(Http::get($url));

        $this->page['search'] = $this->search;
        $this->page['totalResults'] = $response->searchInformation->totalResults;

        if ($this->page['totalResults']) {
            $this->page['results'] = new LengthAwarePaginator($response->items, $response->searchInformation->totalResults, $this->resultPerPage, $this->currentPage);
            $this->page['results']->setPath('search');
            $this->page['results']->addQuery('q', $this->search);
        }
    }

    /**
     * @return string
     */
    private function buildAPIUrl()
    {

        $apiKey         = Settings::get('apikey');
        $cx             = Settings::get('cx');

        $start = ($this->currentPage-1)*$this->resultPerPage+1;
        $params = array('key' => $apiKey,
            'cx' => $cx,
            'start' => $start,
            'q' => $this->search);

        return self::APIURL . '?' . http_build_query($params);
    }
}
