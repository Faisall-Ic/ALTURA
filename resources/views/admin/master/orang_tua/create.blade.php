<x-layouts.app>
    <x-slot:title>Tambah Akun Orang Tua / Wali</x-slot:title>

    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <a href="{{ route('admin.orang-tua.index') }}" class="inline-flex items-center gap-1.5 text-xs text-brand-primary hover:text-brand-navy font-semibold mb-2 transition-colors">
                <i class="fa-solid fa-arrow-left text-[11px]"></i> Kembali ke Daftar Orang Tua
            </a>
            <h1 class="text-2xl font-bold text-neutral-text">Tambah Akun Orang Tua / Wali</h1>
            <p class="text-xs text-neutral-muted mt-1">Daftarkan akun portal wali murid dan hubungkan dengan data siswa binaan.</p>
        </div>

        <div class="bg-white border border-neutral-border rounded-2xl p-6 sm:p-8 shadow-xs">
            <form action="{{ route('admin.orang-tua.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                            Nama Lengkap <span class="text-red-accent">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            placeholder="Contoh: Budi Santoso, S.E."
                            class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                        @error('name')
                            <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                            Alamat Email (Login) <span class="text-red-accent">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            placeholder="nama@email.com"
                            class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                        @error('email')
                            <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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

                    <div>
                        <label for="phone_number" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                            Nomor WhatsApp / HP
                        </label>
                        <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                            placeholder="Contoh: 081234567890"
                            class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                        @error('phone_number')
                            <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="relationship_type" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                            Hubungan Keluarga
                        </label>
                        <select name="relationship_type" id="relationship_type"
                            class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                            <option value="Ayah" {{ old('relationship_type') == 'Ayah' ? 'selected' : '' }}>Ayah</option>
                            <option value="Ibu" {{ old('relationship_type') == 'Ibu' ? 'selected' : '' }}>Ibu</option>
                            <option value="Wali" {{ old('relationship_type', 'Wali') == 'Wali' ? 'selected' : '' }}>Wali Murid</option>
                        </select>
                    </div>

                    <div>
                        <label for="occupation" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                            Pekerjaan
                        </label>
                        <input type="text" name="occupation" id="occupation" value="{{ old('occupation') }}"
                            placeholder="Contoh: Wiraswasta / ASN"
                            class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                        Alamat Domisili
                    </label>
                    <textarea name="address" id="address" rows="2"
                        placeholder="Alamat lengkap tempat tinggal orang tua / wali..."
                        class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">{{ old('address') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                        Hubungkan dengan Siswa Binaan
                    </label>
                    <div class="border border-neutral-border rounded-xl p-3 max-h-56 overflow-y-auto space-y-2 bg-surface">
                        @forelse ($students as $student)
                            <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-neutral-subtle cursor-pointer transition-colors">
                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                    {{ in_array($student->id, old('student_ids', [])) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded text-brand-primary focus:ring-brand-primary/50">
                                <div class="text-xs">
                                    <span class="font-medium text-neutral-text">{{ $student->user->name ?? 'Siswa' }}</span>
                                    <span class="text-neutral-muted text-[11px] block">NISN: {{ $student->nisn }} &bull; {{ $student->academicClass->name ?? 'Tanpa Kelas' }}</span>
                                </div>
                            </label>
                        @empty
                            <p class="text-xs text-neutral-muted p-2">Belum ada data siswa aktif.</p>
                        @endforelse
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-neutral-border">
                    <a href="{{ route('admin.orang-tua.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-neutral-muted hover:text-neutral-text hover:bg-neutral-subtle transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-all">
                        Simpan Akun Orang Tua
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
