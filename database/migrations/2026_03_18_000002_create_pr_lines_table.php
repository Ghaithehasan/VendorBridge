<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pr_lines', function (Blueprint $table) {
            $table->bigIncrements('pr_line_id');

            $table->foreignId('pr_id')
                ->constrained('pr_header', 'pr_id')
                ->onDelete('cascade');

            // Line ordering within a PR document
            $table->unsignedSmallInteger('line_no');

            $table->foreignId('material_id')
                ->constrained('raw_materials', 'material_id')
                ->onDelete('restrict');

            $table->decimal('quantity', 18, 4);

            $table->foreignId('unit_id')
                ->constrained('units', 'unit_id')
                ->onDelete('restrict');

            $table->date('required_delivery_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            // A material can only appear once per PR
            $table->unique(['pr_id', 'line_no'], 'uq_pr_lines_pr_line_no');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('pr_lines');
    }
};
