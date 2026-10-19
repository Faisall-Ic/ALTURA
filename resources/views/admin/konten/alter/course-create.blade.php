<x-layouts.app>
    <x-slot:title>Tambah Modul ALTER Baru</x-slot:title>

    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <a href="{{ route('admin.alter.index') }}" class="inline-flex items-center gap-1.5 text-xs text-brand-primary hover:text-brand-navy font-semibold mb-2 transition-colors">
                <i class="fa-solid fa-arrow-left text-[11px]"></i> Kembali ke Daftar ALTER
            </a>
            <h1 class="text-2xl font-bold text-neutral-text">Tambah Modul Kursus ALTER</h1>
            <p class="text-xs text-neutral-muted mt-1">Daftarkan modul kursus baru yang mengelompokkan chapter video dan soal essay.</p>
        </div>

        <div class="bg-white border border-neutral-border rounded-2xl p-6 sm:p-8 shadow-xs">
            <form action="{{ route('admin.alter.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="title" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                        Judul Kursus / Modul <span class="text-red-accent">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        placeholder="Contoh: Penalaran Matematika Fundamental"
                        class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                    @error('title')
                        <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="subject_id" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                        Mata Pelajaran Induk <span class="text-red-accent">*</span>
                    </label>
                    <select name="subject_id" id="subject_id" required
                        class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach ($subjects as $s)
                            <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }} ({{ $s->code }}) &bull; {{ $s->is_tka ? 'TKA' : 'SNBT Core' }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                        Deskripsi Singkat Modul
                    </label>
                    <textarea name="description" id="description" rows="3"
                        placeholder="Ringkasan silabus atau tujuan instruksional modul ALTER..."
                        class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">{{ old('description') }}</textarea>
                </div>

                <div class="p-4 rounded-xl border border-neutral-border bg-surface">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_snbt_core" value="1" {{ old('is_snbt_core', '1') == '1' ? 'checked' : '' }}
                            class="w-4 h-4 rounded text-brand-primary focus:ring-brand-primary/50">
                        <div>
                            <span class="text-xs font-bold text-neutral-text">Modul Wajib SNBT Core</span>
                            <span class="text-[11px] text-neutral-muted block mt-0.5">Modul ini akan tampil sebagai kurikulum prioritas bagi seluruh pejuang UTBK.</span>
                        </div>
                    </label>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-neutral-border">
                    <a href="{{ route('admin.alter.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-neutral-muted hover:text-neutral-text hover:bg-neutral-subtle transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-all">
                        Simpan Modul
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
