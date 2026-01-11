<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - AI Interviewer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded shadow-lg">
        <h1 class="text-2xl font-bold mb-6 text-indigo-600">Interviewer Configuration</h1>
        
        <form action="{{ route('admin.save_settings') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block font-semibold">Interview Sector:</label>
                <input type="text" name="sector" value="{{ $setting->sector ?? '' }}" class="w-full p-2 border rounded" placeholder="e.g., Banking, IT, Medical">
            </div>

            <div class="mb-4">
                <label class="block font-semibold">AI System Prompt (Guidelines):</label>
                <textarea name="guideline" rows="6" class="w-full p-2 border rounded" placeholder="Tell AI how to behave...">{{ $setting->guideline ?? '' }}</textarea>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">Save Configuration</button>
        </form>
    </div>
</body>
</html>