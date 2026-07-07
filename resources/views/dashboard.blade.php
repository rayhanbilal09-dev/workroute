@extends('layouts.app')

@section('content')
<!-- Welcome Header Section -->
<div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between justify-start gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-800">Dashboard</h1>
        <p class="text-slate-500 text-sm mt-1">
            Selamat datang kembali, <strong class="text-slate-700 font-semibold">{{ auth()->user()->name }}</strong>! Anda masuk sebagai <strong class="text-sky-600 font-semibold">{{ ucfirst(auth()->user()->role) }}</strong>.
        </p>
    </div>
    <div class="flex items-center gap-2">
        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs font-semibold text-slate-600">
            <i class="fa-regular fa-calendar-days text-slate-400"></i>
            {{ date('d M Y') }}
        </span>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
    <!-- Stat Card 1: Total -->
    <div class="bg-white border border-slate-100 hover:border-sky-100 hover:shadow-md hover:shadow-sky-100/10 rounded-3xl p-5 shadow-sm transition-all duration-200 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Total Issues</p>
            <span class="text-3xl font-extrabold text-slate-800 block mt-1.5">{{ $stats['total'] }}</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-500 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
            </svg>
        </div>
    </div>

    <!-- Stat Card 2: Unassigned -->
    @if(auth()->user()->role !== 'worker')
        <div class="bg-white border border-slate-100 hover:border-sky-100 hover:shadow-md hover:shadow-sky-100/10 rounded-3xl p-5 shadow-sm transition-all duration-200 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Unassigned</p>
                <span class="text-3xl font-extrabold text-slate-500 block mt-1.5">{{ $stats['unassigned'] }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                </svg>
            </div>
        </div>
    @endif

    <!-- Stat Card 3: Assigned -->
    <div class="bg-white border border-slate-100 hover:border-sky-100 hover:shadow-md hover:shadow-sky-100/10 rounded-3xl p-5 shadow-sm transition-all duration-200 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider block font-medium">Assigned</p>
            <span class="text-3xl font-extrabold text-sky-600 block mt-1.5">{{ $stats['assigned'] }}</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-500 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
    </div>

    <!-- Stat Card 4: In Progress -->
    <div class="bg-white border border-slate-100 hover:border-amber-100 hover:shadow-md hover:shadow-amber-100/10 rounded-3xl p-5 shadow-sm transition-all duration-200 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">In Progress</p>
            <span class="text-3xl font-extrabold text-amber-600 block mt-1.5">{{ $stats['in_progress'] }}</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center">
            <svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>

    <!-- Stat Card 5: Complete -->
    <div class="bg-white border border-slate-100 hover:border-emerald-100 hover:shadow-md hover:shadow-emerald-100/10 rounded-3xl p-5 shadow-sm transition-all duration-200 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider block font-medium">Complete</p>
            <span class="text-3xl font-extrabold text-emerald-600 block mt-1.5">{{ $stats['complete'] }}</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>
</div>

@if(auth()->user()->role === 'admin')
    <!-- Admin Statistics Visual Chart Diagram Placeholder -->
    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
        <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-chart-simple text-sky-500"></i> Live Status Statistics
        </h3>
        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 text-center">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-5">Visualisasi Distribusi Pekerjaan</p>
            
            <div class="flex items-end justify-center gap-6 md:gap-14 h-36">
                <!-- Unassigned Bar -->
                <div class="flex flex-col items-center gap-2 group w-12">
                    <span class="text-xs font-bold text-slate-500 opacity-0 group-hover:opacity-100 transition-opacity duration-150">{{ $stats['unassigned'] }}</span>
                    <div style="height: {{ $stats['total'] > 0 ? max(($stats['unassigned'] / $stats['total']) * 120, 8) : 8 }}px" 
                         class="w-full bg-slate-300 rounded-t-xl transition-all duration-300 group-hover:bg-slate-400" 
                         title="Unassigned ({{ $stats['unassigned'] }})"></div>
                </div>
                <!-- Assigned Bar -->
                <div class="flex flex-col items-center gap-2 group w-12">
                    <span class="text-xs font-bold text-sky-600 opacity-0 group-hover:opacity-100 transition-opacity duration-150">{{ $stats['assigned'] }}</span>
                    <div style="height: {{ $stats['total'] > 0 ? max(($stats['assigned'] / $stats['total']) * 120, 8) : 8 }}px" 
                         class="w-full bg-sky-200 rounded-t-xl transition-all duration-300 group-hover:bg-sky-300" 
                         title="Assigned ({{ $stats['assigned'] }})"></div>
                </div>
                <!-- In Progress Bar -->
                <div class="flex flex-col items-center gap-2 group w-12">
                    <span class="text-xs font-bold text-amber-600 opacity-0 group-hover:opacity-100 transition-opacity duration-150">{{ $stats['in_progress'] }}</span>
                    <div style="height: {{ $stats['total'] > 0 ? max(($stats['in_progress'] / $stats['total']) * 120, 8) : 8 }}px" 
                         class="w-full bg-amber-200 rounded-t-xl transition-all duration-300 group-hover:bg-amber-300" 
                         title="In Progress ({{ $stats['in_progress'] }})"></div>
                </div>
                <!-- Complete Bar -->
                <div class="flex flex-col items-center gap-2 group w-12">
                    <span class="text-xs font-bold text-emerald-600 opacity-0 group-hover:opacity-100 transition-opacity duration-150">{{ $stats['complete'] }}</span>
                    <div style="height: {{ $stats['total'] > 0 ? max(($stats['complete'] / $stats['total']) * 120, 8) : 8 }}px" 
                         class="w-full bg-emerald-200 rounded-t-xl transition-all duration-300 group-hover:bg-emerald-300" 
                         title="Complete ({{ $stats['complete'] }})"></div>
                </div>
            </div>
            
            <div class="h-px bg-slate-200 max-w-lg mx-auto my-4"></div>
            <div class="flex justify-center gap-6 md:gap-14 font-semibold text-slate-400 text-xs">
                <span class="w-12 text-center text-[10px] md:text-xs">Unassigned</span>
                <span class="w-12 text-center text-[10px] md:text-xs text-sky-600">Assigned</span>
                <span class="w-12 text-center text-[10px] md:text-xs text-amber-600">in Progress</span>
                <span class="w-12 text-center text-[10px] md:text-xs text-emerald-600">Complete</span>
            </div>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Grid Left (2/3): Recent Issues -->
    <div class="lg:col-span-2 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-list text-slate-400"></i> Recent Issues Summary
                </h3>
                <a href="{{ route('tasks.index') }}" class="text-xs font-semibold text-sky-600 hover:text-blue-700 hover:underline">
                    View All Tasks &rarr;
                </a>
            </div>
            
            @if($issues->isEmpty())
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <i class="fa-solid fa-circle-nodes text-3xl text-slate-200 mb-2"></i>
                    <p class="text-slate-400 text-sm">No issues found.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap mt-2">
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
                            @foreach($issues->take(5) as $issue)
                                <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                                    <td class="py-3.5 pr-4">
                                        <a href="{{ route('tasks.show', $issue->id) }}" class="font-bold text-sky-600 hover:text-blue-700">
                                            #{{ $issue->id }}
                                        </a>
                                    </td>
                                    <td class="py-3.5 pr-4 font-medium text-slate-700 max-w-[200px] truncate">
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
    </div>

    <!-- Grid Right (1/3): Job History log -->
    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-route text-slate-400"></i> History Pekerjaan
                </h3>
                <a href="{{ route('tasks.history') }}" class="text-xs font-semibold text-sky-600 hover:text-blue-700 hover:underline">
                    Logs &rarr;
                </a>
            </div>

            @if($histories->isEmpty())
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <i class="fa-solid fa-shoe-prints text-3xl text-slate-200 mb-2"></i>
                    <p class="text-slate-400 text-sm">No recent activity logs.</p>
                </div>
            @else
                <ul class="flex flex-col divide-y divide-slate-100/80">
                    @foreach($histories->take(5) as $history)
                        <li class="py-3 first:pt-0 last:pb-0 flex flex-col gap-1 text-xs">
                            <div>
                                <a href="{{ route('tasks.show', $history->issue_id) }}" class="font-bold text-sky-600 hover:underline">#{{ $history->issue_id }}</a>
                                <span class="text-slate-600 ml-1 leading-relaxed">{{ $history->description }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-[10px] text-slate-400 mt-1">
                                <span class="font-semibold px-1.5 py-0.5 bg-slate-50 border border-slate-200/60 rounded-md text-slate-500">
                                    {{ $history->user->name }}
                                </span>
                                <span>&bull;</span>
                                <span>{{ $history->created_at->diffForHumans() }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
