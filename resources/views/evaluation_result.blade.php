<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-lg">
        <h1 class="text-2xl font-bold text-indigo-700 mb-6">AI Evaluation Result</h1>
        
        <div class="mb-6">
            <h3 class="font-bold text-gray-600">Question:</h3>
            <p class="text-gray-800">{{ $question }}</p>
        </div>

        <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 text-gray-700">
            <h3 class="font-bold">AI Feedback & Score:</h3>
            <div class="whitespace-pre-line">{{ $evaluation }}</div>
        </div>

        <div class="flex gap-4 mt-8">
            <form action="{{ route('interview.next') }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-bold hover:bg-indigo-700">
                    Try Next Question →
                </button>
            </form>
            <a href="/" class="flex-1 text-center bg-gray-200 text-gray-700 py-3 rounded-lg font-bold hover:bg-gray-300">End Session</a>
        </div>
    </div>
</body>
</html>