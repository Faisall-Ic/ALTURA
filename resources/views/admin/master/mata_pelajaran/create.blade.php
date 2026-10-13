<x-layouts.app>
    <x-slot:title>Tambah Mata Pelajaran</x-slot:title>

    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <a href="{{ route('admin.mata-pelajaran.index') }}" class="inline-flex items-center gap-1.5 text-xs text-brand-primary hover:text-brand-navy font-semibold mb-2 transition-colors">
                <i class="fa-solid fa-arrow-left text-[11px]"></i> Kembali ke Daftar Mapel
            </a>
            <h1 class="text-2xl font-bold text-neutral-text">Tambah Mata Pelajaran Baru</h1>
            <p class="text-xs text-neutral-muted mt-1">Daftarkan subtes SNBT atau mata pelajaran TKA akademik ke dalam kurikulum.</p>
        </div>

        <div class="bg-white border border-neutral-border rounded-2xl p-6 sm:p-8 shadow-xs">
            <form action="{{ route('admin.mata-pelajaran.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                        Nama Mata Pelajaran <span class="text-red-accent">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        placeholder="Contoh: Penalaran Matematika atau Fisika"
                        class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                    @error('name')
                        <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="code" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                        Kode Mapel (Unik) <span class="text-red-accent">*</span>
                    </label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}" required
                        placeholder="Contoh: PM, FIS, KIM, LBE"
                        class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs uppercase font-mono focus:outline-none focus:border-brand-primary">
                    @error('code')
                        <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                        Klasifikasi Kurikulum <span class="text-red-accent">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-start gap-3 p-3.5 rounded-xl border border-neutral-border bg-surface cursor-pointer hover:border-brand-primary transition-colors">
                            <input type="radio" name="is_tka" value="0" {{ old('is_tka', '0') == '0' ? 'checked' : '' }}
                                class="mt-0.5 text-brand-primary focus:ring-brand-primary/50">
                            <div>
                                <span class="text-xs font-bold text-neutral-text block">SNBT Core (Wajib)</span>
                                <span class="text-[11px] text-neutral-muted block mt-0.5">Subtes TPS / Literasi yang selalu muncul meski TKA dinonaktifkan di kelas 12.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 p-3.5 rounded-xl border border-neutral-border bg-surface cursor-pointer hover:border-brand-primary transition-colors">
                            <input type="radio" name="is_tka" value="1" {{ old('is_tka') == '1' ? 'checked' : '' }}
                                class="mt-0.5 text-brand-primary focus:ring-brand-primary/50">
                            <div>
                                <span class="text-xs font-bold text-neutral-text block">TKA Akademik (Peminatan)</span>
                                <span class="text-[11px] text-neutral-muted block mt-0.5">Mata pelajaran spesifik jurusan (Fisika, Kimia, Ekonomi). Otomatis disembunyikan jika kelas menonaktifkan TKA.</span>
                            </div>
                        </label>
                    </div>
                    @error('is_tka')
                        <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                        Tautkan ke Jurusan Terkait
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                        @foreach ($majors as $m)
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-neutral-border bg-surface cursor-pointer hover:border-brand-primary transition-colors">
                                <input type="checkbox" name="major_ids[]" value="{{ $m->id }}"
                                    {{ in_array($m->id, old('major_ids', [])) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded text-brand-primary focus:ring-brand-primary/50">
                                <span class="text-xs text-neutral-text font-medium">{{ $m->code }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                        Deskripsi / Cakupan Materi
                    </label>
                    <textarea name="description" id="description" rows="3"
                        placeholder="Keterangan materi dasar atau silabus singkat..."
                        class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">{{ old('description') }}</textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-neutral-border">
                    <a href="{{ route('admin.mata-pelajaran.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-neutral-muted hover:text-neutral-text hover:bg-neutral-subtle transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-all">
                        Simpan Mata Pelajaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
