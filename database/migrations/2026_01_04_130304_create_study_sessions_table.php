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
        Schema::create('study_sessions', function (Blueprint $table) {
            $table->id();
            // ফাইলের ধরণ রাখার জন্য (যেমন: image, pdf, txt বা manual_topic)
            $table->string('type')->nullable(); 
            
            // ফাইল বা ইমেজ থেকে যা লেখা বের হবে সেটা সেভ করার জন্য
            $table->longText('context_text')->nullable(); 
            
            // এআই যদি কোনো সামারি বা স্পেশাল নোট দেয় সেটা রাখার জন্য
            $table->text('ai_summary')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_sessions');
    }
};