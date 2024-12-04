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
        Schema::create('objective_quarters', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('quarter');
            $table->string('label');
            $table->decimal('target', 10, 2);
            $table->decimal('accomplishment', 10, 2);
            $table->decimal('utilization_rate', 10, 2);

            // Parent: Objective
            $table->foreignId('objective_id')->constrained('objectives')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objective_quarters');
    }
};
