<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'order_code')) {
                $table->string('order_code')->nullable()->after('id');
            }

            if (!Schema::hasColumn('orders', 'snap_token')) {
                $table->string('snap_token')->nullable();
            }

            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
            if (Schema::hasColumn('orders', 'snap_token')) {
                $table->dropColumn('snap_token');
            }
            if (Schema::hasColumn('orders', 'order_code')) {
                $table->dropColumn('order_code');
            }
        });
    }
};
