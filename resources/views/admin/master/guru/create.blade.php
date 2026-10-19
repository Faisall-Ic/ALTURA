<x-layouts.app>
    <x-slot:title>Tambah Guru / Mentor</x-slot:title>

    <div class="max-w-3xl mx-auto space-y-6">
        <div>
            <a href="{{ route('admin.guru.index') }}" class="inline-flex items-center gap-1.5 text-xs text-brand-primary hover:text-brand-navy font-semibold mb-2 transition-colors">
                <i class="fa-solid fa-arrow-left text-[11px]"></i> Kembali ke Daftar Guru
            </a>
            <h1 class="text-2xl font-bold text-neutral-text">Tambah Guru & Mentor Baru</h1>
            <p class="text-xs text-neutral-muted mt-1">Daftarkan akun instruktur dan petakan mata pelajaran serta kelas yang diajar.</p>
        </div>

        <div class="bg-white border border-neutral-border rounded-2xl p-6 sm:p-8 shadow-xs">
            <form action="{{ route('admin.guru.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                        Nama Lengkap & Gelar <span class="text-red-accent">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        placeholder="Contoh: Budi Santoso, M.Pd"
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

                <div>
                    <label for="specialization_subject" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                        Label Spesialisasi / Deskripsi Peran
                    </label>
                    <input type="text" name="specialization_subject" id="specialization_subject" value="{{ old('specialization_subject') }}"
                        placeholder="Contoh: Guru Matematika & Mentor SNBT (Boleh dikosongkan jika memilih mata pelajaran di bawah)"
                        class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                </div>

                <!-- Multi-Subject Mapping -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-semibold uppercase text-neutral-muted">
                            Mata Pelajaran yang Diajar (Multi-Subject)
                        </label>
                        <span class="text-[11px] text-neutral-muted">Pilih satu atau lebih</span>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 p-3 rounded-xl border border-neutral-border bg-surface max-h-48 overflow-y-auto">
                        @foreach ($subjects as $s)
                            <label class="flex items-center gap-2 p-2 rounded-lg hover:bg-neutral-subtle cursor-pointer transition-colors">
                                <input type="checkbox" name="subject_ids[]" value="{{ $s->id }}"
                                    {{ in_array($s->id, old('subject_ids', [])) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded text-brand-primary focus:ring-brand-primary/50">
                                <div class="text-xs truncate">
                                    <span class="font-medium text-neutral-text">{{ $s->name }}</span>
                                    <span class="text-[10px] text-neutral-muted block">({{ $s->code }}) &bull; {{ $s->is_tka ? 'TKA' : 'SNBT' }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Multi-Class Mapping -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-semibold uppercase text-neutral-muted">
                            Penugasan Kelas (Multi-Class)
                        </label>
                        <span class="text-[11px] text-neutral-muted">Pilih rombel yang diampu</span>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 p-3 rounded-xl border border-neutral-border bg-surface max-h-48 overflow-y-auto">
                        @foreach ($classes as $c)
                            <label class="flex items-center gap-2 p-2 rounded-lg hover:bg-neutral-subtle cursor-pointer transition-colors">
                                <input type="checkbox" name="class_ids[]" value="{{ $c->id }}"
                                    {{ in_array($c->id, old('class_ids', [])) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded text-brand-primary focus:ring-brand-primary/50">
                                <div class="text-xs truncate">
                                    <span class="font-medium text-neutral-text">{{ $c->name }}</span>
                                    <span class="text-[10px] text-neutral-muted block">{{ $c->major?->code }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-neutral-border">
                    <a href="{{ route('admin.guru.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-neutral-muted hover:text-neutral-text hover:bg-neutral-subtle transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-all">
                        Simpan Guru & Pemetaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
