@extends('layouts.app')

@section('content')
<!-- Header Area -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Tasks Workspace</h1>
        <p class="text-slate-500 text-sm mt-1">Kelola, delegasikan, dan pantau status issue tracker di sini.</p>
    </div>
    
    @if(!auth()->user()->isWorker())
    <div>
        <a href="{{ route('tasks.create') }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-2xl shadow-md shadow-sky-100 transition-all duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Create New Issue</span>
        </a>
    </div>
    @endif
</div>

<!-- Filter & Search Panel -->
<div class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm">
    <form method="GET" action="{{ route('tasks.index') }}" class="space-y-4">
        <!-- Search + Reset row -->
        <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
            <div class="relative flex-grow">
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                </svg>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       placeholder="Cari berdasarkan judul issue..."
                       class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl pl-10 pr-4 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all">
            </div>
            <button type="submit"
                    class="px-5 py-2.5 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-2xl shadow-sm transition-all">
                Cari
            </button>
            @if(request()->hasAny(['search', 'subject', 'priority', 'deadline', 'type', 'status', 'assigned_to']))
            <a href="{{ route('tasks.index') }}"
               class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-2xl transition-all text-center">
                Reset
            </a>
            @endif
        </div>

        <!-- Filter Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 border-t border-slate-100 pt-4">
            <!-- Subject Filter -->
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Subject</label>
                <select name="subject" onchange="this.form.submit()"
                        class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all font-medium">
                    <option value="">Semua</option>
                    <option value="LPM UKP" {{ request('subject') == 'LPM UKP' ? 'selected' : '' }}>LPM UKP</option>
                    <option value="Social Lens" {{ request('subject') == 'Social Lens' ? 'selected' : '' }}>Social Lens</option>
                    <option value="SellerPro" {{ request('subject') == 'SellerPro' ? 'selected' : '' }}>SellerPro</option>
                </select>
            </div>

            <!-- Priority Filter -->
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Priority</label>
                <select name="priority" onchange="this.form.submit()"
                        class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all font-medium">
                    <option value="">Semua</option>
                    <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
                    <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                </select>
            </div>

            <!-- Deadline Filter -->
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Deadline</label>
                <input type="date" name="deadline" value="{{ request('deadline') }}" onchange="this.form.submit()"
                       class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all">
            </div>

            <!-- Type Filter -->
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Type</label>
                <select name="type" onchange="this.form.submit()"
                        class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all font-medium">
                    <option value="">Semua</option>
                    <option value="Bug" {{ request('type') == 'Bug' ? 'selected' : '' }}>Bug</option>
                    <option value="Improve" {{ request('type') == 'Improve' ? 'selected' : '' }}>Improve</option>
                    <option value="Request" {{ request('type') == 'Request' ? 'selected' : '' }}>Request</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status</label>
                <select name="status" onchange="this.form.submit()"
                        class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all font-medium">
                    <option value="">Semua</option>
                    <option value="Unassigned" {{ request('status') == 'Unassigned' ? 'selected' : '' }}>Unassigned</option>
                    <option value="Assigned" {{ request('status') == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Complete" {{ request('status') == 'Complete' ? 'selected' : '' }}>Complete</option>
                </select>
            </div>

            <!-- Assigned To Filter -->
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Assigned To</label>
                <select name="assigned_to" onchange="this.form.submit()"
                        class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all font-medium">
                    <option value="">Semua Worker</option>
                    @foreach($workers as $worker)
                        <option value="{{ $worker->id }}" {{ request('assigned_to') == $worker->id ? 'selected' : '' }}>
                            {{ $worker->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Active Filter Tags -->
        @if(request()->hasAny(['search', 'subject', 'priority', 'deadline', 'type', 'status', 'assigned_to']))
        <div class="flex flex-wrap gap-2 pt-1">
            <span class="text-xs font-semibold text-slate-400">Filter aktif:</span>
            @if(request('search'))
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-sky-50 text-sky-700 text-xs font-semibold rounded-full border border-sky-100">Judul: "{{ request('search') }}"</span>
            @endif
            @if(request('subject'))
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-violet-50 text-violet-700 text-xs font-semibold rounded-full border border-violet-100">Subject: {{ request('subject') }}</span>
            @endif
            @if(request('priority'))
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded-full border border-amber-100">Priority: {{ request('priority') }}</span>
            @endif
            @if(request('deadline'))
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-700 text-xs font-semibold rounded-full border border-rose-100">Deadline: {{ request('deadline') }}</span>
            @endif
            @if(request('type'))
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-full border border-slate-200">Type: {{ request('type') }}</span>
            @endif
            @if(request('status'))
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-full border border-emerald-100">Status: {{ request('status') }}</span>
            @endif
            @if(request('assigned_to'))
                @php $selectedWorker = $workers->firstWhere('id', request('assigned_to')); @endphp
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-cyan-50 text-cyan-700 text-xs font-semibold rounded-full border border-cyan-100">Assigned To: {{ $selectedWorker ? $selectedWorker->name : request('assigned_to') }}</span>
            @endif
        </div>
        @endif
    </form>
</div>

<!-- Task Table Section -->
<div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
    @if($issues->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 text-2xl mb-4">
                <i class="fa-solid fa-clipboard-list"></i>
            </div>
            <h3 class="text-slate-800 font-bold text-base">Tidak ada tugas ditemukan</h3>
            <p class="text-slate-400 text-xs mt-1 max-w-sm">
                Belum ada issue yang dibuat atau sesuai filter saat ini.
            </p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap align-middle">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                        <th class="pb-3 text-center w-20">Type</th>
                        <th class="pb-3 px-4">Issue ID</th>
                        <th class="pb-3 px-4">Subject</th>
                        <th class="pb-3 px-4">Title</th>
                        <th class="pb-3 px-4">Assigned To</th>
                        <th class="pb-3 px-4">Status</th>
                        <th class="pb-3 px-4">Priority</th>
                        <th class="pb-3 text-right w-16">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/50">
                    @foreach($issues as $issue)
                        <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                            <!-- 1. Issue Type Badge -->
                            <td class="py-4 text-center">
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg uppercase tracking-wider 
                                    {{ $issue->type === 'Bug' ? 'bg-rose-50 text-rose-600 border border-rose-100' : '' }}
                                    {{ $issue->type === 'Improve' ? 'bg-sky-50 text-sky-600 border border-sky-100' : '' }}
                                    {{ $issue->type === 'Request' ? 'bg-slate-100 text-slate-600 border border-slate-200' : '' }}">
                                    {{ $issue->type }}
                                </span>
                            </td>
                            
                            <!-- 2. Issue ID -->
                            <td class="py-4 px-4">
                                <a href="{{ route('tasks.show', $issue->id) }}" class="font-bold text-sky-600 hover:text-blue-700">
                                    #{{ $issue->id }}
                                </a>
                            </td>
                            
                            <!-- 3. Subject -->
                            <td class="py-4 px-4">
                                <span class="px-2 py-0.5 rounded-lg text-xs font-semibold
                                    {{ $issue->subject === 'LPM UKP' ? 'bg-violet-50 text-violet-700 border border-violet-100' : '' }}
                                    {{ $issue->subject === 'Social Lens' ? 'bg-teal-50 text-teal-700 border border-teal-100' : '' }}
                                    {{ $issue->subject === 'SellerPro' ? 'bg-orange-50 text-orange-700 border border-orange-100' : '' }}
                                ">
                                    {{ $issue->subject }}
                                </span>
                            </td>
                            
                            <!-- 4. Title (NEW) -->
                            <td class="py-4 px-4 font-medium text-slate-700 max-w-xs truncate" title="{{ $issue->title }}">
                                {{ $issue->title ?? '—' }}
                            </td>
                            
                            <!-- 5. Assigned To -->
                            <td class="py-4 px-4">
                                @if($issue->assignedTo)
                                    <div class="flex items-center gap-2">
                                        @if($issue->assignedTo->avatar)
                                            <img src="{{ asset('storage/' . $issue->assignedTo->avatar) }}" class="w-7 h-7 rounded-full object-cover ring-2 ring-slate-100" alt="Avatar">
                                        @else
                                            <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($issue->assignedTo->email))) }}?d=mp" class="w-7 h-7 rounded-full object-cover ring-2 ring-slate-100" alt="Avatar">
                                        @endif
                                        <span class="text-xs font-semibold text-slate-600">{{ $issue->assignedTo->name }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">Unassigned</span>
                                @endif
                            </td>
                            
                            <!-- 6. Status Badge -->
                            <td class="py-4 px-4">
                                <span class="badge status-{{ strtolower(str_replace(' ', '', $issue->status)) }}">
                                    {{ $issue->status }}
                                </span>
                            </td>
                            
                            <!-- 7. Priority Indicator -->
                            <td class="py-4 px-4">
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
                            
                            <!-- 8. Action Menu -->
                            <td class="py-4 text-right pr-2">
                                <div class="relative inline-block text-left">
                                    <details class="group">
                                        <summary class="list-none select-none p-2 rounded-xl hover:bg-slate-100 text-slate-400 hover:text-slate-700 transition-colors cursor-pointer flex items-center justify-center w-8 h-8">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                                                <path d="M12 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 6a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 6a2 2 0 1 1 0-4 2 2 0 0 1 0 4z" />
                                            </svg>
                                        </summary>
                                        <div class="absolute right-0 top-full mt-1 bg-white border border-slate-200/80 rounded-2xl shadow-xl shadow-slate-200/50 py-1.5 z-50 min-w-[160px] text-left">
                                            
                                            {{-- Lihat Detail: selalu tersedia untuk semua role --}}
                                            <a href="{{ route('tasks.show', $issue->id) }}" 
                                               class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                                <i class="fa-regular fa-eye w-4 text-center text-slate-400"></i>
                                                Lihat Detail
                                            </a>

                                            @if(auth()->user()->isClient())
                                                {{-- Client: Edit & Hapus hanya muncul jika issue miliknya DAN status Unassigned --}}
                                                @if($issue->creator_id == auth()->id() && $issue->status === 'Unassigned')
                                                    <div class="h-px bg-slate-100 mx-3 my-1"></div>
                                                    <a href="{{ route('tasks.edit', $issue->id) }}" 
                                                       class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                                        <i class="fa-regular fa-pen-to-square w-4 text-center text-slate-400"></i>
                                                        Edit Issue
                                                    </a>
                                                    <form action="{{ route('tasks.destroy', $issue->id) }}" method="POST" 
                                                          onsubmit="return confirm('Yakin ingin menghapus issue #{{ $issue->id }}?');" class="m-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 w-full text-left transition-colors">
                                                            <i class="fa-regular fa-trash-can w-4 text-center text-rose-400"></i>
                                                            Hapus Issue
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                {{-- Admin / Worker: gunakan policy @can --}}
                                                @can('update', $issue)
                                                    <div class="h-px bg-slate-100 mx-3 my-1"></div>
                                                    <a href="{{ route('tasks.edit', $issue->id) }}" 
                                                       class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                                        <i class="fa-regular fa-pen-to-square w-4 text-center text-slate-400"></i>
                                                        Edit Issue
                                                    </a>
                                                @endcan
                                                @can('delete', $issue)
                                                    <div class="h-px bg-slate-100 mx-3 my-1"></div>
                                                    <form action="{{ route('tasks.destroy', $issue->id) }}" method="POST" 
                                                          onsubmit="return confirm('Yakin ingin menghapus issue #{{ $issue->id }}?');" class="m-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 w-full text-left transition-colors">
                                                            <i class="fa-regular fa-trash-can w-4 text-center text-rose-400"></i>
                                                            Hapus Issue
                                                        </button>
                                                    </form>
                                                @endcan
                                            @endif
                                        </div>
                                    </details>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
