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
        Schema::create('spin_participants', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('name');
            $table->string('city');
            $table->boolean('is_win')->default(false);
            $table->string('fill_style');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spin_participants');
    }
};
