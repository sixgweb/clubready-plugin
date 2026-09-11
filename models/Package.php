<?php

namespace Sixgweb\ClubReady\Models;

use Model;

/**
 * Package Model
 *
 * @link https://docs.octobercms.com/4.x/extend/system/models.html
 */
class Package extends Model
{
    use \Sixgweb\ClubReady\Traits\CallsApi;
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    /**
     * @var string table name
     */
    public $table = 'sixgweb_clubready_packages';

    /**
     * @var array rules for validation
     */
    public $rules = [];

    public $fillable = [
        'clubready_id',
        'name',
        'name_override',
        'price',
        'price_override',
    ];

    public $hasMany = [
        'installments' => [
            Installment::class,
            'key' => 'package_id',
            'otherKey' => 'clubready_id',
        ],
    ];

    public function getSetupFeeAttribute()
    {
        $setupFee = 0;
        foreach ($this->installments as $installment) {
            $setupFee += $installment->setup_fee;
        }
        return $setupFee;
    }

    public function getPackageNameAttribute()
    {
        return $this->name_override ?: $this->name;
    }
}
