<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser;
use App\Models\StudySession;
use App\Models\InterviewSetting;

class StudyController extends Controller
{
    /**
     * Show the Study Lab Landing Page
     */
    public function index()
    {
        return view('study.index');
    }

    /**
     * Handle file upload OR direct topic text input
     */
    public function startStudy(Request $request)
    {
        // ভ্যালিডেশন: ফাইল অথবা টেক্সট টপিক যেকোনো একটি থাকতে হবে
        $request->validate([
            'study_material' => 'nullable|mimes:pdf,png,jpg,jpeg,txt|max:5120',
            'study_topic' => 'nullable|string',
            'type' => 'required'
        ]);

        try {
            $text = "";

            // ১. যদি ইউজার ফাইল আপলোড করে
            if ($request->hasFile('study_material')) {
                $file = $request->file('study_material');
                $extension = $file->getClientOriginalExtension();

                if ($extension == 'pdf') {
                    $pdfParser = new Parser();
                    $pdf = $pdfParser->parseFile($file->getPathname());
                    $text = $pdf->getText();
                } elseif ($extension == 'txt') {
                    $text = file_get_contents($file->getRealPath());
                } else {
                    // ইমেজ (png, jpg, jpeg) হলে Groq Vision এপিআই ব্যবহার
                    $text = $this->extractTextFromImage($file);
                }
            } 
            // ২. যদি ইউজার সরাসরি টেক্সট বা টপিক লিখে দেয়
            elseif ($request->filled('study_topic')) {
                $text = $request->study_topic;
            } 
            else {
                return back()->with('error', 'Please upload a file or enter a topic to start.');
            }

            // ডেটাবেসে সেশন সেভ করা
            $session = StudySession::create([
                'type' => $request->type,
                'context_text' => $text,
            ]);

            session(['current_study_id' => $session->id]);
            return redirect()->route('study.lab');

        } catch (\Exception $e) {
            return "System Error: " . $e->getMessage();
        }
    }

    /**
     * ইমেজ থেকে টেক্সট এক্সট্রাক্ট করার মেথড
     */
    private function extractTextFromImage($file)
    {
        $imageData = base64_encode(file_get_contents($file->getRealPath()));
        $apiKey = env('GROQ_API_KEY');

        $response = Http::withOptions(['verify' => false])
            ->withHeaders(['Authorization' => 'Bearer ' . $apiKey])
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.2-11b-vision-preview',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            ['type' => 'text', 'text' => 'Extract all readable text from this image and return only the text.'],
                            ['type' => 'image_url', 'image_url' => ['url' => 'data:image/jpeg;base64,' . $imageData]]
                        ]
                    ]
                ]
            ]);

        return $response->successful() ? $response->json()['choices'][0]['message']['content'] : "Could not read image content.";
    }

    /**
     * ইন্টারাক্টিভ স্টাডি ল্যাব পেজ
     */
    public function studyLab()
    {
        $sessionId = session('current_study_id');
        $sessionData = StudySession::find($sessionId);

        if (!$sessionData) {
            return redirect()->route('study.index');
        }

        // InterviewSetting থেকে অ্যাডমিন গাইডলাইন নেওয়া
        $setting = InterviewSetting::first();
        $guidelines = $setting ? $setting->guideline : "Act as a friendly AI Tutor.";
        $sector = $setting ? $setting->sector : "General Education";

        $prompt = "Context/Topic: " . substr($sessionData->context_text, 0, 1000) . "\n" .
                  "Admin Guidelines: " . $guidelines . "\n" .
                  "Sector: " . $sector . "\n" .
                  "Task: Based on the topic, introduce yourself briefly and ask an initial question to start the discussion.";
        
        $initialQuestion = $this->getAIResponse($prompt);

        return view('study.lab', [
            'context' => $sessionData->context_text,
            'initial_question' => $initialQuestion
        ]);
    }

    /**
     * AJAX এর মাধ্যমে চ্যাট হ্যান্ডেল করা (মার্কিং ও আনলিমিটেড প্রশ্ন)
     */
    public function chat(Request $request)
    {
        $userAnswer = $request->input('answer');
        $sessionId = session('current_study_id');
        $sessionData = StudySession::find($sessionId);
        
        $context = $sessionData ? $sessionData->context_text : "No context provided.";
        $setting = InterviewSetting::first();
        $guidelines = $setting ? $setting->guideline : "Be a helpful tutor.";

        $prompt = "Topic Context: " . substr($context, 0, 1500) . "\n" .
                  "System Guidelines: " . $guidelines . "\n" .
                  "User's Answer: " . $userAnswer . "\n" .
                  "Task: 
                  1. Evaluate the answer accurately.
                  2. Provide a score out of 10.
                  3. Give short constructive feedback.
                  4. Ask the NEXT relevant and challenging question based on the topic.";

        $reply = $this->getAIResponse($prompt);
        return response()->json(['reply' => $reply]);
    }

    /**
     * Groq API কল করার কমন মেথড
     */
    private function getAIResponse($prompt)
    {
        $apiKey = env('GROQ_API_KEY');
        
        $response = Http::withOptions(['verify' => false])->withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'llama-3.3-70b-versatile',
            'messages' => [
                ['role' => 'system', 'content' => 'You are an expert AI Tutor. Always provide scores and feedback for answers, then follow up with a new question.'],
                ['role' => 'user', 'content' => $prompt]
            ]
        ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'];
        }

        return "Sorry, I'm having trouble connecting (Error: " . $response->status() . ")";
    }
}