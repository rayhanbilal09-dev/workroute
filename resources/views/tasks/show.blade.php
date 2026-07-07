@extends('layouts.app')

@section('content')
<!-- Back navigation -->
<div class="mb-4">
    <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-sky-600 hover:text-blue-700 transition-colors">
        <i class="fa-solid fa-chevron-left text-[10px]"></i>
        Back to Task List
    </a>
</div>

<!-- Main Details Layout grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column (2/3): Title, description, attachments -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Header container -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-4">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg uppercase tracking-wider 
                        {{ $issue->type === 'Bug' ? 'bg-rose-50 text-rose-600 border border-rose-100' : '' }}
                        {{ $issue->type === 'Improve' ? 'bg-sky-50 text-sky-600 border border-sky-100' : '' }}
                        {{ $issue->type === 'Request' ? 'bg-slate-100 text-slate-600 border border-slate-200' : '' }}">
                        {{ $issue->type }}
                    </span>
                    <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight mt-3">
                        <span class="text-slate-450 font-medium">#{{ $issue->id }}:</span> {{ $issue->subject }}
                    </h1>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    @can('update', $issue)
                        <a href="{{ route('tasks.edit', $issue->id) }}" 
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-xs font-bold rounded-xl shadow-sm shadow-sky-100/50 transition-colors">
                            <i class="fa-regular fa-pen-to-square"></i>
                            <span>Edit Issue</span>
                        </a>
                    @endcan

                    @can('delete', $issue)
                        <form action="{{ route('tasks.destroy', $issue->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus issue ini?');" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 text-xs font-bold rounded-xl transition-colors border border-rose-100">
                                <i class="fa-regular fa-trash-can"></i>
                                <span>Delete</span>
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
            
            <div class="border-t border-slate-100 pt-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description</h3>
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 md:p-5 text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                    {{ $issue->description }}
                </div>
            </div>
        </div>

        <!-- Attachments Card -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-4">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-3 flex items-center gap-2">
                <i class="fa-solid fa-paperclip text-sm text-slate-400"></i> Attachments
            </h3>
            
            @if($issue->attachments->isEmpty())
                <div class="py-6 text-center text-slate-400 text-xs italic">
                    Belum ada berkas lampiran yang diunggah untuk issue ini.
                </div>
            @else
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($issue->attachments as $attachment)
                        <li class="border border-slate-100 bg-slate-50/50 rounded-2xl p-3 flex items-center justify-between gap-3 hover:bg-slate-50 transition-colors duration-150">
                            <div class="overflow-hidden">
                                <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="font-bold text-xs text-sky-600 hover:text-blue-700 truncate block">
                                    {{ $attachment->file_name }}
                                </a>
                                <small class="text-[10px] text-slate-400 block mt-0.5">Uploaded {{ $attachment->created_at->format('d M Y, H:i') }}</small>
                            </div>
                            
                            <!-- Image preview if image file extension -->
                            @if(in_array(strtolower(pathinfo($attachment->file_name, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="shrink-0">
                                    <img src="{{ asset('storage/' . $attachment->file_path) }}" class="max-h-11 rounded-lg border border-slate-200/50 shadow-sm" alt="Preview">
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Right Column (1/3): Sidebar Info & Activity Logs -->
    <div class="space-y-6">
        <!-- Metadata Info box -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-4">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-3">Details Meta</h3>
            
            <div class="divide-y divide-slate-100 text-xs">
                <div class="py-3 flex justify-between items-center">
                    <span class="font-semibold text-slate-400">Status</span>
                    <span class="badge status-{{ strtolower(str_replace(' ', '', $issue->status)) }}">
                        {{ $issue->status }}
                    </span>
                </div>
                
                <div class="py-3 flex justify-between items-center">
                    <span class="font-semibold text-slate-400">Priority</span>
                    @if($issue->priority == 'Low')
                        <span class="inline-flex items-center gap-1.5 font-bold text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0l6.75-6.75M12 19.5l-6.75-6.75" />
                            </svg>
                            Low
                        </span>
                    @elseif($issue->priority == 'Medium')
                        <span class="inline-flex items-center gap-1.5 font-bold text-amber-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                            </svg>
                            Medium
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 font-bold text-rose-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5v-15m0 0l-6.75 6.75M12 4.5l6.75 6.75" />
                            </svg>
                            High
                        </span>
                    @endif
                </div>

                <div class="py-3 flex justify-between items-center">
                    <span class="font-semibold text-slate-400">Created By</span>
                    <span class="font-bold text-slate-700 bg-slate-50 border border-slate-200/50 rounded-md px-1.5 py-0.5 text-[10px]">{{ $issue->creator->name }} (Client)</span>
                </div>

                <div class="py-3 flex justify-between items-center">
                    <span class="font-semibold text-slate-400">Assigned To</span>
                    @if($issue->assignedTo)
                        <span class="font-bold text-slate-705 text-[10px]">{{ $issue->assignedTo->name }}</span>
                    @else
                        <span class="italic text-slate-400">Unassigned</span>
                    @endif
                </div>

                <div class="py-3 flex justify-between items-center">
                    <span class="font-semibold text-slate-400">Reported</span>
                    <span class="text-slate-500 font-medium text-[10px]" title="{{ $issue->created_at->format('d M Y, H:i') }}">
                        {{ $issue->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Mini Activity timeline log -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-4">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-3 flex items-center gap-2">
                <i class="fa-solid fa-route text-sm text-slate-450"></i> Activity Log
            </h3>
            
            @if($issue->histories->isEmpty())
                <div class="py-4 text-center text-slate-400 text-xs italic">
                    Belum ada log aktivitas penugasan.
                </div>
            @else
                <ul class="flex flex-col gap-4 text-xs">
                    @foreach($issue->histories as $history)
                        <li class="relative pl-4 border-l-2 border-slate-100 last:border-0 pb-1">
                            <!-- timeline dot marker -->
                            <div class="absolute -left-[5px] top-1 w-2.5 h-2.5 rounded-full bg-slate-200"></div>
                            
                            <div>
                                <strong class="text-slate-800">{{ $history->user->name }}</strong>
                                <span class="text-slate-650 ml-0.5 leading-relaxed">{{ $history->description }}</span>
                            </div>
                            <div class="text-[10px] text-slate-400 mt-1">
                                {{ $history->created_at->format('d M Y, H:i') }} ({{ $history->created_at->diffForHumans() }})
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        
    </div>

</div>
@endsection
