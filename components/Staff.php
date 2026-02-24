<?php

namespace Sixgweb\ClubReady\Components;

use Cms\Classes\ComponentBase;
use Sixgweb\ClubReady\Models\Staff as StaffModel;

/**
 * Staff Component
 *
 * @link https://docs.octobercms.com/4.x/extend/cms-components.html
 */
class Staff extends ComponentBase
{
    use \Sixgweb\ClubReady\Traits\CallsApi;

    public function componentDetails()
    {
        return [
            'name' => 'Staff Component',
            'description' => 'No description provided yet...'
        ];
    }

    /**
     * @link https://docs.octobercms.com/4.x/element/inspector-types.html
     */
    public function defineProperties()
    {
        return [
            'types' => [
                'title' => 'Staff Types',
                'description' => 'Comma separated list of staff type IDs to filter by.',
                'type' => 'set',
            ],
        ];
    }

    public function init()
    {
        $this->prepareVars();
    }

    public function prepareVars(): void
    {
        $staff = $this->page['staff'] = $this->getStaff();
    }

    public function getTypesOptions(): array
    {
        $types = $this->getApiResponse('/staff/types');
        $options = [];
        foreach ($types as $type) {
            $options['type-' . $type['Id']] = $type['Title'];
        }
        return $options;
    }

    private function getStaff()
    {
        return StaffModel::where('is_enabled', true)->get();
    }
}
