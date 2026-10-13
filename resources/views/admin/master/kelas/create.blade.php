<x-layouts.app>
    <x-slot:title>Tambah Kelas</x-slot:title>

    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center gap-1.5 text-xs text-brand-primary hover:text-brand-navy font-semibold mb-2 transition-colors">
                <i class="fa-solid fa-arrow-left text-[11px]"></i> Kembali ke Daftar Kelas
            </a>
            <h1 class="text-2xl font-bold text-neutral-text">Tambah Rombongan Belajar (Kelas) Baru</h1>
            <p class="text-xs text-neutral-muted mt-1">Daftarkan kelas baru dan tautkan ke jurusan induk yang relevan.</p>
        </div>

        <div class="bg-white border border-neutral-border rounded-2xl p-6 sm:p-8 shadow-xs">
            <form action="{{ route('admin.kelas.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="major_id" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                        Pilih Jurusan Induk <span class="text-red-accent">*</span>
                    </label>
                    <select name="major_id" id="major_id" required
                        class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach ($majors as $m)
                            <option value="{{ $m->id }}" {{ old('major_id') == $m->id ? 'selected' : '' }}>
                                {{ $m->name }} ({{ $m->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('major_id')
                        <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                        Nama Kelas <span class="text-red-accent">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        placeholder="Contoh: 12 MIPA 1"
                        class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                    @error('name')
                        <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="code" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                        Kode Kelas (Unik) <span class="text-red-accent">*</span>
                    </label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}" required
                        placeholder="Contoh: 12-MIPA-01"
                        class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs uppercase font-mono focus:outline-none focus:border-brand-primary">
                    @error('code')
                        <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-neutral-border">
                    <a href="{{ route('admin.kelas.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-neutral-muted hover:text-neutral-text hover:bg-neutral-subtle transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-all">
                        Simpan Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
