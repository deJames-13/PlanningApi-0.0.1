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
        Schema::create('particular_values', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->decimal('target', 10, 2);
            $table->decimal('accomplishment', 10, 2);

            // Parent: Particular
            $table->foreignId('particular_id')->constrained('particulars')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('particular_values_quarter', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('quarter');
            $table->decimal('target', 10, 2)->default(0);
            $table->decimal('accomplishment', 10, 2)->default(0);

            $table->foreignId('particular_value_id')->constrained('particular_values')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('particular_values_quarter');
        Schema::dropIfExists('particular_values');
    }
};
