<?php

namespace Kanboard\Plugin\SimplerFilters;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;

class Plugin extends Base
{

    public function initialize()
    {
        $this->hook->on('template:layout:css', array('template' => 'plugins/SimplerFilters/Assets/css/filters.css'));        
        $this->hook->on('template:layout:js', array('template' => 'plugins/SimplerFilters/Assets/js/filters.js'));
        $this->helper->register('simplerFilters', '\Kanboard\Plugin\SimplerFilters\Helper\FilterHelper');
        $this->template->setTemplateOverride('project_header/search', 'SimplerFilters:project_header/search');
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getPluginName()
    {
        return 'Simpler Filters';
    }

    public function getPluginDescription()
    {
        return 'Replaces the search bar with a dropdown filter system (Tags, Users, Dates, Priorities).';
    }

    public function getPluginAuthor()
    {
        return 'laimeilleurzamis';
    }

    public function getPluginVersion()
    {
        return '1.0.0';
    }
}