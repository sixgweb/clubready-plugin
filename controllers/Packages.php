<?php

namespace Sixgweb\ClubReady\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Sixgweb\ClubReady\Models\Package;
use Sixgweb\ClubReady\Models\Installment;

/**
 * Packages Backend Controller
 *
 * @link https://docs.octobercms.com/4.x/extend/system/controllers.html
 */
class Packages extends Controller
{
    use \Sixgweb\ClubReady\Traits\CallsApi;
    use \Sixgweb\ClubReady\Traits\SyncsPackages;

    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
        \Backend\Behaviors\RelationController::class,
    ];

    /**
     * @var string formConfig file
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var string listConfig file
     */
    public $listConfig = 'config_list.yaml';

    public $relationConfig = 'config_relation.yaml';

    /**
     * @var array required permissions
     */
    public $requiredPermissions = ['sixgweb.clubready.packages'];

    /**
     * __construct the controller
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Sixgweb.ClubReady', 'clubready', 'packages');
    }

    public function onSyncPackages()
    {
        $this->syncClubReadyPackages();

        \Flash::success('Packages synchronized from ClubReady.');
    }
}
