<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Workroute - Issue Tracker' }}</title>
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
    <!-- FontAwesome Free Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.3/css/all.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Custom legacy class styling for backward compatibility with view templates */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1.2;
        }
        .status-unassigned { background-color: #f3f4f6; color: #4b5563; }
        .status-assigned { background-color: #e0f2fe; color: #0284c7; }
        .status-inprogress { background-color: #fef3c7; color: #b55e09; }
        .status-complete { background-color: #d1fae5; color: #065f46; }
        
        .priority-low { color: #10b981; font-weight: 600; }
        .priority-medium { color: #f59e0b; font-weight: 600; }
        .priority-high { color: #ef4444; font-weight: 600; }
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-800 flex flex-col md:flex-row overflow-hidden">

    <!-- Sidebar Navigation -->
    <aside class="w-full md:w-64 bg-white border-b md:border-b-0 md:border-r border-slate-200 flex flex-col shrink-0">
        <!-- Logo Section -->
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-sky-400 to-blue-500 flex items-center justify-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-white">
                        <circle cx="6" cy="19" r="3"/>
                        <path d="M9 19h8.5a3.5 3.5 0 0 0 0-7h-11a3.5 3.5 0 0 1 0-7H15"/>
                        <circle cx="18" cy="5" r="3"/>
                    </svg>
                </div>
                <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-blue-600 tracking-tight">Workroute</span>
            </div>
            <!-- Indicator label -->
            <span class="px-2 py-0.5 text-[10px] font-semibold bg-sky-50 text-sky-600 border border-sky-100 rounded-full">v1.1</span>
        </div>

        <!-- Menu items navigation -->
        <nav class="flex-grow px-4 py-6 overflow-y-auto space-y-7">
            <div>
                <span class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-3">Core Workspace</span>
                <ul class="space-y-1.5">
                    <li>
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-2xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-sky-50 text-sky-600 font-semibold shadow-sm shadow-sky-100/30' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fa-solid fa-chart-pie text-base text-slate-400 {{ request()->routeIs('dashboard') ? 'text-sky-500' : '' }}"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tasks.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-2xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('tasks.*') && !request()->routeIs('tasks.history') ? 'bg-sky-50 text-sky-600 font-semibold shadow-sm shadow-sky-100/30' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fa-solid fa-list-check text-base text-slate-400 {{ request()->routeIs('tasks.*') && !request()->routeIs('tasks.history') ? 'text-sky-500' : '' }}"></i>
                            <span>Tasks Workspace</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tasks.history') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-2xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('tasks.history') ? 'bg-sky-50 text-sky-600 font-semibold shadow-sm shadow-sky-100/30' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fa-solid fa-clock-rotate-left text-base text-slate-400 {{ request()->routeIs('tasks.history') ? 'text-sky-500' : '' }}"></i>
                            <span>Activity History</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <span class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-3">Communication</span>
                <ul class="space-y-1.5">
                    <li>
                        <a href="{{ route('chat.individual') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-2xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('chat.individual') ? 'bg-sky-50 text-sky-600 font-semibold shadow-sm shadow-sky-100/30' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fa-solid fa-paper-plane text-base text-slate-400 {{ request()->routeIs('chat.individual') ? 'text-sky-500' : '' }}"></i>
                            <span>Individual Chat</span>
                        </a>
                    </li>
                    @if(auth()->user()->role !== 'client')
                        <li>
                            <a href="{{ route('chat.group') }}" 
                               class="flex items-center gap-3 px-3 py-2.5 rounded-2xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('chat.group') ? 'bg-sky-50 text-sky-600 font-semibold shadow-sm shadow-sky-100/30' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="fa-solid fa-users text-base text-slate-400 {{ request()->routeIs('chat.group') ? 'text-sky-500' : '' }}"></i>
                                <span>Group Chat</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>

        <!-- User Profile Footer Section -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            <div class="relative group">
                <details class="w-full list-none focus:outline-none">
                    <summary class="flex items-center justify-between gap-3 px-3 py-2 rounded-2xl cursor-pointer list-none select-none hover:bg-slate-100 transition-all duration-150">
                        <div class="flex items-center gap-3 overflow-hidden">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}?v={{ filemtime(storage_path('app/public/' . auth()->user()->avatar)) }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-slate-100 flex-shrink-0" alt="Avatar">
                            @else
                                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim(auth()->user()->email))) }}?d=mp" class="w-10 h-10 rounded-full object-cover ring-2 ring-slate-100 flex-shrink-0" alt="Avatar">
                            @endif
                            <div class="text-left overflow-hidden">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block leading-none mt-0.5">{{ auth()->user()->role }}</span>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-up text-xs text-slate-400 group-open:rotate-180 transition-transform duration-200"></i>
                    </summary>
                    <div class="absolute bottom-full left-0 right-0 mb-2 bg-white border border-slate-200/80 rounded-2xl shadow-xl shadow-slate-100/50 p-2 z-50 flex flex-col gap-1">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                            <i class="fa-solid fa-circle-user text-sm text-slate-400"></i>
                            <span>Lihat Profil</span>
                        </a>
                        <div class="h-px bg-slate-100 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST" class="m-0 w-full">
                            @csrf
                            <button type="submit" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50 w-full text-left transition-colors">
                                <i class="fa-solid fa-arrow-right-from-bracket text-sm text-rose-400"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </details>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-grow flex flex-col min-h-0 overflow-y-auto">
        <div class="max-w-7xl w-full mx-auto p-4 md:p-8 space-y-6">
            
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-800 shadow-sm animate-fade-in">
                    <span class="text-emerald-500 mt-0.5">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-emerald-950">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="flex items-start gap-3 p-4 bg-rose-50 border border-rose-100 rounded-2xl text-rose-800 shadow-sm animate-fade-in">
                    <span class="text-rose-500 mt-0.5">
                        <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-rose-950 mb-1">Terjadi beberapa kendala:</p>
                        <ul class="list-disc list-inside text-xs opacity-90 pl-1 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Page Template Content -->
            @yield('content')
            
        </div>
    </main>

    @stack('scripts')
</body>
</html>
