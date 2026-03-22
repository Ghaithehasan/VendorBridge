<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rfq_recipients', function (Blueprint $table) {
            $table->bigIncrements('recipient_id');

            $table->foreignId('rfq_id')
                ->constrained('rfq', 'rfq_id')
                ->onDelete('cascade');

            $table->foreignId('vendor_id')
                ->constrained('vendors', 'vendor_id')
                ->onDelete('restrict');

            $table->enum('status', ['pending', 'sent', 'responded', 'declined', 'expired'])
                ->default('pending');

            $table->timestamps();

            // A vendor can only be added once per RFQ
            $table->unique(['rfq_id', 'vendor_id'], 'uq_rfq_recipients_rfq_vendor');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rfq_recipients');
    }
};
