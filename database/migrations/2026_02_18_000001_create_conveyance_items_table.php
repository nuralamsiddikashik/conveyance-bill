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
        Schema::create('conveyance_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conveyance_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('from_place')->nullable();
            $table->string('to_place')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conveyance_items');
    }
};

