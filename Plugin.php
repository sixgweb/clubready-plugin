<?php

namespace Sixgweb\ClubReady;

use Backend;
use Sixgweb\ClubReady\Models\Package;
use System\Classes\PluginBase;

/**
 * Plugin Information File
 *
 * @link https://docs.octobercms.com/4.x/extend/system/plugins.html
 */
class Plugin extends PluginBase
{

    use \Sixgweb\ClubReady\Traits\CallsApi;
    use \Sixgweb\ClubReady\Traits\SyncsPackages;

    /**
     * pluginDetails about this plugin.
     */
    public function pluginDetails()
    {
        return [
            'name' => 'ClubReady',
            'description' => 'No description provided yet...',
            'author' => 'Sixgweb',
            'icon' => 'icon-leaf'
        ];
    }

    /**
     * register method, called when the plugin is first registered.
     */
    public function register()
    {
        //
    }

    /**
     * boot method, called right before the request route.
     */
    public function boot() {}

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
        return [
            'Sixgweb\ClubReady\Components\Packages' => 'packageList',
            'Sixgweb\ClubReady\Components\Staff' => 'staffList',
        ];
    }

    /**
     * registerPermissions used by the backend.
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'sixgweb.clubready.some_permission' => [
                'tab' => 'ClubReady',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * registerNavigation used by the backend.
     */
    public function registerNavigation()
    {
        return [
            'clubready' => [
                'label' => 'ClubReady',
                'url' => Backend::url('sixgweb/clubready/packages'),
                'icon' => 'icon-leaf',
                'permissions' => ['sixgweb.clubready.*'],
                'order' => 500,
                'sideMenu' => [
                    'packages' => [
                        'label' => 'Packages',
                        'icon' => 'icon-copy',
                        'url' => Backend::url('sixgweb/clubready/packages'),
                        'permissions' => ['sixgweb.clubready.access_packages'],
                    ],
                    'staff' => [
                        'label' => 'Staff',
                        'icon' => 'icon-copy',
                        'url' => Backend::url('sixgweb/clubready/staff'),
                        'permissions' => ['sixgweb.clubready.access_staff'],
                    ]
                ]
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'ClubReady Settings',
                'description' => 'Manage ClubReady API settings',
                'category'    => 'ClubReady',
                'icon'        => 'icon-person-arms-up',
                'class'       => 'Sixgweb\ClubReady\Models\Setting',
                'order'       => 500,
                'keywords'    => 'clubready settings',
            ]
        ];
    }

    public function registerSchedule($schedule)
    {
        $schedule->call(function () {
            $this->syncClubReadyPackages();
        })->daily();
    }
}
