<x-layouts.app>
    <x-slot:title>Buat Tugas Rumah Baru</x-slot:title>

    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <a href="{{ route('admin.tugas.index') }}" class="inline-flex items-center gap-1.5 text-xs text-brand-primary hover:text-brand-navy font-semibold mb-2 transition-colors">
                <i class="fa-solid fa-arrow-left text-[11px]"></i> Kembali ke Daftar Tugas
            </a>
            <h1 class="text-2xl font-bold text-neutral-text">Buat Tugas Rumah Baru</h1>
            <p class="text-xs text-neutral-muted mt-1">Buat tugas mandiri untuk siswa dengan instruksi dan batas waktu pengumpulan.</p>
        </div>

        <div class="bg-white border border-neutral-border rounded-2xl p-6 sm:p-8 shadow-xs">
            <form action="{{ route('admin.tugas.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="title" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                        Judul Tugas <span class="text-red-accent">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        placeholder="Contoh: Latihan Soal Penalaran Matematika Bab 1"
                        class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                    @error('title')
                        <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="class_id" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                            Sasaran Kelas <span class="text-red-accent">*</span>
                        </label>
                        <select name="class_id" id="class_id" required
                            class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($classes as $c)
                                <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }} ({{ $c->major?->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subject_id" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                            Mata Pelajaran <span class="text-red-accent">*</span>
                        </label>
                        <select name="subject_id" id="subject_id" required
                            class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                            <option value="">-- Pilih Mapel --</option>
                            @foreach ($subjects as $s)
                                <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }} ({{ $s->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="teacher_id" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                            Guru Pengampu / Penilai <span class="text-red-accent">*</span>
                        </label>
                        <select name="teacher_id" id="teacher_id" required
                            class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                            <option value="">-- Pilih Guru --</option>
                            @foreach ($teachers as $t)
                                <option value="{{ $t->id }}" {{ old('teacher_id') == $t->id ? 'selected' : '' }}>
                                    {{ $t->user?->name }} ({{ $t->specialization_subject }})
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="due_date" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                            Tenggat Waktu (Due Date) <span class="text-red-accent">*</span>
                        </label>
                        <input type="datetime-local" name="due_date" id="due_date" value="{{ old('due_date', now()->addDays(3)->format('Y-m-d\TH:i')) }}" required
                            class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                        @error('due_date')
                            <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="instructions" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                        Instruksi & Soal Tugas <span class="text-red-accent">*</span>
                    </label>
                    <textarea name="instructions" id="instructions" rows="5" required
                        placeholder="Tuliskan petunjuk pengerjaan, format berkas yang diharapkan, atau link materi pendukung..."
                        class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">{{ old('instructions') }}</textarea>
                    @error('instructions')
                        <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-neutral-border">
                    <a href="{{ route('admin.tugas.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-neutral-muted hover:text-neutral-text hover:bg-neutral-subtle transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-all">
                        Simpan & Publikasikan Tugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
