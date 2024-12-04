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
        Schema::create('budget_annuals', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->decimal('target', 10, 2);
            $table->decimal('accomplishment', 10, 2);
            $table->decimal('utilization_rate', 10, 2);

            // Parent: Budget
            $table->foreignId('budget_id')->constrained('budgets')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_annuals');
    }
};
