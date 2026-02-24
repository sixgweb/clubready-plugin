<?php

namespace Sixgweb\ClubReady\Models;

use Model;

/**
 * Staff Model
 *
 * @link https://docs.octobercms.com/4.x/extend/system/models.html
 */
class Staff extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    /**
     * @var string table name
     */
    public $table = 'sixgweb_clubready_staff';

    /**
     * @var array rules for validation
     */
    public $rules = [];

    public $fillable = [
        'is_enabled',
        'clubready_id',
        'staff_type_id',
        'first_name',
        'last_name',
        'title',
        'bio',
        'email',
        'sort_order',
    ];

    public $attachOne = [
        'photo' => \System\Models\File::class,
    ];
}
