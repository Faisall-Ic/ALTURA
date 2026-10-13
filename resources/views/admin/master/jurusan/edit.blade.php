<x-layouts.app>
    <x-slot:title>Edit Jurusan</x-slot:title>

    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <a href="{{ route('admin.jurusan.index') }}" class="inline-flex items-center gap-1.5 text-xs text-brand-primary hover:text-brand-navy font-semibold mb-2 transition-colors">
                <i class="fa-solid fa-arrow-left text-[11px]"></i> Kembali ke Daftar Jurusan
            </a>
            <h1 class="text-2xl font-bold text-neutral-text">Edit Jurusan: {{ $major->name }}</h1>
            <p class="text-xs text-neutral-muted mt-1">Perbarui konfigurasi jurusan akademik.</p>
        </div>

        <div class="bg-white border border-neutral-border rounded-2xl p-6 sm:p-8 shadow-xs">
            <form action="{{ route('admin.jurusan.update', $major) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                        Nama Jurusan <span class="text-red-accent">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $major->name) }}" required
                        class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                    @error('name')
                        <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="code" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                        Kode Jurusan (Unik) <span class="text-red-accent">*</span>
                    </label>
                    <input type="text" name="code" id="code" value="{{ old('code', $major->code) }}" required
                        class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs uppercase font-mono focus:outline-none focus:border-brand-primary">
                    @error('code')
                        <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_snbt_mandatory" value="1" {{ old('is_snbt_mandatory', $major->is_snbt_mandatory) ? 'checked' : '' }}
                            class="w-4 h-4 rounded bg-surface border-neutral-border text-brand-primary focus:ring-brand-primary/50">
                        <span class="text-xs text-neutral-text font-medium">
                            Mandatori Kurikulum & Tryout SNBT (Wajib ikut serta)
                        </span>
                    </label>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-neutral-border">
                    <a href="{{ route('admin.jurusan.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-neutral-muted hover:text-neutral-text hover:bg-neutral-subtle transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-all">
                        Perbarui Jurusan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
