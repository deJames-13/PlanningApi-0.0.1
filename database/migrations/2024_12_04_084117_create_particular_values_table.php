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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('particular_values');
    }
};
