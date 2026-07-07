<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workroute - Public Issue Tracker</title>
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
    </style>
</head>
<body class="min-h-full flex flex-col text-slate-800">

    <!-- Header Navigation -->
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
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
            
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-950 transition-colors">
                    Login
                </a>
                <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-semibold bg-sky-500 text-white rounded-xl hover:bg-sky-600 shadow-sm shadow-sky-100 transition-colors">
                    Register
                </a>
            </div>
        </div>
    </header>

    <!-- Main public container -->
    <main class="flex-grow max-w-5xl w-full mx-auto px-4 py-8 md:py-12 space-y-8">
        
        <!-- Hero section -->
        <div class="bg-white border border-slate-100 rounded-3xl p-8 md:p-12 text-center shadow-sm relative overflow-hidden">
            <div class="absolute -top-24 -left-20 w-48 h-48 bg-sky-50 rounded-full blur-3xl opacity-60"></div>
            <div class="absolute -bottom-24 -right-20 w-48 h-48 bg-blue-50 rounded-full blur-3xl opacity-60"></div>

            <div class="relative space-y-4">
                <span class="px-3 py-1 bg-sky-50 border border-sky-100 text-sky-600 rounded-full text-xs font-bold uppercase tracking-wider">
                    Task & Issue Tracking
                </span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight max-w-2xl mx-auto leading-tight">
                    Optimalkan pengelolaan issues & melacak progress tim secara real-time
                </h2>
                <p class="text-slate-500 text-sm md:text-base max-w-xl mx-auto leading-relaxed">
                    Workroute mempermudah koordinasi pendaftaran bug, pengembangan fitur baru, dan interaksi langsung antar tim dan klien.
                </p>
                <div class="pt-4 flex items-center justify-center gap-4">
                    <a href="{{ route('login') }}" class="px-6 py-3 font-bold text-sm bg-sky-500 text-white rounded-2xl hover:bg-sky-600 shadow-md shadow-sky-100 transition-colors">
                        Mulai Sekarang
                    </a>
                    <a href="{{ route('register') }}" class="px-6 py-3 font-bold text-sm bg-slate-100 text-slate-700 rounded-2xl hover:bg-slate-200 transition-colors">
                        Buat Akun Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Guest View Notice banner -->
        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 flex items-start gap-3">
            <i class="fa-solid fa-circle-exclamation text-amber-500 text-lg mt-0.5"></i>
            <div class="text-xs md:text-sm text-amber-800">
                <span>Anda sedang mengakses Workroute sebagai <strong>Guest</strong>. Beberapa fitur hanya dapat digunakan setelah login. Silakan </span>
                <a href="{{ route('login') }}" class="font-bold underline hover:text-amber-950">Login disini</a>
                <span> untuk membuat issue, membalas chat, atau meninjau penugasan.</span>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <!-- Total -->
            <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Total Issues</p>
                <span class="text-2xl font-bold text-slate-800 block mt-2">{{ $stats['total'] }}</span>
            </div>
            <!-- Unassigned -->
            <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Unassigned</p>
                <span class="text-2xl font-bold text-slate-500 block mt-2">{{ $stats['unassigned'] }}</span>
            </div>
            <!-- Assigned -->
            <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Assigned</p>
                <span class="text-2xl font-bold text-sky-600 block mt-2">{{ $stats['assigned'] }}</span>
            </div>
            <!-- In Progress -->
            <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">In Progress</p>
                <span class="text-2xl font-bold text-amber-600 block mt-2">{{ $stats['in_progress'] }}</span>
            </div>
            <!-- Complete -->
            <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Complete</p>
                <span class="text-2xl font-bold text-emerald-600 block mt-2">{{ $stats['complete'] }}</span>
            </div>
        </div>

        <!-- Recent Public Issues Table -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-earth-americas text-slate-400"></i> Recent Public Issues
            </h3>
            
            @if($recentIssues->isEmpty())
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <i class="fa-solid fa-cubes text-3xl text-slate-200 mb-2"></i>
                    <p class="text-slate-400 text-sm">Tidak ada issues yang terdaftar saat ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                                <th class="pb-3 pr-4">ID</th>
                                <th class="pb-3 pr-4">Subject</th>
                                <th class="pb-3 pr-4">Type</th>
                                <th class="pb-3 pr-4">Status</th>
                                <th class="pb-3">Priority</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/50">
                            @foreach($recentIssues as $issue)
                                <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                                    <td class="py-3.5 pr-4 font-bold text-slate-700">
                                        #{{ $issue->id }}
                                    </td>
                                    <td class="py-3.5 pr-4 font-medium text-slate-800 max-w-sm truncate">
                                        {{ $issue->subject }}
                                    </td>
                                    <td class="py-3.5 pr-4 text-xs font-semibold text-slate-500">
                                        {{ $issue->type }}
                                    </td>
                                    <td class="py-3.5 pr-4">
                                        <span class="badge status-{{ strtolower(str_replace(' ', '', $issue->status)) }}">
                                            {{ $issue->status }}
                                        </span>
                                    </td>
                                    <td class="py-3.5">
                                        @if($issue->priority == 'Low')
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-xl bg-blue-50 text-blue-600 border border-blue-100/50 shadow-sm" title="Low">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0l6.75-6.75M12 19.5l-6.75-6.75" />
                                                </svg>
                                            </span>
                                        @elseif($issue->priority == 'Medium')
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-xl bg-amber-50 text-amber-600 border border-amber-100/50 shadow-sm" title="Medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                                                </svg>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-xl bg-rose-50 text-rose-600 border border-rose-100/50 shadow-sm" title="High">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5v-15m0 0l-6.75 6.75M12 4.5l6.75 6.75" />
                                                </svg>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </main>

</body>
</html>
