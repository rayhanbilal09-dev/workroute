@extends('layouts.app')

@section('content')
<!-- Back Line -->
<div class="mb-4">
    <a href="{{ route('tasks.show', $issue->id) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-sky-600 hover:text-blue-750 transition-colors">
        <i class="fa-solid fa-chevron-left text-[10px]"></i>
        Back to Issue Detail
    </a>
</div>

<!-- Form Container Card -->
<div class="max-w-2xl bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
    <div>
        <h1 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">Edit Issue #{{ $issue->id }}</h1>
        <p class="text-slate-400 text-xs mt-1">Perbarui detail penugasan, ubah status progress, atau unggah lampiran tambahan.</p>
    </div>

    <form action="{{ route('tasks.update', $issue->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        @if(auth()->user()->isAdmin())
            <!-- Admin Fields: Edit Everything -->
            
            <!-- Type Selection -->
            <div class="space-y-1.5">
                <label for="type" class="text-xs font-bold text-slate-650 tracking-wide uppercase">Issue Type</label>
                <select id="type" name="type" 
                        class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all font-semibold" required>
                    <option value="Bug" {{ old('type', $issue->type) == 'Bug' ? 'selected' : '' }}>Bug</option>
                    <option value="Improve" {{ old('type', $issue->type) == 'Improve' ? 'selected' : '' }}>Improve</option>
                    <option value="Request" {{ old('type', $issue->type) == 'Request' ? 'selected' : '' }}>Request</option>
                </select>
            </div>

            <!-- Subject Selection -->
            <div class="space-y-1.5">
                <label for="subject" class="text-xs font-bold text-slate-650 tracking-wide uppercase">Subject</label>
                <select id="subject" name="subject" required
                        class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all font-semibold">
                    <option value="LPM UKP" {{ old('subject', $issue->subject) == 'LPM UKP' ? 'selected' : '' }}>LPM UKP</option>
                    <option value="Social Lens" {{ old('subject', $issue->subject) == 'Social Lens' ? 'selected' : '' }}>Social Lens</option>
                    <option value="SellerPro" {{ old('subject', $issue->subject) == 'SellerPro' ? 'selected' : '' }}>SellerPro</option>
                </select>
            </div>

            <!-- Title Input -->
            <div class="space-y-1.5">
                <label for="title" class="text-xs font-bold text-slate-650 tracking-wide uppercase">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $issue->title) }}"
                       class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 placeholder:text-slate-450 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all" required>
            </div>

            <!-- Deadline Input -->
            <div class="space-y-1.5">
                <label for="deadline" class="text-xs font-bold text-slate-650 tracking-wide uppercase">Deadline</label>
                <input type="date" id="deadline" name="deadline" value="{{ old('deadline', optional($issue->deadline)->format('Y-m-d')) }}"
                       class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all">
            </div>

            <!-- Description Input -->
            <div class="space-y-1.5">
                <label for="description" class="text-xs font-bold text-slate-650 tracking-wide uppercase">Description</label>
                <textarea id="description" name="description" rows="5" 
                          class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 placeholder:text-slate-450 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all" required>{{ old('description', $issue->description) }}</textarea>
            </div>

            <!-- Meta details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 border-t border-slate-100 pt-4">
                
                <!-- Priority -->
                <div class="space-y-1.5">
                    <label for="priority" class="text-xs font-bold text-slate-650 tracking-wide uppercase">Priority</label>
                    <select id="priority" name="priority" 
                            class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all" required>
                        <option value="Low" {{ old('priority', $issue->priority) == 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ old('priority', $issue->priority) == 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ old('priority', $issue->priority) == 'High' ? 'selected' : '' }}>High</option>
                    </select>
                </div>

                <!-- Assigned To -->
                <div class="space-y-1.5">
                    <label for="assigned_to" class="text-xs font-bold text-slate-650 tracking-wide uppercase">Assign To (Worker)</label>
                    <select id="assigned_to" name="assigned_to" 
                            class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all">
                        <option value="">-- Select Worker --</option>
                        @foreach($workers as $worker)
                            <option value="{{ $worker->id }}" {{ old('assigned_to', $issue->assigned_to) == $worker->id ? 'selected' : '' }}>
                                {{ $worker->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Selection -->
                <div class="space-y-1.5">
                    <label for="status" class="text-xs font-bold text-slate-650 tracking-wide uppercase">Status</label>
                    <select id="status" name="status" 
                            class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all font-semibold" required>
                        <option value="Unassigned" {{ old('status', $issue->status) == 'Unassigned' ? 'selected' : '' }}>Unassigned</option>
                        <option value="Assigned" {{ old('status', $issue->status) == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="In Progress" {{ old('status', $issue->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Complete" {{ old('status', $issue->status) == 'Complete' ? 'selected' : '' }}>Complete</option>
                    </select>
                </div>

            </div>

        @elseif(auth()->user()->isWorker())
            <!-- Worker Fields: ONLY edit Status -->
            <div class="bg-indigo-50/50 border border-indigo-150 rounded-2xl p-4 text-xs text-indigo-900 space-y-2">
                <p class="font-bold text-slate-900 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-exclamation text-indigo-500"></i>
                    Issue Details (Read Only)
                </p>
                <div class="grid grid-cols-2 gap-y-2 mt-2 border-t border-indigo-100/50 pt-2 font-medium text-slate-700">
                    <div>Type: <span class="font-semibold text-slate-900">{{ $issue->type }}</span></div>
                    <div>Subject: <span class="font-semibold text-slate-900">{{ $issue->subject }}</span></div>
                    <div class="col-span-2">Description: <span class="font-semibold text-slate-900">{{ $issue->description }}</span></div>
                </div>
            </div>

            <!-- Worker Option: Status Selection only -->
            <div class="space-y-1.5 border-t border-slate-100 pt-4">
                <label for="status" class="text-xs font-bold text-slate-650 tracking-wide uppercase">Update Progress Status</label>
                <select id="status" name="status" 
                        class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all font-semibold" required>
                    <option value="Assigned" {{ old('status', $issue->status) == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="In Progress" {{ old('status', $issue->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Complete" {{ old('status', $issue->status) == 'Complete' ? 'selected' : '' }}>Complete</option>
                </select>
            </div>

        @elseif(auth()->user()->isClient())
            <!-- Client Fields: Edit details (ONLY if status is Unassigned) -->
            @if($issue->status === 'Unassigned')
                
                <!-- Type Selection -->
                <div class="space-y-1.5">
                    <label for="type" class="text-xs font-bold text-slate-650 tracking-wide uppercase">Issue Type</label>
                    <select id="type" name="type" 
                            class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all font-semibold" required>
                        <option value="Bug" {{ old('type', $issue->type) == 'Bug' ? 'selected' : '' }}>Bug</option>
                        <option value="Improve" {{ old('type', $issue->type) == 'Improve' ? 'selected' : '' }}>Improve</option>
                        <option value="Request" {{ old('type', $issue->type) == 'Request' ? 'selected' : '' }}>Request</option>
                    </select>
                </div>

                <!-- Subject -->
                <div class="space-y-1.5">
                    <label for="subject" class="text-xs font-bold text-slate-650 tracking-wide uppercase">Subject</label>
                    <select id="subject" name="subject" required
                            class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all font-semibold">
                        <option value="LPM UKP" {{ old('subject', $issue->subject) == 'LPM UKP' ? 'selected' : '' }}>LPM UKP</option>
                        <option value="Social Lens" {{ old('subject', $issue->subject) == 'Social Lens' ? 'selected' : '' }}>Social Lens</option>
                        <option value="SellerPro" {{ old('subject', $issue->subject) == 'SellerPro' ? 'selected' : '' }}>SellerPro</option>
                    </select>
                </div>

                <!-- Title -->
                <div class="space-y-1.5">
                    <label for="title" class="text-xs font-bold text-slate-650 tracking-wide uppercase">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $issue->title) }}"
                           class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 placeholder:text-slate-450 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all" required>
                </div>

                <!-- Deadline -->
                <div class="space-y-1.5">
                    <label for="deadline" class="text-xs font-bold text-slate-650 tracking-wide uppercase">Deadline</label>
                    <input type="date" id="deadline" name="deadline" value="{{ old('deadline', optional($issue->deadline)->format('Y-m-d')) }}"
                           class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all">
                </div>

                <!-- Description -->
                <div class="space-y-1.5">
                    <label for="description" class="text-xs font-bold text-slate-650 tracking-wide uppercase">Description</label>
                    <textarea id="description" name="description" rows="5" 
                              class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-3 text-sm text-slate-700 placeholder:text-slate-450 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all" required>{{ old('description', $issue->description) }}</textarea>
                </div>

            @else
                <div class="bg-rose-50 border border-rose-100 text-rose-800 rounded-2xl p-4 flex gap-3 text-sm">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 mt-0.5"></i>
                    <div>
                        <strong>Maaf, Formulir Terkunci:</strong> Anda tidak lagi dapat mengubah detail issue ini karena saat ini status pekerjaan telah diproses/ditugaskan (Status: <strong class="underline">{{ $issue->status }}</strong>).
                    </div>
                </div>
            @endif
        @endif

        <!-- File Upload Section (If not worker, and either admin or unassigned status client) -->
        @if(!auth()->user()->isWorker() && ($issue->status === 'Unassigned' || auth()->user()->isAdmin()))
            <div class="space-y-2 border-t border-slate-100 pt-4">
                <label for="attachments" class="text-xs font-bold text-slate-650 tracking-wide uppercase block">Add More Attachments (Multiple Files)</label>
                <div class="w-full border-2 border-dashed border-slate-200 rounded-2xl p-4 hover:bg-slate-50 transition-colors duration-150 relative flex flex-col items-center justify-center text-center cursor-pointer">
                    <i class="fa-solid fa-plus text-slate-350 text-xl mb-1"></i>
                    <p class="text-xs text-slate-500 font-medium">Klik untuk menambahkan file lampiran baru</p>
                    <input type="file" id="attachments" name="attachments[]" accept="image/*,application/zip,application/x-rar-compressed,application/x-zip-compressed,.zip,.rar" 
                           class="absolute inset-0 opacity-0 cursor-pointer" w-full h-full multiple>
                </div>
            </div>
        @endif

        <!-- Submit Group -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('tasks.show', $issue->id) }}" class="px-5 py-2.5 rounded-2xl text-sm font-bold text-slate-500 hover:bg-slate-50 transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 bg-sky-500 hover:bg-sky-600 text-white text-sm font-bold rounded-2xl shadow-md shadow-sky-100 transition-colors">
                Update Issue
            </button>
        </div>
    </form>
</div>
@endsection
