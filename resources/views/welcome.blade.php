<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Interviewer & Study Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .custom-shadow { shadow-lg shadow-cyan-500/20; }
        .hover-scale { transition: transform 0.3s ease; }
        .hover-scale:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-indigo-100 min-h-screen">
    
    <div class="max-w-7xl mx-auto px-6 py-12 text-center">
        <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 mb-6 tracking-tight">
            Next-Gen <span class="text-indigo-600 italic">AI Platform</span>
        </h1>
        <p class="text-lg md:text-xl text-slate-600 mb-12 max-w-3xl mx-auto leading-relaxed">
            Prepare for interviews with our specialized AI Interviewer or master any topic using our Interactive Voice Study Lab.
        </p>

        <div class="grid lg:grid-cols-3 gap-8">
            
            <div class="bg-white p-8 rounded-3xl shadow-xl border-b-8 border-indigo-600 hover-scale">
                <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 mb-6 mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-3">Admin Panel</h3>
                <p class="text-slate-500 mb-8">Setup interview guidelines, sectors, and AI behavior patterns for both rooms.</p>
                <a href="{{ route('admin.settings') }}" class="block w-full py-4 px-6 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg">Manage Settings</a>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-xl border-b-8 border-emerald-500 hover-scale">
                <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 mb-6 mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-3">Interview Room</h3>
                <p class="text-slate-500 mb-4 text-sm">Upload your CV to start a professional AI interview session.</p>
                <form action="{{ route('interview.upload') }}" method="POST" enctype="multipart/form-data" class="text-left space-y-4">
                    @csrf
                    <input type="file" name="cv" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition"/>
                    <button type="submit" class="w-full py-4 px-6 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/20">Start Interview</button>
                </form>
            </div>

            <div class="bg-slate-900 p-8 rounded-3xl shadow-2xl border-b-8 border-cyan-400 hover-scale text-white">
                <div class="w-16 h-16 bg-cyan-400/10 rounded-2xl flex items-center justify-center text-cyan-400 mb-6 mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m8 0h-8m10-10V5a2 2 0 10-4 0v6a2 2 0 104 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-2">AI Study Lab</h3>
                <p class="text-slate-400 mb-6 text-xs uppercase tracking-widest">Voice & Text Interaction</p>
                
                <form action="{{ route('study.start') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-left">
                    @csrf
                    <input type="hidden" name="type" value="manual">
                    
                    <div>
                        <label class="text-[10px] text-cyan-400 font-bold uppercase mb-1 block">Upload PDF/Image</label>
                        <input type="file" name="study_material" class="block w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-slate-800 file:text-cyan-400 file:font-bold hover:file:bg-slate-700 transition cursor-pointer border border-slate-700 rounded-full"/>
                    </div>

                    <div class="relative py-2 flex items-center">
                        <div class="flex-grow border-t border-slate-800"></div>
                        <span class="flex-shrink mx-4 text-slate-600 text-[10px] font-bold">OR</span>
                        <div class="flex-grow border-t border-slate-800"></div>
                    </div>

                    <div>
                        <label class="text-[10px] text-cyan-400 font-bold uppercase mb-1 block">Type Topic / Note</label>
                        <textarea name="study_topic" rows="2" class="w-full bg-slate-800 border border-slate-700 rounded-xl p-3 text-xs text-white focus:border-cyan-400 outline-none transition placeholder:text-slate-600" placeholder="e.g. My Science Exam Topic..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-4 px-6 bg-cyan-500 text-slate-900 rounded-xl font-bold hover:bg-cyan-400 transition shadow-lg shadow-cyan-500/30 uppercase tracking-tighter">Enter Voice Lab</button>
                </form>
            </div>

        </div>

        <footer class="mt-20 text-slate-400 text-sm border-t border-slate-200 pt-8">
            &copy; 2026 AI Platform Project - Built for Advanced Learning.
        </footer>
    </div>

</body>
</html>