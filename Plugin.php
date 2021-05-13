<?php

namespace RLuders\JWTAuth;

use App, Config;
use Illuminate\Foundation\AliasLoader;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;
use RLuders\JWTAuth\Models\Settings as PluginSettings;

/**
 * JWTAuth Plugin Information File.
 */
class Plugin extends PluginBase
{
    /**
     * Plugin dependencies.
     *
     * @var array
     */
    public $require = ['Winter.User'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'rluders.jwtauth::lang.plugin.name',
            'description' => 'rluders.jwtauth::lang.plugin.description',
            'author'      => 'Ricardo Lüders',
            'icon'        => 'icon-user-secret',
        ];
    }

    /**
     * Register the plugin settings
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'rluders.jwtauth::lang.settings.menu_label',
                'description' => 'rluders.jwtauth::lang.settings.menu_description',
                'category'    => SettingsManager::CATEGORY_USERS,
                'icon'        => 'icon-user-secret',
                'class'       => 'RLuders\JWTAuth\Models\Settings',
                'order'       => 600,
                'permissions' => ['rluders.jwtauth.access_settings'],
            ]
        ];
    }

    /**
     * Register the plugin permissions
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'rluders.jwtauth.access_settings' => [
                'tab' => 'rluders.jwtauth::lang.plugin.name',
                'label' => 'rluders.jwtauth::lang.permissions.settings'
            ]
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        App::register('\RLuders\JWTAuth\Providers\AuthServiceProvider');
        $alias = AliasLoader::getInstance();
        $alias->alias('JWTAuth', '\RLuders\JWTAuth\Facades\JWTAuth');

        // Handle error
        $this->app->error(function (\Exception $e) {
            if (!request()->isJson()) {
                return;
            }

            return [
                'error' => [
                    'code' => 'internal_error',
                    'http_code' => 500,
                    'message' => $e->getMessage(),
                ],
            ];
        });
    }
}
