<x-layouts.app>
    <x-slot:title>Tambah Siswa Baru</x-slot:title>

    <div class="max-w-3xl mx-auto space-y-6">
        <div>
            <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center gap-1.5 text-xs text-brand-primary hover:text-brand-navy font-semibold mb-2 transition-colors">
                <i class="fa-solid fa-arrow-left text-[11px]"></i> Kembali ke Daftar Siswa
            </a>
            <h1 class="text-2xl font-bold text-neutral-text">Tambah Siswa Baru</h1>
            <p class="text-xs text-neutral-muted mt-1">Daftarkan akun siswa baru, peminatan jurusan, dan alokasi mentor terkunci.</p>
        </div>

        <div class="bg-white border border-neutral-border rounded-2xl p-6 sm:p-8 shadow-xs">
            <form action="{{ route('admin.siswa.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Section: Akun Pengguna -->
                <div class="border-b border-neutral-border pb-5">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-brand-primary mb-4">1. Informasi Akun Login</h2>
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                                Nama Lengkap Siswa <span class="text-red-accent">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                placeholder="Contoh: Muhammad Fikri"
                                class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                            @error('name')
                                <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="email" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                                    Email Siswa <span class="text-red-accent">*</span>
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    placeholder="fikri@altura.test"
                                    class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                                @error('email')
                                    <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                                    Kata Sandi <span class="text-red-accent">*</span>
                                </label>
                                <input type="password" name="password" id="password" required
                                    placeholder="Minimal 6 karakter"
                                    class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                                @error('password')
                                    <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Data Akademik -->
                <div class="border-b border-neutral-border pb-5">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-brand-primary mb-4">2. Penempatan Akademik</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="nisn" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                                NISN Siswa <span class="text-neutral-muted text-[11px] normal-case font-normal">(Opsional)</span>
                            </label>
                            <input type="text" name="nisn" id="nisn" value="{{ old('nisn') }}"
                                placeholder="10 digit NISN (opsional)"
                                class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs font-mono focus:outline-none focus:border-brand-primary">
                            @error('nisn')
                                <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="major_id" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                                Jurusan / Program <span class="text-red-accent">*</span>
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
                            <label for="class_id" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                                Kelas (Rombel) <span class="text-red-accent">*</span>
                            </label>
                            <select name="class_id" id="class_id" required
                                class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($classes as $c)
                                    <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }} ({{ $c->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Bimbingan & Orang Tua -->
                <div class="space-y-4">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-brand-primary">3. Pembimbing & Komunikasi</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="mentor_id" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                                Mentor Resmi (Terkunci) <span class="text-red-accent">*</span>
                            </label>
                            <select name="mentor_id" id="mentor_id" required
                                class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                                <option value="">-- Tetapkan Mentor --</option>
                                @foreach ($teachers as $t)
                                    <option value="{{ $t->id }}" {{ old('mentor_id') == $t->id ? 'selected' : '' }}>
                                        {{ $t->user?->name }} ({{ $t->specialization_subject }})
                                    </option>
                                @endforeach
                            </select>
                            @error('mentor_id')
                                <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="counselor_id" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                                Guru BK Penanggung Jawab <span class="text-red-accent">*</span>
                            </label>
                            <select name="counselor_id" id="counselor_id" required
                                class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                                <option value="">-- Tetapkan Guru BK --</option>
                                @foreach ($counselors as $c)
                                    <option value="{{ $c->id }}" {{ old('counselor_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('counselor_id')
                                <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="parent_phone_number" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                                No. WhatsApp Orang Tua / Wali <span class="text-red-accent">*</span>
                            </label>
                            <input type="text" name="parent_phone_number" id="parent_phone_number" value="{{ old('parent_phone_number') }}" required
                                placeholder="Contoh: 6281234567890"
                                class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs font-mono focus:outline-none focus:border-brand-primary">
                            @error('parent_phone_number')
                                <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center pt-6">
                            <label class="flex items-center gap-3 cursor-pointer select-none">
                                <input type="checkbox" name="status_active" value="1" {{ old('status_active', true) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-neutral-border text-brand-primary focus:ring-brand-primary">
                                <span class="text-xs text-neutral-text font-semibold">
                                    Status Siswa Aktif (Diberikan akses pembelajaran)
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex items-center justify-end gap-3 border-t border-neutral-border">
                    <a href="{{ route('admin.siswa.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-neutral-muted hover:text-neutral-text hover:bg-neutral-subtle transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-all">
                        Simpan Data Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
