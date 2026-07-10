@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Group Chat</h1>
        <p class="text-slate-500 text-sm mt-1">Komunikasi bersama tim dalam grup. Grup utama mencakup semua peran.</p>
    </div>
    @if(auth()->user()->isAdmin())
        <a href="{{ route('chat.group.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-2xl shadow-md shadow-sky-100 transition-all duration-150 self-start sm:self-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Buat Grup Baru
        </a>
    @endif
</div>

<!-- Groups List -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($groups as $group)
        <a href="{{ route('chat.group.show', $group->id) }}"
           class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm hover:shadow-md hover:border-sky-100 transition-all duration-200 flex items-center gap-4 group">
            <!-- Group Avatar -->
            @if($group->avatar)
                <img src="{{ asset('storage/' . $group->avatar) }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-slate-100 flex-shrink-0" alt="Group Avatar">
            @else
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-sky-400 to-blue-500 flex items-center justify-center flex-shrink-0 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
            @endif

            <div class="flex-grow min-w-0">
                <div class="flex items-center gap-2">
                    <p class="font-bold text-slate-800 text-sm truncate group-hover:text-sky-600 transition-colors">{{ $group->name }}</p>
                    @if($group->is_main)
                        <span class="px-2 py-0.5 text-[9px] font-bold bg-sky-50 text-sky-600 border border-sky-100 rounded-full uppercase tracking-wider flex-shrink-0">UTAMA</span>
                    @endif
                </div>
                <p class="text-xs text-slate-400 mt-0.5">{{ $group->users()->count() }} anggota</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Dibuat oleh {{ $group->creator->name }}</p>
            </div>

            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-300 group-hover:text-sky-400 transition-colors flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </a>
    @empty
        <div class="col-span-3 flex flex-col items-center justify-center py-20 text-center bg-white border border-slate-100 rounded-3xl shadow-sm">
            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 text-2xl mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
            </div>
            <h3 class="text-slate-800 font-bold text-base">Belum ada grup</h3>
            <p class="text-slate-400 text-xs mt-1 max-w-xs">Grup akan muncul di sini ketika Anda ditambahkan sebagai anggota.</p>
        </div>
    @endforelse
</div>
@endsection
