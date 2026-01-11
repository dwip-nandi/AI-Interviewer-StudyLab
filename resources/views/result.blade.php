<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questions</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen p-10">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded shadow">
        <h1 class="text-2xl font-bold text-blue-600 mb-6 border-b pb-2">Your Interview Questions</h1>
        <div class="text-gray-800 whitespace-pre-line leading-relaxed">
            {{ $questions }}
        </div>
        <div class="mt-8">
            <a href="{{ url('/') }}" class="text-blue-500 hover:underline">← Upload another CV</a>
        </div>
    </div>
</body>
</html>