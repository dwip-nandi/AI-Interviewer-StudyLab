<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser;
use App\Models\InterviewSetting;

class InterviewController extends Controller
{
    /**
     * Admin: Show the interview configuration form.
     */
    public function showAdminSettings()
    {
        $setting = InterviewSetting::first(); 
        return view('admin_settings', compact('setting'));
    }

    /**
     * Admin: Save or update the AI guidelines.
     */
    public function saveAdminSettings(Request $request)
    {
        $request->validate([
            'sector' => 'required',
            'guideline' => 'required'
        ]);

        InterviewSetting::updateOrCreate(
            ['id' => 1],
            [
                'sector' => $request->sector,
                'guideline' => $request->guideline
            ]
        );

        return back()->with('success', 'Settings saved successfully!');
    }

    /**
     * Candidate: Handle CV upload and start the interview session.
     */
    public function processInterview(Request $request)
    {
        $request->validate([
            'cv' => 'required|mimes:pdf|max:2048'
        ]);

        try {
            // 1. Extract text from the PDF
            $pdfParser = new Parser();
            $pdf = $pdfParser->parseFile($request->file('cv')->getPathname());
            $cvText = $pdf->getText();

            // 2. Store CV text in Session for continuous questions
            session(['cv_text' => $cvText]);

            // 3. Call the helper function to get the first question
            return $this->generateQuestion();

        } catch (\Exception $e) {
            return "System Error: " . $e->getMessage();
        }
    }

    /**
     * Helper: Generate a single question using AI (used for "Next" button too).
     */
    public function generateQuestion()
    {
        $cvText = session('cv_text');
        
        // If session is empty, redirect to home
        if (!$cvText) {
            return redirect('/');
        }

        $setting = InterviewSetting::first();
        $systemPrompt = $setting ? $setting->guideline : 'You are a professional technical interviewer.';
        $sectorName = $setting ? $setting->sector : 'General';

        $apiKey = env('GROQ_API_KEY');
        
        $response = Http::withOptions([
            'verify' => false 
        ])->withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'llama-3.3-70b-versatile',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Category: " . $sectorName . ". Guidelines: " . $systemPrompt . " Task: Ask only ONE relevant interview question at a time."
                ],
                [
                    'role' => 'user',
                    'content' => 'Based on this CV, ask me a unique question: ' . $cvText
                ]
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $question = $data['choices'][0]['message']['content'];
            
            // We use a new view 'interview_room' for interactive session
            return view('interview_room', compact('question'));
        }

        return "API Error: " . $response->body();
    }

    // Handle answer submission and get AI feedback
    public function submitAnswer(Request $request)
   {
    $userAnswer = $request->input('answer');
    $question = $request->input('question');
    $cvText = session('cv_text');

    $apiKey = env('GROQ_API_KEY');
    
    $response = Http::withOptions(['verify' => false])->withHeaders([
        'Authorization' => 'Bearer ' . $apiKey,
    ])->post('https://api.groq.com/openai/v1/chat/completions', [
        'model' => 'llama-3.3-70b-versatile',
        'messages' => [
            [
                'role' => 'system', 
                'content' => "You are an expert interviewer. Evaluate the candidate's answer based on the question and CV. Provide: 1. A score out of 10. 2. Short feedback. 3. Specific guidelines for improvement."
            ],
            [
                'role' => 'user', 
                'content' => "Question: $question \n Candidate's Answer: $userAnswer \n CV Context: $cvText"
            ]
        ]
    ]);

    if ($response->successful()) {
        $evaluation = $response->json()['choices'][0]['message']['content'];
        return view('evaluation_result', compact('evaluation', 'question', 'userAnswer'));
    }

    return back()->with('error', 'Evaluation failed.');
   }
}