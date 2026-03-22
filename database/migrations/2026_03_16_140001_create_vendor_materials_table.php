<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_materials', function (Blueprint $table) {
            $table->bigIncrements('vendor_material_id');
            $table->foreignId('vendor_id')
                ->constrained('vendors', 'vendor_id')
                ->onDelete('cascade');
            $table->foreignId('material_id')
                ->constrained('raw_materials', 'material_id')
                ->onDelete('cascade');

            $table->integer('lead_time_days')->nullable();
            $table->decimal('minimum_order_qty', 18, 4)->nullable();
            $table->boolean('preferred_vendor')->default(false);
            $table->decimal('last_price', 18, 4)->nullable();

            // Currency for last_price
            $table->foreignId('currency_id')
                ->nullable()
                ->constrained('currencies')
                ->restrictOnDelete();

            $table->string('vendor_material_code', 100)->nullable();
            $table->timestamps();

            $table->unique(['vendor_id', 'material_id'], 'uq_vendor_material');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_materials');
    }
};