<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UiTM Kuala Terengganu Hostel Booking System - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-purple-900 to-indigo-950 min-h-screen flex flex-col items-center justify-center font-sans px-4">

    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md mb-3 text-white text-3xl font-bold shadow-lg">
            🏢
        </div>
        <h1 class="text-white text-2xl font-bold tracking-tight">Hostel Booking System</h1>
        <p class="text-purple-200/70 text-xs mt-1">UiTM Terengganu · Kampus Kuala Terengganu</p>
    </div>

    <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-md border border-white/20">
        
        <div class="flex bg-slate-100 p-1.5 rounded-xl mb-6">
            <button id="studentTab" onclick="switchTab('student')" class="flex-1 text-sm font-semibold py-2.5 rounded-lg transition duration-200 bg-purple-700 text-white shadow-sm">
                🎓 Student Login
            </button>
            <button id="adminTab" onclick="switchTab('admin')" class="flex-1 text-sm font-semibold py-2.5 rounded-lg transition duration-200 text-slate-500 hover:text-slate-800">
                🛡️ Admin / JPK
            </button>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-rose-50 border-l-4 border-rose-500 text-rose-700 text-xs rounded font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/login" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="loginType" id="loginType" value="student">

            <div>
                <label id="idLabel" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Student ID</label>
                <input type="text" name="userID" required placeholder="e.g. 2024669856" 
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="passwordInput" required placeholder="••••••••••••" 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition pr-12">
                    
                    <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs font-semibold focus:outline-none px-2 py-1 rounded hover:bg-slate-100 transition">
                        <span id="toggleText">Show</span>
                    </button>
                </div>
            </div>

            <div id="mfaField" class="hidden">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">MFA Token (6-digit)</label>
                <input type="text" name="mfa_token" placeholder="e.g. 123456" maxlength="6"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition">
                <p class="text-[10px] text-slate-400 mt-1">Demo MFA token requirement: <span class="font-mono bg-slate-100 px-1 rounded">123456</span></p>
            </div>

            <button type="submit" class="w-full mt-2 bg-purple-700 hover:bg-purple-800 text-white font-semibold py-3 rounded-xl shadow-lg shadow-purple-700/20 transition duration-150 transform active:scale-[0.98]">
                Sign In
            </button>
        </form>

        <div class="mt-6 text-center text-[11px] text-slate-400 font-medium">
            UiTM Single Sign-On Secured Portal
        </div>
    </div>

    <script>
        // Tab switcher handling logic
        function switchTab(type) {
            const studentTab = document.getElementById('studentTab');
            const adminTab = document.getElementById('adminTab');
            const idLabel = document.getElementById('idLabel');
            const mfaField = document.getElementById('mfaField');
            const loginType = document.getElementById('loginType');

            if (type === 'student') {
                studentTab.className = "flex-1 text-sm font-semibold py-2.5 rounded-lg transition duration-200 bg-purple-700 text-white shadow-sm";
                adminTab.className = "flex-1 text-sm font-semibold py-2.5 rounded-lg transition duration-200 text-slate-500 hover:text-slate-800";
                idLabel.innerText = "Student ID";
                mfaField.classList.add('hidden');
                loginType.value = "student";
            } else {
                adminTab.className = "flex-1 text-sm font-semibold py-2.5 rounded-lg transition duration-200 bg-purple-700 text-white shadow-sm";
                studentTab.className = "flex-1 text-sm font-semibold py-2.5 rounded-lg transition duration-200 text-slate-500 hover:text-slate-800";
                idLabel.innerText = "Admin ID";
                mfaField.classList.remove('hidden');
                loginType.value = "admin";
            }
        }

        // Visibility toggler handling logic
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('passwordInput');
            const toggleText = document.getElementById('toggleText');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleText.innerText = 'Hide';
            } else {
                passwordInput.type = 'password';
                toggleText.innerText = 'Show';
            }
        }
    </script>
</body>
</html>