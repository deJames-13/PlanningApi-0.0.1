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
        Schema::create('budget_quarters', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('quarter');
            $table->string('label');
            $table->decimal('allotment', 10, 2);
            $table->decimal('obligated', 10, 2);
            $table->decimal('utilization_rate', 10, 2);

            // Parent: BudgetAnnual
            $table->foreignId('budget_annual_id')->constrained('budget_annuals')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_quarters');
    }
};
