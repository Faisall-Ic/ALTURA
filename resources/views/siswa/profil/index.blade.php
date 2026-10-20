<x-layouts.app>
    <x-slot:title>Profil Akademik Terpadu Siswa</x-slot:title>

    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-neutral-border/80 p-6 sm:p-8 shadow-sm">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-brand-blue via-accent-gold to-brand-blue"></div>
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-soft text-brand-primary border border-blue-200 mb-3">
                        <span class="w-2 h-2 rounded-full bg-brand-primary animate-pulse"></span>
                        <i class="fa-solid fa-id-card text-[10px]"></i>
                        Kartu Identitas Akademik Terintegrasi
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-neutral-text tracking-tight">Profil Akademik Terpadu</h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Informasi kesiswaan, mentor tertaut, pemetaan kelas, dan unduh Rapor 360° resmi.
                    </p>
                </div>

                <div>
                    <a href="{{ route('reports.progress.pdf', $student->id) }}" target="_blank" class="px-5 py-2.5 rounded-xl bg-brand-primary hover:bg-brand-navy text-white text-xs font-bold shadow-xs transition-all flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-print"></i>
                        <span>Unduh Rapor 360° Resmi (PDF)</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Student Profile Card Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Info (2 Cols) -->
            <div class="lg:col-span-2 bg-white border border-neutral-border/60 rounded-2xl p-6 shadow-sm space-y-6">
                <div>
                    <h2 class="text-base font-bold text-neutral-text mb-4">Informasi Biodata Siswa</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-4 rounded-xl bg-neutral-subtle/50 border border-neutral-border">
                            <span class="text-neutral-muted block mb-1 text-[11px] font-semibold uppercase tracking-wider">Nama Lengkap</span>
                            <span class="text-base font-extrabold text-neutral-text">{{ $user->name }}</span>
                        </div>
                        <div class="p-4 rounded-xl bg-neutral-subtle/50 border border-neutral-border">
                            <span class="text-neutral-muted block mb-1 text-[11px] font-semibold uppercase tracking-wider">Nomor Induk Siswa Nasional (NISN)</span>
                            @if ($student->nisn)
                                <span class="text-base font-mono font-extrabold text-brand-primary">{{ $student->nisn }}</span>
                            @else
                                <form method="POST" action="{{ route('siswa.profil.update-nisn') }}" class="flex items-center gap-2 mt-1">
                                    @csrf
                                    <input type="text" name="nisn" required placeholder="Masukkan 10 Digit NISN"
                                        class="px-3 py-1.5 rounded-lg border border-amber-300 text-xs font-mono font-bold text-neutral-text focus:outline-none focus:border-brand-primary">
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-brand-primary text-white text-xs font-bold shadow-2xs hover:bg-brand-navy">
                                        Simpan
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div class="p-4 rounded-xl bg-neutral-subtle/50 border border-neutral-border">
                            <span class="text-neutral-muted block mb-1 text-[11px] font-semibold uppercase tracking-wider">Kelas &amp; Rombongan Belajar</span>
                            <span class="text-sm font-bold text-neutral-text">{{ $student->academicClass?->name }}</span>
                        </div>
                        <div class="p-4 rounded-xl bg-neutral-subtle/50 border border-neutral-border">
                            <span class="text-neutral-muted block mb-1 text-[11px] font-semibold uppercase tracking-wider">Program / Jurusan</span>
                            <span class="text-sm font-bold text-neutral-text">{{ $student->major?->name }} ({{ $student->major?->code }})</span>
                        </div>
                        <div class="p-4 rounded-xl bg-neutral-subtle/50 border border-neutral-border">
                            <span class="text-neutral-muted block mb-1 text-[11px] font-semibold uppercase tracking-wider">Email Terdaftar</span>
                            <span class="text-sm font-mono text-neutral-text">{{ $user->email }}</span>
                        </div>
                        <div class="p-4 rounded-xl bg-neutral-subtle/50 border border-neutral-border">
                            <span class="text-neutral-muted block mb-1 text-[11px] font-semibold uppercase tracking-wider">Nomor WhatsApp Orang Tua</span>
                            <span class="text-sm font-mono font-bold text-emerald-700">{{ $student->parent_phone_number }}</span>
                        </div>
                    </div>
                </div>

                <!-- Academic Guidance Links -->
                <div class="pt-4 border-t border-neutral-border">
                    <h3 class="text-sm font-bold text-neutral-text mb-3">Guru Mentor &amp; Konselor BK Terkunci</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl bg-brand-soft/50 border border-blue-200/80 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-brand-soft text-brand-primary flex items-center justify-center font-bold text-base shrink-0">
                                <i class="fa-solid fa-chalkboard-user"></i>
                            </div>
                            <div>
                                <span class="text-[10px] text-neutral-muted uppercase font-bold tracking-wider block">Guru Mentor Pembimbing</span>
                                <span class="text-sm font-bold text-brand-dark">{{ $student->mentor?->user?->name ?? 'Belum Ditugaskan' }}</span>
                                <span class="text-xs text-neutral-muted block">{{ $student->mentor?->specialization_subject }}</span>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-purple-50/50 border border-purple-200/80 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center font-bold text-base shrink-0">
                                <i class="fa-solid fa-brain"></i>
                            </div>
                            <div>
                                <span class="text-[10px] text-neutral-muted uppercase font-bold tracking-wider block">Guru Bimbingan Konseling</span>
                                <span class="text-sm font-bold text-brand-dark">{{ $student->counselor?->name ?? 'Kak Nisa (BK)' }}</span>
                                <span class="text-xs text-neutral-muted block">Spesialis Konseling &amp; EWS</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right QR Identity Box -->
            <div class="bg-white border border-neutral-border/60 rounded-2xl p-6 shadow-sm flex flex-col justify-between space-y-4">
                <div>
                    <h3 class="text-sm font-bold text-neutral-text mb-1">Token QR Identitas Presensi</h3>
                    <p class="text-xs text-neutral-muted">Gunakan token unik ini saat verifikasi kehadiran meja belajar oleh instruktur.</p>

                    <div class="my-6 p-6 rounded-2xl bg-neutral-subtle/50 border border-neutral-border text-center space-y-2">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-brand-primary text-white flex items-center justify-center text-2xl shadow-sm">
                            <i class="fa-solid fa-qrcode"></i>
                        </div>
                        <div class="font-mono text-sm font-bold text-neutral-text pt-2 tracking-wider">
                            {{ $student->qr_identity_token }}
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Identitas Valid
                        </span>
                    </div>
                </div>

                <div class="pt-4 border-t border-neutral-border space-y-2">
                    <a href="{{ route('siswa.presensi.qr') }}" class="w-full inline-flex justify-center items-center gap-2 py-2.5 rounded-xl bg-brand-primary hover:bg-brand-navy text-white text-xs font-bold shadow-xs transition-colors">
                        <i class="fa-solid fa-qrcode"></i>
                        <span>Buka Tampilan Layar Penuh QR</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
