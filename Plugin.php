<?php namespace Inetis\GoogleCustomSearch;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            '\Inetis\GoogleCustomSearch\Components\SearchResults' => 'searchResults'
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Google Custom Search',
                'description' => 'Connect to REST API',
                'category'    => 'Users',
                'icon'        => 'icon-search',
                'class'       => 'Inetis\GoogleCustomSearch\Models\Settings',
                'order'       => 500,
                'keywords'    => 'Google Custom Search',
                'permissions' => ['inetis.googlecustomsearch.access_settings']
            ]
        ];
    }
}
