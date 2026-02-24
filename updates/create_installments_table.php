<?php

namespace Sixgweb\ClubReady\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateInstallmentsTable Migration
 *
 * @link https://docs.octobercms.com/4.x/extend/database/structure.html
 */
return new class extends Migration
{
    /**
     * up builds the migration
     */
    public function up()
    {
        Schema::create('sixgweb_clubready_installments', function (Blueprint $table) {
            $table->id();
            $table->integer('installment_id')->unsigned()->nullable();
            $table->integer('package_id')->unsigned()->nullable();
            $table->integer('payment_count')->unsigned()->nullable();
            $table->decimal('payment_amount', 10, 2)->nullable();
            $table->decimal('setup_fee', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * down reverses the migration
     */
    public function down()
    {
        Schema::dropIfExists('sixgweb_clubready_installments');
    }
};
