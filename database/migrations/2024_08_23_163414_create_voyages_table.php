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
        Schema::create('voyages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vessel_id')->constrained()->onDelete('cascade');
            $table->string('code')->unique();
            $table->dateTime('start');
            $table->dateTime('end')->nullable();
            $table->enum('status', ['pending', 'ongoing', 'submitted'])->default('pending');
            $table->decimal('revenues', 8, 2)->nullable();
            $table->decimal('expenses', 8, 2)->nullable();
            $table->decimal('profit', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voyages');
    }
};
