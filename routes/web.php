<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\StudyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Home / Landing Page ---
Route::get('/', function () {
    return view('welcome');
});

// --- Candidate / Mock Interview Routes ---
Route::prefix('interview')->group(function () {
    // CV আপলোড এবং ইন্টারভিউ শুরু
    Route::post('/upload', [InterviewController::class, 'processInterview'])->name('interview.upload');
    
    // পরবর্তী প্রশ্ন জেনারেট করা
    Route::post('/next-question', [InterviewController::class, 'generateQuestion'])->name('interview.next');
    
    // উত্তর সাবমিট এবং মূল্যায়ন
    Route::post('/submit-answer', [InterviewController::class, 'submitAnswer'])->name('interview.submit_answer');
});

// --- Interviewer / Admin Routes ---
Route::prefix('admin')->group(function () {
    Route::get('/settings', [InterviewController::class, 'showAdminSettings'])->name('admin.settings');
    Route::post('/settings', [InterviewController::class, 'saveAdminSettings'])->name('admin.save_settings');
});

// --- AI Study Lab Routes (Voice & Image) ---
Route::prefix('study-lab')->group(function () {
    // স্টাডি ল্যাব হোম এবং ফাইল আপলোড
    Route::get('/', [StudyController::class, 'index'])->name('study.index');
    Route::post('/start', [StudyController::class, 'startStudy'])->name('study.start');
    
    // ইন্টারাক্টিভ ভয়েস ল্যাব
    Route::get('/lab', [StudyController::class, 'studyLab'])->name('study.lab');
    
    // ভয়েস চ্যাটিং রাউট (এটি নতুন যোগ করা হয়েছে)
    Route::post('/chat', [StudyController::class, 'chat'])->name('study.chat');
});