<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rfq', function (Blueprint $table) {
            $table->bigIncrements('rfq_id');

            $table->foreignId('pr_line_id')
                ->unique()
                ->constrained('pr_lines', 'pr_line_id')
                ->onDelete('restrict');

            $table->string('rfq_number', 30)->unique();

            // Snapshot fields
            $table->foreignId('material_id')
                ->constrained('raw_materials', 'material_id')
                ->onDelete('restrict');
            $table->decimal('quantity', 18, 4);
            $table->foreignId('unit_id')
                ->constrained('units', 'unit_id')
                ->onDelete('restrict');
            $table->date('required_delivery_date');
            // End snapshot

            $table->date('rfq_date');
            $table->date('quotation_due_date');

            // Currency required for vendor responses
            $table->foreignId('currency_id')
                ->nullable()
                ->constrained('currencies')
                ->restrictOnDelete();

            $table->string('payment_terms', 255)->nullable();
            $table->string('delivery_location', 255)->nullable();
            $table->string('pdf_path', 500)->nullable();

            $table->foreignId('issued_by')
                ->nullable()
                ->constrained('users', 'user_id')
                ->onDelete('restrict');
            $table->timestamp('issued_at')->nullable();

            $table->enum('status', ['draft', 'issued', 'closed', 'awarded', 'cancelled'])
                ->default('draft');

            $table->softDeletes();
            $table->timestamps();

            $table->index('status');
        });

        DB::statement('ALTER TABLE rfq ADD CONSTRAINT chk_rfq_quotation_due_date CHECK (quotation_due_date >= rfq_date)');
        DB::statement('ALTER TABLE rfq ADD CONSTRAINT chk_rfq_quantity CHECK (quantity > 0)');
        DB::statement("ALTER TABLE rfq ADD CONSTRAINT chk_rfq_issued_by_required CHECK (status = 'draft' OR issued_by IS NOT NULL)");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE rfq DROP CONSTRAINT IF EXISTS chk_rfq_quotation_due_date');
        DB::statement('ALTER TABLE rfq DROP CONSTRAINT IF EXISTS chk_rfq_quantity');
        DB::statement('ALTER TABLE rfq DROP CONSTRAINT IF EXISTS chk_rfq_issued_by_required');
        Schema::dropIfExists('rfq');
    }
};