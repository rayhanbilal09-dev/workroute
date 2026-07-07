@extends('layouts.app')

@section('content')
<!-- Header Area -->
<div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Activity Logs</h1>
        <p class="text-slate-500 text-sm mt-1">Jejak audit seluruh aktivitas, transisi status, dan penugasan issue.</p>
    </div>
</div>

<!-- History Log Container -->
<div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
    @if($histories->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 text-2xl mb-4">
                <i class="fa-solid fa-timeline"></i>
            </div>
            <h3 class="text-slate-800 font-bold text-base">Tidak ada log aktivitas</h3>
            <p class="text-slate-400 text-xs mt-1 max-w-sm">
                Belum ada rekaman riwayat pekerjaan yang tercatat dalam log audit sistem saat ini.
            </p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap align-middle">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                        <th class="pb-3 px-4">Waktu & Tanggal</th>
                        <th class="pb-3 px-4 text-center">Issue ID</th>
                        <th class="pb-3 px-4">User Pelaksana</th>
                        <th class="pb-3 px-4 text-center">Aksi / Operasi</th>
                        <th class="pb-3 px-4">Deskripsi Aktivitas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/50">
                    @foreach($histories as $history)
                        <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                            <!-- 1. Timestamp -->
                            <td class="py-4 px-4 text-xs font-medium text-slate-650">
                                <div class="flex flex-col">
                                    <span class="text-slate-700 font-semibold">{{ $history->created_at->format('d M Y, H:i') }}</span>
                                    <span class="text-slate-400 text-[10px]">{{ $history->created_at->diffForHumans() }}</span>
                                </div>
                            </td>
                            
                            <!-- 2. Issue Link -->
                            <td class="py-4 px-4 text-center">
                                @if($history->issue)
                                    <a href="{{ route('tasks.show', $history->issue_id) }}" class="font-bold text-sky-600 hover:text-blue-700">
                                        #{{ $history->issue_id }}
                                    </a>
                                @else
                                    <span class="line-through text-slate-400 italic text-xs">#{{ $history->issue_id }}</span>
                                @endif
                            </td>
                            
                            <!-- 3. User & Role -->
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800 text-xs">{{ $history->user->name }}</span>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-0.5">{{ $history->user->role }}</span>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- 4. Action Pill Badge -->
                            <td class="py-4 px-4 text-center">
                                <span class="px-2 py-0.5 text-[9px] font-bold border rounded-md uppercase tracking-wider
                                    {{ in_array(strtolower($history->action), ['create', 'created']) ? 'bg-sky-50 text-sky-600 border-sky-100' : '' }}
                                    {{ in_array(strtolower($history->action), ['update', 'updated']) ? 'bg-amber-50 text-amber-600 border-amber-100' : '' }}
                                    {{ in_array(strtolower($history->action), ['delete', 'deleted']) ? 'bg-rose-50 text-rose-600 border-rose-100' : '' }}
                                    {{ !in_array(strtolower($history->action), ['create', 'created', 'update', 'updated', 'delete', 'deleted']) ? 'bg-slate-50 text-slate-600 border-slate-200' : '' }}">
                                    {{ $history->action }}
                                </span>
                            </td>
                            
                            <!-- 5. Description Content -->
                            <td class="py-4 px-4 text-xs text-slate-600 max-w-sm truncate" title="{{ $history->description }}">
                                {{ $history->description }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="mt-6 border-t border-slate-100 pt-4">
            {{ $histories->links() }}
        </div>
    @endif
</div>
@endsection
