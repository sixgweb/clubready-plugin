<?php

namespace Sixgweb\ClubReady\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sixgweb_clubready_installments', function (Blueprint $table) {
            $table->decimal('first_payment_amount', 10, 2)->default(0)->after('payment_amount');
        });
    }

    public function down()
    {
        Schema::table('sixgweb_clubready_installments', function (Blueprint $table) {
            $table->dropColumn('first_payment_amount');
        });
    }
};
