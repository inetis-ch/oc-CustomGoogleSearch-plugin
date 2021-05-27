<?php namespace Inetis\GoogleCustomSearch;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            Components\SearchResults::class => 'searchResults',
        ];
    }
}
