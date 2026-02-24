<?php

namespace Sixgweb\ClubReady\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Sixgweb\ClubReady\Models\Staff as StaffModel;

/**
 * Staff Backend Controller
 *
 * @link https://docs.octobercms.com/4.x/extend/system/controllers.html
 */
class Staff extends Controller
{
    use \Sixgweb\ClubReady\Traits\CallsApi;

    /**
     * @var array implement behaviors
     */
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
    ];

    /**
     * @var string formConfig file
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var string listConfig file
     */
    public $listConfig = 'config_list.yaml';

    /**
     * @var array required permissions
     */
    public $requiredPermissions = ['sixgweb.clubready.staff'];

    /**
     * __construct the controller
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Sixgweb.ClubReady', 'clubready', 'staff');
    }

    public function onSyncStaff()
    {
        $disabled = StaffModel::where('is_enabled', false)->pluck('clubready_id')->toArray();

        foreach ($this->getApiResponse('/staff') as $staffData) {

            if (in_array($staffData['UserId'], $disabled)) {
                continue;
            }

            $staff = StaffModel::firstOrNew([
                'clubready_id' => $staffData['UserId'],
            ]);
            $staff->first_name = $staffData['FirstName'];
            $staff->last_name = $staffData['LastName'];
            $staff->title = $staffData['BioTitle'] ?? null;
            $staff->bio = $staffData['BioText'] ?? null;
            $staff->email = $staffData['Email'] ?? null;
            $staff->staff_type_id = $staffData['StaffTypeId'] ?? null;
            $staff->save();

            if (isset($staffData['PhotoUrl']) && $staffData['PhotoUrl']) {
                $staff->photo = (new \System\Models\File)->fromUrl($staffData['PhotoUrl']);
                $staff->save();
            }
        }

        \Flash::success('Staff synchronized from ClubReady.');
    }
}
