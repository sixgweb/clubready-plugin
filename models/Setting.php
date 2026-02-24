<?php

namespace Sixgweb\ClubReady\Models;

use Model;

/**
 * Setting Model
 *
 * @link https://docs.octobercms.com/4.x/extend/system/models.html
 */
class Setting extends \System\Models\SettingModel
{
    public $settingsCode = 'sixgweb_clubready_settings';

    public $settingsFields = 'fields.yaml';
}
