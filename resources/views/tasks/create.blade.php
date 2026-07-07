@extends('layouts.app')

@section('content')
<!-- Back line -->
<div class="mb-4">
    <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-sky-600 hover:text-blue-750 transition-colors">
        <i class="fa-solid fa-chevron-left text-[10px]"></i>
        Back to Task List
    </a>
</div>

<!-- Form Container Card -->
<div class="max-w-2xl bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
    <div>
        <h1 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">Create New Issue</h1>
        <p class="text-slate-400 text-xs mt-1">Daftarkan kendala baru, masalah bug, atau request improvement disini.</p>
    </div>

    <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        
        <!-- Type Selection -->
        <div class="space-y-1.5">
            <label for="type" class="text-xs font-bold text-slate-650 tracking-wide uppercase">Issue Type</label>
            <select id="type" name="type" 
                    class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all font-semibold" required>
                <option value="Bug" {{ old('type') == 'Bug' ? 'selected' : '' }}>Bug</option>
                <option value="Improve" {{ old('type') == 'Improve' ? 'selected' : '' }}>Improve</option>
                <option value="Request" {{ old('type') == 'Request' ? 'selected' : '' }}>Request</option>
            </select>
        </div>

        <!-- Subject Input -->
        <div class="space-y-1.5">
            <label for="subject" class="text-xs font-bold text-slate-650 tracking-wide uppercase">Subject</label>
            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" 
                   class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all" 
                   placeholder="Issue title or summary" required>
        </div>

        <!-- Description Area -->
        <div class="space-y-1.5">
            <label for="description" class="text-xs font-bold text-slate-650 tracking-wide uppercase">Description</label>
            <textarea id="description" name="description" rows="5" 
                      class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all" 
                      placeholder="Describe the issue or error steps in detail..." required>{{ old('description') }}</textarea>
        </div>

        <!-- Admin Only Fields: Priority and Assign To -->
        @if(auth()->user()->isAdmin())
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-slate-100 pt-4">
                
                <div class="space-y-1.5">
                    <label for="priority" class="text-xs font-bold text-slate-650 tracking-wide uppercase">Priority</label>
                    <select id="priority" name="priority" 
                            class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all" required>
                        <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ old('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>High</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="assigned_to" class="text-xs font-bold text-slate-650 tracking-wide uppercase">Assign To (Worker)</label>
                    <select id="assigned_to" name="assigned_to" 
                            class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all">
                        <option value="">-- Select Worker (Optional) --</option>
                        @foreach($workers as $worker)
                            <option value="{{ $worker->id }}" {{ old('assigned_to') == $worker->id ? 'selected' : '' }}>
                                {{ $worker->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
            </div>
        @else
            <!-- Helper Note Card -->
            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex gap-3 items-center">
                <i class="fa-solid fa-circle-info text-sky-500 text-base"></i>
                <p class="text-xs text-slate-500">
                    <strong>Catatan:</strong> Prioritas penanganan otomatis diset ke <em>Low</em> oleh sistem. Prioritas & penugasan hanya dapat diubah oleh Administrator.
                </p>
            </div>
        @endif

        <!-- File Upload Section -->
        <div class="space-y-2 border-t border-slate-100 pt-4">
            <label for="attachments" class="text-xs font-bold text-slate-650 tracking-wide uppercase block">Attachments (Multiple Files)</label>
            <div class="w-full border-2 border-dashed border-slate-200 rounded-2xl p-4 hover:bg-slate-50 transition-colors duration-150 relative flex flex-col items-center justify-center text-center cursor-pointer">
                <i class="fa-solid fa-cloud-arrow-up text-slate-350 text-2xl mb-1.5"></i>
                <p class="text-xs text-slate-500 font-medium">Klik untuk memilih file lampiran</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Mendukung gambar, dokumen, zip, rar</p>
                <input type="file" id="attachments" name="attachments[]" accept="image/*,application/zip,application/x-rar-compressed,application/x-zip-compressed,.zip,.rar" 
                       class="absolute inset-0 opacity-0 cursor-pointer" w-full h-full multiple>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('tasks.index') }}" class="px-5 py-2.5 rounded-2xl text-sm font-bold text-slate-500 hover:bg-slate-50 transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 bg-sky-500 hover:bg-sky-600 text-white text-sm font-bold rounded-2xl shadow-md shadow-sky-100 transition-colors">
                Submit Issue
            </button>
        </div>
    </form>
</div>
@endsection
