@extends('layouts.app')

@section('content')
<!-- Back -->
<div class="mb-4">
    <a href="{{ route('chat.group') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-sky-600 hover:text-blue-700 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
        </svg>
        Kembali ke Daftar Grup
    </a>
</div>

<div class="max-w-2xl bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
    <div>
        <h1 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">Buat Grup Chat Baru</h1>
        <p class="text-slate-400 text-xs mt-1">Buat grup khusus dan tambahkan anggota tim secara manual.</p>
    </div>

    <form action="{{ route('chat.group.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <!-- Group Name -->
        <div class="space-y-1.5">
            <label for="name" class="text-xs font-bold text-slate-650 tracking-wide uppercase">Nama Grup</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}"
                   class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all"
                   placeholder="Nama grup chat" required>
        </div>

        <!-- Group Avatar -->
        <div class="space-y-2">
            <label for="avatar" class="text-xs font-bold text-slate-650 tracking-wide uppercase block">Foto Profil Grup <span class="font-normal text-slate-400">(Opsional)</span></label>
            <div class="w-full border-2 border-dashed border-slate-200 rounded-2xl p-4 hover:bg-slate-50 transition-colors relative flex flex-col items-center justify-center text-center cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-300 mb-1.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 5.25h18M3.75 5.25v13.5A2.25 2.25 0 006 21h12a2.25 2.25 0 002.25-2.25V5.25M3.75 5.25A2.25 2.25 0 016 3h12a2.25 2.25 0 012.25 2.25" />
                </svg>
                <p class="text-xs text-slate-500 font-medium">Klik untuk upload foto grup</p>
                <p class="text-[10px] text-slate-400 mt-0.5">JPG, PNG, GIF – maks. 2MB</p>
                <input type="file" id="avatar" name="avatar" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
            </div>
        </div>

        <!-- Members -->
        <div class="space-y-3 border-t border-slate-100 pt-4">
            <label class="text-xs font-bold text-slate-650 tracking-wide uppercase block">Tambah Anggota</label>
            <p class="text-xs text-slate-400 -mt-2">Anda (Admin) otomatis menjadi anggota grup.</p>

            @foreach([['label' => 'Workers', 'list' => $workers, 'icon' => 'fa-user-gear', 'color' => 'sky'], ['label' => 'Clients', 'list' => $clients, 'icon' => 'fa-user-tie', 'color' => 'emerald'], ['label' => 'Admins Lain', 'list' => $admins->where('id', '!=', auth()->id()), 'icon' => 'fa-user-shield', 'color' => 'violet']] as $group)
                @if($group['list']->count() > 0)
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">{{ $group['label'] }}</p>
                        <div class="space-y-2">
                            @foreach($group['list'] as $u)
                                <label class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-slate-50 cursor-pointer transition-colors border border-transparent hover:border-slate-200">
                                    <input type="checkbox" name="members[]" value="{{ $u->id }}" class="rounded-md accent-sky-500 w-4 h-4" {{ old('members') && in_array($u->id, old('members')) ? 'checked' : '' }}>
                                    @if($u->avatar)
                                        <img src="{{ asset('storage/' . $u->avatar) }}" class="w-7 h-7 rounded-full object-cover ring-2 ring-slate-100 flex-shrink-0" alt="">
                                    @else
                                        <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($u->email))) }}?d=mp" class="w-7 h-7 rounded-full object-cover ring-2 ring-slate-100 flex-shrink-0" alt="">
                                    @endif
                                    <span class="text-sm font-medium text-slate-700">{{ $u->name }}</span>
                                    <span class="ml-auto text-xs text-slate-400">{{ ucfirst($u->role) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Submit -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('chat.group') }}" class="px-5 py-2.5 rounded-2xl text-sm font-bold text-slate-500 hover:bg-slate-50 transition-colors">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-sky-500 hover:bg-sky-600 text-white text-sm font-bold rounded-2xl shadow-md shadow-sky-100 transition-colors">
                Buat Grup
            </button>
        </div>
    </form>
</div>
@endsection
