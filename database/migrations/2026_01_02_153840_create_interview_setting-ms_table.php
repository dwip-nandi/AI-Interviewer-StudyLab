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
        // Table name is set to 'interview_settings' for consistency
        Schema::create('interview_settings', function (Blueprint $table) {
            $table->id();
            $table->string('sector')->nullable(); // Column to store the sector name
            $table->text('guideline');            // Column to store AI instructions
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the correct table name if migration is rolled back
        Schema::dropIfExists('interview_settings');
    }
};