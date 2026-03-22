<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotation', function (Blueprint $table) {
            $table->bigIncrements('quotation_id');

            $table->foreignId('recipient_id')
                ->constrained('rfq_recipients', 'recipient_id')
                ->onDelete('cascade');

            $table->unsignedSmallInteger('version_no');
            $table->decimal('unit_price', 18, 4);

            // No currency_code here — currency inherited from rfq.currency_id
            $table->integer('lead_time_days')->nullable();

            $table->enum('status', [
                'draft',
                'submitted',
                'revised',
                'withdrawn',
                'rejected',
                'awarded',
            ])->default('draft');

            $table->timestamps();

            $table->unique(['recipient_id', 'version_no'], 'uq_quotation_recipient_version');
            $table->index('status');
        });

        DB::statement('ALTER TABLE quotation ADD CONSTRAINT chk_quotation_unit_price CHECK (unit_price > 0)');
        DB::statement('ALTER TABLE quotation ADD CONSTRAINT chk_quotation_lead_time_days CHECK (lead_time_days IS NULL OR lead_time_days >= 0)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE quotation DROP CONSTRAINT IF EXISTS chk_quotation_unit_price');
        DB::statement('ALTER TABLE quotation DROP CONSTRAINT IF EXISTS chk_quotation_lead_time_days');
        Schema::dropIfExists('quotation');
    }
};