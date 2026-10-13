<x-layouts.app>
    <x-slot:title>Tambah Guru / Mentor</x-slot:title>

    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <a href="{{ route('admin.guru.index') }}" class="inline-flex items-center gap-1.5 text-xs text-brand-primary hover:text-brand-navy font-semibold mb-2 transition-colors">
                <i class="fa-solid fa-arrow-left text-[11px]"></i> Kembali ke Daftar Guru
            </a>
            <h1 class="text-2xl font-bold text-neutral-text">Tambah Guru & Mentor Baru</h1>
            <p class="text-xs text-neutral-muted mt-1">Daftarkan akun instruktur dan mentor akademik siswa.</p>
        </div>

        <div class="bg-white border border-neutral-border rounded-2xl p-6 sm:p-8 shadow-xs">
            <form action="{{ route('admin.guru.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                        Nama Lengkap & Gelar <span class="text-red-accent">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        placeholder="Contoh: Pak Budi Santoso, M.Pd"
                        class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                    @error('name')
                        <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                            Alamat Email (Login) <span class="text-red-accent">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            placeholder="guru@altura.test"
                            class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                        @error('email')
                            <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                            Kata Sandi Awal <span class="text-red-accent">*</span>
                        </label>
                        <input type="password" name="password" id="password" required
                            placeholder="Minimal 6 karakter"
                            class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                        @error('password')
                            <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nip" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                            NIP / Nomor Induk Pegawai
                        </label>
                        <input type="text" name="nip" id="nip" value="{{ old('nip') }}"
                            placeholder="Contoh: 198501152010011002"
                            class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs font-mono focus:outline-none focus:border-brand-primary">
                        @error('nip')
                            <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="specialization_subject" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                            Mata Pelajaran / Spesialisasi <span class="text-red-accent">*</span>
                        </label>
                        <input type="text" name="specialization_subject" id="specialization_subject" value="{{ old('specialization_subject') }}" required
                            placeholder="Contoh: Matematika & Penalaran Kuantitatif"
                            class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                        @error('specialization_subject')
                            <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-neutral-border">
                    <a href="{{ route('admin.guru.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-neutral-muted hover:text-neutral-text hover:bg-neutral-subtle transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-all">
                        Simpan Guru
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
