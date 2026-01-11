<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Session</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6 md:p-12">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-lg border-t-8 border-indigo-600">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Live Interview Room</h2>
            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">Active Session</span>
        </div>

        <div class="mb-8">
            <h3 class="text-lg font-semibold text-indigo-700 mb-2">Interviewer's Question:</h3>
            <div class="p-6 bg-indigo-50 border-l-4 border-indigo-500 rounded-r-lg text-lg italic text-gray-800 leading-relaxed">
                "{{ $question }}"
            </div>
        </div>

        <form action="{{ route('interview.submit_answer') }}" method="POST">
            @csrf
            <input type="hidden" name="question" value="{{ $question }}">

            <div class="mb-6">
                <label for="answer" class="block text-gray-700 font-bold mb-2">Type Your Answer Below:</label>
                <textarea 
                    name="answer" 
                    id="answer" 
                    rows="6" 
                    class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:outline-none text-gray-700 transition"
                    placeholder="Provide your detailed answer here..." 
                    required></textarea>
            </div>

            <div class="flex flex-col gap-3">
                <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-lg font-bold text-lg hover:bg-indigo-700 shadow-md transform active:scale-95 transition">
                    Submit Answer & Get Feedback
                </button>
            </div>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-between items-center">
            <a href="/" class="text-gray-500 hover:text-red-600 font-medium transition flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                </svg>
                End Interview
            </a>
            
            <form action="{{ route('interview.next') }}" method="POST">
                @csrf
                <button type="submit" class="text-indigo-600 hover:text-indigo-800 font-bold transition">
                    Skip / Next Question →
                </button>
            </form>
        </div>
    </div>
</body>
</html>