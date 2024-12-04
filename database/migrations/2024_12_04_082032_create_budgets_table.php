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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->year('current_year');
            $table->smallInteger('current_quarter')->default(1);

            // Has an optional parent: Sector
            // If no sector is provided, it means that the budget is for the whole department
            $table->foreignId('sector_id')->nullable()->constrained('sectors')->onDelete('cascade');

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
        Schema::dropIfExists('budget_annuals');
        Schema::dropIfExists('budgets');
    }
};
