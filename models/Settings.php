<?php namespace Inetis\GoogleCustomSearch\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'googlecustomsearch_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}