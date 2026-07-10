@extends('layouts.app')

@section('content')
<!-- Back -->
<div class="mb-4">
    <a href="{{ route('chat.group.show', $group->id) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-sky-600 hover:text-blue-700 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
        </svg>
        Kembali ke Grup
    </a>
</div>

<div class="max-w-2xl bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
    <div>
        <h1 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">Edit Grup: {{ $group->name }}</h1>
        <p class="text-slate-400 text-xs mt-1">Perbarui nama grup, foto profil, dan kelola anggota.</p>
    </div>

    <form action="{{ route('chat.group.update', $group->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Group Name -->
        <div class="space-y-1.5">
            <label for="name" class="text-xs font-bold text-slate-650 tracking-wide uppercase">Nama Grup</label>
            <input type="text" id="name" name="name" value="{{ old('name', $group->name) }}"
                   class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all"
                   placeholder="Nama grup chat" required>
        </div>

        <!-- Current Avatar Preview + Change -->
        <div class="space-y-2">
            <label class="text-xs font-bold text-slate-650 tracking-wide uppercase block">Foto Profil Grup</label>
            @if($group->avatar)
                <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-2xl border border-slate-200/80">
                    <img src="{{ asset('storage/' . $group->avatar) }}" class="w-14 h-14 rounded-xl object-cover ring-2 ring-slate-100" alt="Current avatar">
                    <div class="text-xs text-slate-500">
                        <p class="font-semibold text-slate-700 mb-1">Foto saat ini</p>
                        <p>Upload file baru untuk menggantinya</p>
                    </div>
                </div>
            @endif
            <div class="w-full border-2 border-dashed border-slate-200 rounded-2xl p-4 hover:bg-slate-50 transition-colors relative flex flex-col items-center justify-center text-center cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-slate-300 mb-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                </svg>
                <p class="text-xs text-slate-500 font-medium">Klik untuk ganti foto</p>
                <p class="text-[10px] text-slate-400 mt-0.5">JPG, PNG – maks. 2MB</p>
                <input type="file" id="avatar" name="avatar" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
            </div>
        </div>

        <!-- Members (non-main group only) -->
        @if(!$group->is_main)
            <div class="space-y-3 border-t border-slate-100 pt-4">
                <label class="text-xs font-bold text-slate-650 tracking-wide uppercase block">Kelola Anggota</label>
                <p class="text-xs text-slate-400 -mt-2">Anda (Admin) selalu menjadi anggota grup.</p>

                @foreach([['label' => 'Workers', 'list' => $workers], ['label' => 'Clients', 'list' => $clients], ['label' => 'Admins Lain', 'list' => $admins->where('id', '!=', auth()->id())]] as $roleGroup)
                    @if($roleGroup['list']->count() > 0)
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">{{ $roleGroup['label'] }}</p>
                            <div class="space-y-2">
                                @foreach($roleGroup['list'] as $u)
                                    <label class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-slate-50 cursor-pointer transition-colors border border-transparent hover:border-slate-200">
                                        <input type="checkbox" name="members[]" value="{{ $u->id }}"
                                               class="rounded-md accent-sky-500 w-4 h-4"
                                               {{ in_array($u->id, $currentMemberIds) ? 'checked' : '' }}>
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
        @else
            <div class="bg-sky-50 border border-sky-100 rounded-2xl p-4 text-xs text-sky-700 flex items-start gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
                <p><strong>Grup Utama:</strong> Keanggotaan grup utama dikelola otomatis oleh sistem dan mencakup seluruh pengguna terdaftar. Anda hanya dapat mengubah nama dan foto profil grup ini.</p>
            </div>
        @endif

        <!-- Submit -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('chat.group.show', $group->id) }}" class="px-5 py-2.5 rounded-2xl text-sm font-bold text-slate-500 hover:bg-slate-50 transition-colors">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-sky-500 hover:bg-sky-600 text-white text-sm font-bold rounded-2xl shadow-md shadow-sky-100 transition-colors">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
