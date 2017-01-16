<?php namespace Inetis\GoogleCustomSearch;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'Custom Google Search',
            'description' => 'Add custom google search results to your site',
            'author'      => 'inetis',
            'icon'        => 'icon-search'
        ];
    }

    public function registerComponents()
    {
        return [
            '\Inetis\GoogleCustomSearch\Components\SearchResults' => 'searchResults'

        ];
    }
}
