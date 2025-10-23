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
        Schema::create('string_analyzers', function (Blueprint $table) {
            $table->string('id')->primary()->unique();
            $table->string('value')->unique();
            $table->boolean('is_palindrome')->default(false);
            $table->integer('word_count')->default(0);
            $table->integer('length')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('string_analyzers');
    }
};
