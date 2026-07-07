<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Workroute</title>
    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-tr from-sky-100 via-slate-50 to-blue-50 flex items-center justify-center p-4 relative">
    
    <!-- Background Decorative Bubbles (Aesthetic glassmorphism cues matching the uploaded design colors) -->
    <div class="absolute top-[-10%] right-[-10%] w-[350px] h-[350px] bg-sky-200/50 rounded-full blur-[80px] pointer-events-none select-none"></div>
    <div class="absolute bottom-[-10%] left-[-15%] w-[400px] h-[400px] bg-blue-200/40 rounded-full blur-[100px] pointer-events-none select-none"></div>

    <div class="w-full max-w-md bg-white/70 backdrop-blur-md rounded-3xl shadow-[0_20px_50px_rgba(8,112,184,0.12)] border border-white/60 p-8 flex flex-col transition-all duration-300 relative z-10 my-8">
        
        <!-- Header & Brand Logo -->
        <div class="flex items-center justify-center gap-2.5 mb-4">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-500 to-blue-600 flex items-center justify-center shadow-md shadow-sky-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-white">
                    <circle cx="6" cy="19" r="3"/>
                    <path d="M9 19h8.5a3.5 3.5 0 0 0 0-7h-11a3.5 3.5 0 0 1 0-7H15"/>
                    <circle cx="18" cy="5" r="3"/>
                </svg>
            </div>
            <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-blue-600 tracking-tight">Workroute</span>
        </div>

        <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-sky-50/80 border border-sky-100/60 rounded-full text-xs font-semibold text-sky-600 mb-6 mx-auto select-none shadow-sm shadow-sky-100/10">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5 animate-pulse text-sky-500">
                <path fill-rule="evenodd" d="M9 4.5a.75.75 0 01.721.544l.813 2.846a3.75 3.75 0 002.576 2.576l2.846.813a.75.75 0 010 1.442l-2.846.813a3.75 3.75 0 00-2.576 2.576l-.813 2.846a.75.75 0 01-1.442 0l-.813-2.846a3.75 3.75 0 00-2.576-2.576l-2.846-.813a.75.75 0 010-1.442l2.846-.813a3.75 3.75 0 002.576-2.576l.813-2.846A.75.75 0 019 4.5zM19.301 1.702a.45.45 0 01.696 0l.243.277a1.8 1.8 0 001.07.61l.34.053a.45.45 0 010 .895l-.34.053a1.8 1.8 0 00-1.07.61l-.243.277a.45.45 0 01-.696 0l-.243-.277a1.8 1.8 0 00-1.07-.61l-.34-.053a.45.45 0 010-.895l.34-.053a1.8 1.8 0 001.07-.61l.243-.277z" clip-rule="evenodd" />
            </svg>
            <span>Your Trusted Digital Partner</span>
        </div>

        <h2 class="text-2xl font-semibold text-slate-800 text-center mb-1">Selamat Datang</h2>
        <p class="text-center text-sm text-slate-500 mb-6">Masuk untuk mengelola tugas dan chat dengan tim Anda.</p>

        @if($errors->any())
            <div class="rounded-2xl bg-rose-50 border border-rose-100 text-rose-700 p-4 mb-6 text-sm">
                <div class="flex items-center gap-2 mb-1.5 font-semibold text-rose-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-rose-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    <span>Terdapat kendala masuk:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-xs opacity-90 pl-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </span>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus 
                           placeholder="nama@perusahaan.com"
                           class="w-full rounded-2xl border border-slate-200 bg-white/50 pl-11 pr-4 py-3.5 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-sky-500 focus:ring-4 focus:ring-sky-100 transition-all duration-200" />
                </div>
            </div>

            <!-- Password Input -->
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </span>
                    <input type="password" id="password" name="password" required 
                           placeholder="••••••••"
                           class="w-full rounded-2xl border border-slate-200 bg-white/50 pl-11 pr-12 py-3.5 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-sky-500 focus:ring-4 focus:ring-sky-100 transition-all duration-200" />
                    <button type="button" onclick="togglePassword('password', this)" 
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-650 transition-colors duration-200 focus:outline-none select-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Remember Me checkbox -->
            <div class="flex items-center gap-3 py-1">
                <input type="checkbox" id="remember" name="remember" 
                       class="h-5 w-5 rounded-lg border-slate-200 text-sky-500 focus:ring-sky-400 transition-all duration-150" />
                <label for="remember" class="text-sm font-medium text-slate-600 select-none">Remember Me</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full rounded-2xl bg-gradient-to-r from-sky-500 to-blue-600 px-4 py-3.5 text-white font-semibold shadow-md shadow-sky-500/20 hover:shadow-lg hover:shadow-sky-500/35 hover:-translate-y-[1px] active:translate-y-[1px] transition-all duration-200 focus:outline-none">
                Login
            </button>
        </form>

        <p class="text-center text-sm text-slate-500 mt-6 select-none">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-sky-600 font-semibold hover:text-blue-700 transition duration-150 hover:underline">Daftar sekarang</a>
        </p>

        <!-- Back to Guest Dashboard -->
        <div class="h-px bg-slate-100/85 my-4"></div>
        <div class="text-center">
            <a href="{{ route('guest.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-slate-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                <span>Kembali ke Dashboard Guest</span>
            </a>
        </div>
    </div>

    <!-- Toggle Password Visibility Script -->
    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                button.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                `;
            } else {
                input.type = 'password';
                button.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                `;
            }
        }
    </script>
</body>
</html>
