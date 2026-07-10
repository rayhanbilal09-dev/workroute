@extends('layouts.app')

@section('content')
<!-- Header Area -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Kelola Akun</h1>
        <p class="text-slate-500 text-sm mt-1">Manajemen akun pengguna — Admin, Worker, dan Client. Hanya dapat diakses oleh Administrator.</p>
    </div>
    <button onclick="document.getElementById('modal-create').classList.remove('hidden')"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-2xl shadow-md shadow-sky-100 transition-all duration-150 self-start sm:self-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Tambah Akun Baru
    </button>
</div>

<!-- Users Table -->
<div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap align-middle">
            <thead>
                <tr class="border-b border-slate-100 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                    <th class="pb-3 px-4">Nama / Username</th>
                    <th class="pb-3 px-4">Email</th>
                    <th class="pb-3 px-4">Role</th>
                    <th class="pb-3 px-4">Bergabung</th>
                    <th class="pb-3 text-right pr-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100/50">
                @foreach($users as $user)
                    <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                        <!-- Name + Avatar -->
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-slate-100 flex-shrink-0" alt="Avatar">
                                @else
                                    <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?d=mp" class="w-9 h-9 rounded-full object-cover ring-2 ring-slate-100 flex-shrink-0" alt="Avatar">
                                @endif
                                <div>
                                    <p class="font-bold text-slate-800 text-sm">{{ $user->name }}</p>
                                    @if($user->id === auth()->id())
                                        <span class="text-[10px] font-semibold text-sky-500 bg-sky-50 px-1.5 py-0.5 rounded-full">Anda</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Email -->
                        <td class="py-4 px-4 text-slate-500 text-xs">{{ $user->email }}</td>

                        <!-- Role Badge -->
                        <td class="py-4 px-4">
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-full uppercase tracking-wider
                                {{ $user->role === 'admin'  ? 'bg-violet-50 text-violet-700 border border-violet-100' : '' }}
                                {{ $user->role === 'worker' ? 'bg-sky-50 text-sky-700 border border-sky-100' : '' }}
                                {{ $user->role === 'client' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : '' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>

                        <!-- Joined Date -->
                        <td class="py-4 px-4 text-slate-400 text-xs">{{ $user->created_at->format('d M Y') }}</td>

                        <!-- Actions -->
                        <td class="py-4 pr-4 text-right">
                            @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus akun {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.');"
                                      class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-semibold rounded-xl transition-colors border border-rose-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-slate-300 italic">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($users->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 text-2xl mb-4">
                <i class="fa-solid fa-users"></i>
            </div>
            <h3 class="text-slate-800 font-bold text-base">Tidak ada akun terdaftar</h3>
        </div>
    @endif
</div>

<!-- ── Create Account Modal ─────────────────────────────────────────────────── -->
<div id="modal-create" class="hidden fixed inset-0 z-[999] flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="document.getElementById('modal-create').classList.add('hidden')"></div>

    <!-- Modal Card -->
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 md:p-8 space-y-5 z-10 border border-slate-100">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Tambah Akun Baru</h2>
            <p class="text-slate-400 text-xs mt-1">Buat akun baru untuk anggota tim. Username/nama akun harus unik.</p>
        </div>

        <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Name -->
            <div class="space-y-1.5">
                <label for="name" class="text-xs font-bold text-slate-600 tracking-wide uppercase">Nama / Username</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                       class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all"
                       placeholder="Nama unik pengguna" required autocomplete="off">
            </div>

            <!-- Email -->
            <div class="space-y-1.5">
                <label for="email" class="text-xs font-bold text-slate-600 tracking-wide uppercase">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all"
                       placeholder="email@contoh.com" required autocomplete="off">
            </div>

            <!-- Role -->
            <div class="space-y-1.5">
                <label for="role" class="text-xs font-bold text-slate-600 tracking-wide uppercase">Role</label>
                <select id="role" name="role" required
                        class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all font-semibold">
                    <option value="worker" {{ old('role') == 'worker' ? 'selected' : '' }}>Worker</option>
                    <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Client</option>
                    <option value="admin"  {{ old('role') == 'admin'  ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <!-- Password -->
            <div class="space-y-1.5">
                <label for="password" class="text-xs font-bold text-slate-600 tracking-wide uppercase">Password</label>
                <input type="password" id="password" name="password"
                       class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all"
                       placeholder="Minimal 8 karakter" required autocomplete="new-password">
            </div>

            <!-- Password Confirmation -->
            <div class="space-y-1.5">
                <label for="password_confirmation" class="text-xs font-bold text-slate-600 tracking-wide uppercase">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all"
                       placeholder="Ulangi password" required autocomplete="new-password">
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modal-create').classList.add('hidden')"
                        class="px-5 py-2.5 rounded-2xl text-sm font-bold text-slate-500 hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-6 py-2.5 bg-sky-500 hover:bg-sky-600 text-white text-sm font-bold rounded-2xl shadow-md shadow-sky-100 transition-colors">
                    Buat Akun
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Auto-open modal if there are validation errors (from previous failed submit)
    @if($errors->any())
        document.getElementById('modal-create').classList.remove('hidden');
    @endif
</script>
@endpush
@endsection
