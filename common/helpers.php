<?php

use Botble\PluginManagement\Services\PluginService;

if (! function_exists('is_plugin_active')) {
    function is_plugin_active(string $alias): bool
    {
        return in_array($alias, get_active_plugins());
    }
}

if (! function_exists('get_active_plugins')) {
    function get_active_plugins(): array
    {
        return PluginService::getActivatedPlugins();
    }
}
