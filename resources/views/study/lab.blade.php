<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Voice & Text Study Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glow { animation: pulse 2s infinite; }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(34, 211, 238, 0.7); }
            70% { box-shadow: 0 0 0 20px rgba(34, 211, 238, 0); }
            100% { box-shadow: 0 0 0 0 rgba(34, 211, 238, 0); }
        }
        .scroll-custom::-webkit-scrollbar { width: 6px; }
        .scroll-custom::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-900 text-white min-h-screen flex flex-col items-center p-6">

    <div class="w-full max-w-4xl flex justify-between items-center mb-8">
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 bg-cyan-500 rounded-full animate-ping"></div>
            <span class="text-cyan-400 font-mono tracking-widest uppercase text-xs">AI Tutor Active</span>
        </div>
        <a href="{{ route('study.index') }}" class="text-slate-400 hover:text-red-400 transition text-sm flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg> Exit Lab
        </a>
    </div>

    <div class="w-full max-w-3xl bg-slate-800 rounded-3xl p-8 mb-6 shadow-2xl border border-slate-700">
        <h2 class="text-cyan-500 text-xs font-bold uppercase mb-4 tracking-tighter">AI Tutor Feedback & Question:</h2>
        <div id="ai-response-area" class="text-xl md:text-2xl font-medium leading-relaxed min-h-[100px] scroll-custom">
            {{ $initial_question }}
        </div>
    </div>

    <div class="w-full max-w-3xl space-y-4">
        <div class="relative">
            <textarea id="user-input" rows="3" 
                class="w-full bg-slate-950 border border-slate-700 rounded-2xl p-5 text-lg text-cyan-50 focus:border-cyan-500 outline-none transition-all placeholder:text-slate-600"
                placeholder="Type your answer here or use the mic..."></textarea>
            
            <div id="listening-indicator" class="absolute bottom-4 right-4 hidden">
                <span class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </span>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 items-center">
            <button id="mic-btn" class="flex items-center gap-3 px-6 py-4 bg-slate-800 border border-slate-700 rounded-2xl hover:bg-slate-700 transition-all active:scale-95 group">
                <div class="p-2 bg-cyan-500 rounded-full group-hover:bg-cyan-400 transition">
                    <svg id="mic-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m8 0h-8m10-10V5a2 2 0 10-4 0v6a2 2 0 104 0z" />
                    </svg>
                </div>
                <span id="status-text" class="font-bold text-sm uppercase tracking-wider text-slate-300">Voice Answer</span>
            </button>

            <button onclick="submitToAI()" class="flex-1 w-full py-4 bg-cyan-600 text-slate-900 font-black rounded-2xl hover:bg-cyan-400 transition-all shadow-lg shadow-cyan-900/20 active:scale-95 uppercase tracking-widest">
                Submit & Get Next Question
            </button>

            <button onclick="speakText()" class="p-4 bg-slate-800 border border-slate-700 rounded-2xl hover:bg-slate-700 transition">
                🔊
            </button>
        </div>
    </div>

    <p id="user-transcript" class="mt-4 text-cyan-400/50 text-sm italic"></p>

    

    <script>
        const micBtn = document.getElementById('mic-btn');
        const statusText = document.getElementById('status-text');
        const userInput = document.getElementById('user-input');
        const aiResponseArea = document.getElementById('ai-response-area');
        const listeningIndicator = document.getElementById('listening-indicator');

        // 1. Text-to-Speech (AI Voice)
        function speakText() {
            window.speechSynthesis.cancel(); // আগের কথা বন্ধ করা
            const text = aiResponseArea.innerText;
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.rate = 1.0; 
            utterance.pitch = 1.0;
            window.speechSynthesis.speak(utterance);
        }

        // Auto-speak on Load
        window.onload = () => setTimeout(speakText, 1000);

        // 2. Speech-to-Text (Voice Input)
        if ('webkitSpeechRecognition' in window) {
            const recognition = new webkitSpeechRecognition();
            recognition.continuous = false;
            recognition.lang = 'en-US';

            micBtn.onclick = () => {
                recognition.start();
                statusText.innerText = "Listening...";
                micBtn.classList.add('glow', 'border-cyan-500');
                listeningIndicator.classList.remove('hidden');
            };

            recognition.onresult = (event) => {
                const transcript = event.results[0][0].transcript;
                userInput.value = transcript;
                stopListening();
            };

            recognition.onerror = () => stopListening();
            recognition.onend = () => stopListening();

            function stopListening() {
                statusText.innerText = "Voice Answer";
                micBtn.classList.remove('glow', 'border-cyan-500');
                listeningIndicator.classList.add('hidden');
            }
        }

        // 3. Submit Data via AJAX
        async function submitToAI() {
            const answer = userInput.value.trim();
            if (!answer) {
                alert("Please provide an answer first!");
                return;
            }

            // UI State Change
            aiResponseArea.innerHTML = `<span class="text-slate-500 animate-pulse">Evaluating your answer and preparing next question...</span>`;
            userInput.value = "";
            userInput.disabled = true;

            try {
                const response = await fetch("{{ route('study.chat') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ answer: answer })
                });

                const data = await response.json();
                
                // Display Response & Speak
                aiResponseArea.innerText = data.reply;
                speakText();

            } catch (error) {
                aiResponseArea.innerText = "Error: Could not connect to AI. Please try again.";
            } finally {
                userInput.disabled = false;
            }
        }
    </script>
</body>
</html>