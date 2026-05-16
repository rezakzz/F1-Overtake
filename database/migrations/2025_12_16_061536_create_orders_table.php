<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->unsignedBigInteger('total')->default(0); 
            $table->enum('status', ['pending','approved','processing','shipped','delivered','cancelled'])
                  ->default('pending');

            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
