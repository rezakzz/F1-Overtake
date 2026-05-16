<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('recipient_name')->nullable();
            $table->string('phone')->nullable();
            $table->text('shipping_address')->nullable(); // alamat lengkap
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('note')->nullable(); // catatan kurir (opsional)
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'recipient_name','phone','shipping_address','city','postal_code','note'
            ]);
        });
    }
};
