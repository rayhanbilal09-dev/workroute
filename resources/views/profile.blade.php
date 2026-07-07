@extends('layouts.app')

@section('content')
<div class="space-y-4 mb-6">
    <div>
        <h1 class="text-3xl font-semibold text-slate-900">User Profile</h1>
        <p class="text-slate-600">View and manage your account details.</p>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-[2fr_1fr]">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
        <div class="border-b border-slate-200 pb-5 mb-6">
            <h3 class="text-xl font-semibold text-slate-900">Edit Details</h3>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                <input type="email" value="{{ $user->email }}" disabled class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-500" />
                <p class="text-sm text-slate-500 mt-2">Email address cannot be changed.</p>
            </div>

            <div>
                <label for="avatar" class="block text-sm font-medium text-slate-700 mb-2">Profile Avatar</label>
                <input type="file" id="avatar" name="avatar" accept="image/*" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <p class="text-sm text-slate-500 mt-2">Upload a new profile picture (JPEG, PNG, max 2MB).</p>
            </div>

            <div class="border-t border-slate-200 pt-6">
                <div class="mb-4">
                    <h4 class="text-lg font-semibold text-slate-900">Change Password</h4>
                    <p class="text-sm text-slate-500">Leave password fields empty to keep your current password.</p>
                </div>

                <div class="relative mb-4">
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">New Password</label>
                    <input type="password" id="password" name="password" class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-12 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <button type="button" onclick="togglePassword('password', this)" class="absolute right-3 top-[45px] text-slate-400 hover:text-slate-900">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                </div>

                <div class="relative">
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-12 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute right-3 top-[45px] text-slate-400 hover:text-slate-900">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-6 py-3 text-white font-semibold shadow-sm hover:bg-blue-700">Save Changes</button>
        </form>
    </div>

    <aside class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
        <div class="border-b border-slate-200 pb-5 mb-6">
            <h3 class="text-xl font-semibold text-slate-900">Overview</h3>
        </div>

        <div class="flex flex-col items-center gap-4 text-center">
            @if($user->avatar)
                <div class="relative group">
                    <img src="{{ asset('storage/' . $user->avatar) }}?v={{ filemtime(storage_path('app/public/' . $user->avatar)) }}" 
                         class="h-32 w-32 rounded-full object-cover border-4 border-blue-600 shadow-md" 
                         alt="Avatar">
                </div>
                <!-- Delete Avatar Form -->
                <form action="{{ route('profile.avatar.destroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto profil?');" class="mt-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-semibold rounded-xl border border-rose-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                        <span>Hapus Foto</span>
                    </button>
                </form>
            @else
                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?d=mp&s=120" 
                     class="h-32 w-32 rounded-full object-cover border-4 border-blue-600" 
                     alt="Avatar">
            @endif

            <div>
                <h2 class="text-2xl font-semibold text-slate-900">{{ $user->name }}</h2>
                <span class="inline-flex rounded-full bg-blue-600 px-4 py-1 text-sm font-semibold text-white mt-2">{{ ucfirst($user->role) }}</span>
            </div>
        </div>

        <div class="mt-8 space-y-3 text-slate-700">
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Email</p>
                <p class="font-medium">{{ $user->email }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Member Since</p>
                <p class="font-medium">{{ $user->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </aside>
</div>
@endsection

@push('scripts')
<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');
        if (!input || !icon) return;
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endpush
