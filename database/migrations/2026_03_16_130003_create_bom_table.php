<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bom', function (Blueprint $table) {
            $table->bigIncrements('bom_id');
            $table->foreignId('product_id')->constrained('products', 'product_id')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('raw_materials', 'material_id')->onDelete('restrict');
            $table->decimal('quantity_required', 18, 4);
            $table->foreignId('unit_id')->constrained('units', 'unit_id')->onDelete('restrict');
            $table->timestamps();

            // Constraint: Unique combination of product and material
            $table->unique(['product_id', 'material_id'], 'uq_bom_product_material');
        });

        // Add check constraint for quantity_required > 0 using raw SQL
        DB::statement('ALTER TABLE bom ADD CONSTRAINT chk_bom_quantity_required CHECK (quantity_required > 0)');
    }

    public function down(): void
    {
        Schema::table('bom', function (Blueprint $table) {
            DB::statement('ALTER TABLE bom DROP CONSTRAINT IF EXISTS chk_bom_quantity_required');
        });
        Schema::dropIfExists('bom');
    }
};