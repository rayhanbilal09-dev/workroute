@extends('layouts.app')

@section('content')
<!-- Back -->
<div class="mb-2">
    <a href="{{ route('chat.group') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-sky-600 hover:text-blue-700 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
        </svg>
        Kembali ke Daftar Grup
    </a>
</div>

<div class="flex flex-col lg:flex-row gap-5">

    <!-- ── Chat Panel ──────────────────────────────────────────────────────── -->
    <div class="flex-grow bg-white border border-slate-100 rounded-3xl shadow-sm flex flex-col" style="min-height: 70vh;">

        <!-- Chat Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                @if($group->avatar)
                    <img src="{{ asset('storage/' . $group->avatar) }}" class="w-10 h-10 rounded-2xl object-cover ring-2 ring-slate-100" alt="Group Avatar">
                @else
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-sky-400 to-blue-500 flex items-center justify-center shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                    </div>
                @endif
                <div>
                    <h2 class="text-base font-bold text-slate-800">{{ $group->name }}</h2>
                    <p class="text-xs text-slate-400">{{ $members->count() }} anggota
                        @if($group->is_main)<span class="ml-1 px-1.5 py-0.5 text-[9px] font-bold bg-sky-50 text-sky-600 border border-sky-100 rounded-full uppercase">UTAMA</span>@endif
                    </p>
                </div>
            </div>

            @if(auth()->user()->isAdmin())
                <div class="flex items-center gap-2">
                    <a href="{{ route('chat.group.edit', $group->id) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                        Edit Grup
                    </a>
                    @if(!$group->is_main)
                        <form action="{{ route('chat.group.destroy', $group->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus grup ini?');" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-semibold rounded-xl transition-colors border border-rose-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                Hapus Grup
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>

        <!-- Messages Area -->
        <div id="msg-window" class="flex-grow overflow-y-auto px-6 py-4 space-y-3" style="max-height: 52vh;">
            @if($messages->isEmpty())
                <div class="flex items-center justify-center h-full">
                    <p class="text-slate-400 text-sm text-center">Belum ada pesan. Mulai percakapan!</p>
                </div>
            @else
                @foreach($messages as $msg)
                    <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }} gap-2.5">
                        @if($msg->sender_id !== auth()->id())
                            @if($msg->sender->avatar)
                                <img src="{{ asset('storage/' . $msg->sender->avatar) }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-slate-100 flex-shrink-0 mt-1" alt="">
                            @else
                                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($msg->sender->email))) }}?d=mp" class="w-8 h-8 rounded-full object-cover ring-2 ring-slate-100 flex-shrink-0 mt-1" alt="">
                            @endif
                        @endif

                        <div class="max-w-[70%]">
                            @if($msg->sender_id !== auth()->id())
                                <p class="text-[10px] font-bold text-slate-500 mb-1 ml-1">
                                    {{ $msg->sender->name }}
                                    <span class="font-normal text-slate-400">({{ ucfirst($msg->sender->role) }})</span>
                                </p>
                            @endif
                            <div class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed
                                {{ $msg->sender_id === auth()->id()
                                    ? 'bg-sky-500 text-white rounded-tr-none'
                                    : 'bg-slate-100 text-slate-800 rounded-tl-none' }}">
                                {{ $msg->message }}
                            </div>
                            <div class="flex items-center gap-2 mt-1 {{ $msg->sender_id === auth()->id() ? 'justify-end' : '' }}">
                                <span class="text-[10px] text-slate-400">{{ $msg->created_at->format('d M, H:i') }}</span>
                                @if($msg->sender_id === auth()->id())
                                    <!-- Edit -->
                                    <details class="inline-block relative">
                                        <summary class="cursor-pointer text-[10px] text-slate-400 hover:text-sky-500 list-none select-none">✎</summary>
                                        <div class="absolute right-0 bottom-full mb-2 bg-white border border-slate-200 rounded-2xl shadow-xl p-3 min-w-[260px] z-50">
                                            <form action="{{ route('chat.update', $msg->id) }}" method="POST" class="flex gap-2">
                                                @csrf @method('PUT')
                                                <input type="text" name="message" value="{{ $msg->message }}"
                                                       class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500" required>
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-500 text-white text-xs font-bold rounded-xl">Simpan</button>
                                            </form>
                                        </div>
                                    </details>
                                    <!-- Delete -->
                                    <form action="{{ route('chat.destroy', $msg->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus pesan ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-[10px] text-slate-400 hover:text-rose-500 transition-colors">✕</button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        @if($msg->sender_id === auth()->id())
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-slate-100 flex-shrink-0 mt-1" alt="">
                            @else
                                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim(auth()->user()->email))) }}?d=mp" class="w-8 h-8 rounded-full object-cover ring-2 ring-slate-100 flex-shrink-0 mt-1" alt="">
                            @endif
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Message Input -->
        <div class="px-6 py-4 border-t border-slate-100">
            <form action="{{ route('chat.group.send', $group->id) }}" method="POST" class="flex gap-3">
                @csrf
                <input type="text" name="message" id="group-msg-input"
                       placeholder="Tulis pesan untuk grup ini..."
                       class="flex-grow bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all"
                       required autocomplete="off" autofocus>
                <button type="submit"
                        class="px-5 py-2.5 bg-sky-500 hover:bg-sky-600 text-white text-sm font-bold rounded-2xl shadow-sm transition-all flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                    Kirim
                </button>
            </form>
        </div>
    </div>

    <!-- ── Members Sidebar ─────────────────────────────────────────────────── -->
    <div class="lg:w-60 bg-white border border-slate-100 rounded-3xl shadow-sm p-5 h-fit">
        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
            </svg>
            Anggota ({{ $members->count() }})
        </h3>
        <ul class="space-y-3">
            @foreach($members as $member)
                <li class="flex items-center gap-2.5">
                    @if($member->avatar)
                        <img src="{{ asset('storage/' . $member->avatar) }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-slate-100 flex-shrink-0" alt="">
                    @else
                        <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($member->email))) }}?d=mp" class="w-8 h-8 rounded-full object-cover ring-2 ring-slate-100 flex-shrink-0" alt="">
                    @endif
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-slate-700 truncate">{{ $member->name }}</p>
                        <span class="text-[10px] font-medium text-slate-400">{{ ucfirst($member->role) }}</span>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-scroll to bottom of message window
    const msgWindow = document.getElementById('msg-window');
    if (msgWindow) {
        msgWindow.scrollTop = msgWindow.scrollHeight;
    }
</script>
@endpush
