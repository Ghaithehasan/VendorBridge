<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pr_header', function (Blueprint $table) {
            $table->bigIncrements('pr_id');

            // Auto-generated in service layer: PR-YYYYMMDD-XXXX
            $table->string('pr_number', 30)->unique();

            $table->foreignId('requester_id')
                ->constrained('users', 'user_id')
                ->onDelete('restrict');

            // Snapshot at PR creation — preserved even if user changes department later
            $table->foreignId('department_id')
                ->constrained('departments', 'department_id')
                ->onDelete('restrict');

            $table->date('request_date');
            $table->enum('status', ['draft', 'submitted', 'approved', 'cancelled'])
                ->default('draft');
            $table->text('notes')->nullable();

            // Approval audit trail
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users', 'user_id')
                ->onDelete('restrict');
            $table->timestamp('approved_at')->nullable();

            // Cancellation audit trail
            $table->foreignId('cancelled_by')
                ->nullable()
                ->constrained('users', 'user_id')
                ->onDelete('restrict');
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index('status');
            $table->index('requester_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pr_header');
    }
};