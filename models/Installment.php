<?php

namespace Sixgweb\ClubReady\Models;

use Model;

/**
 * Installment Model
 *
 * @link https://docs.octobercms.com/4.x/extend/system/models.html
 */
class Installment extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table name
     */
    public $table = 'sixgweb_clubready_installments';

    /**
     * @var array rules for validation
     */
    public $rules = [];

    public $fillable = [
        'installment_id',
        'package_id',
        'payment_count',
        'payment_amount',
        'first_payment_amount',
        'setup_fee',
    ];
}
