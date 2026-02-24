<?php

namespace Sixgweb\ClubReady\Components;

use Cms\Classes\ComponentBase;

/**
 * Packages Component
 *
 * @link https://docs.octobercms.com/4.x/extend/cms-components.html
 */
class Packages extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Packages Component',
            'description' => 'No description provided yet...'
        ];
    }

    /**
     * @link https://docs.octobercms.com/4.x/element/inspector-types.html
     */
    public function defineProperties()
    {
        return [];
    }

    public function init()
    {
        $this->prepareVars();
    }

    public function prepareVars()
    {
        $this->page['packages'] = \Sixgweb\ClubReady\Models\Package::with('installments')->where('is_enabled', true)->get();
        $this->page['storeId'] = \Sixgweb\ClubReady\Models\Setting::get('store_id');
    }
}
