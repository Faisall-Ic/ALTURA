<x-layouts.app>
    <x-slot:title>Dashboard Admin</x-slot:title>

    <div class="space-y-6">
        <!-- Hero Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-neutral-border/80 p-6 sm:p-8 shadow-sm">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-brand-blue via-accent-gold to-brand-blue"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200 mb-3">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                        <i class="fa-solid fa-shield-halved text-[10px]"></i>
                        Pusat Kendali Sistem Akademik
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-neutral-dark tracking-tight">Selamat Datang, {{ $user->name }}!</h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Manajemen Master Data, Hak Akses Spatie, Auto-Enrollment, dan Operasional Bimbel Alto
                    </p>
                </div>

                <div class="bg-[#FFFAF3] border border-neutral-border rounded-xl p-4 text-xs space-y-1.5 min-w-[220px] shadow-2xs">
                    <div class="text-neutral-muted">Status Sistem: <span class="text-emerald-700 font-bold">Aktif &amp; Optimal</span></div>
                    <div class="text-neutral-muted">Database: <span class="text-neutral-dark font-mono font-semibold">SQLite (Local)</span></div>
                    <div class="text-neutral-muted">Sesi Login: <span class="text-brand-blue font-bold">120 Menit</span></div>
                </div>
            </div>
        </div>

        <!-- Master Data Metrics -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-neutral-border/60 p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-neutral-muted uppercase tracking-wider">Total Siswa</span>
                    <span class="p-2 rounded-lg bg-blue-50 text-brand-blue">
                        <i class="fa-solid fa-users text-sm"></i>
                    </span>
                </div>
                <div class="text-3xl font-extrabold text-neutral-dark mt-3">{{ $stats['total_students'] }}</div>
                <p class="text-xs text-neutral-muted mt-1">Terdaftar di rombel</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-neutral-border/60 p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-neutral-muted uppercase tracking-wider">Total Guru</span>
                    <span class="p-2 rounded-lg bg-sky-50 text-sky-600">
                        <i class="fa-solid fa-chalkboard-user text-sm"></i>
                    </span>
                </div>
                <div class="text-3xl font-extrabold text-neutral-dark mt-3">{{ $stats['total_teachers'] }}</div>
                <p class="text-xs text-neutral-muted mt-1">Instruktur aktif</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-neutral-border/60 p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-neutral-muted uppercase tracking-wider">Kelas / Rombel</span>
                    <span class="p-2 rounded-lg bg-amber-50 text-accent-gold">
                        <i class="fa-solid fa-school text-sm"></i>
                    </span>
                </div>
                <div class="text-3xl font-extrabold text-neutral-dark mt-3">{{ $stats['total_classes'] }}</div>
                <p class="text-xs text-neutral-muted mt-1">Kelas terdaftar</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-neutral-border/60 p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-neutral-muted uppercase tracking-wider">Jurusan</span>
                    <span class="p-2 rounded-lg bg-purple-50 text-purple-700">
                        <i class="fa-solid fa-graduation-cap text-sm"></i>
                    </span>
                </div>
                <div class="text-3xl font-extrabold text-neutral-dark mt-3">{{ $stats['total_majors'] }}</div>
                <p class="text-xs text-neutral-muted mt-1">SNBT, MIPA, IPS, SMK</p>
            </div>
        </div>

        <!-- System Architecture Status Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-border/60 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-neutral-dark flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    Status Arsitektur Sistem ALTURA
                </h2>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Semua 6 Fase Terverifikasi 100%
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
                <div class="bg-[#FFFAF3] border border-neutral-border rounded-xl p-4">
                    <div class="flex items-center justify-between text-brand-blue font-bold mb-1">
                        <span>Fase 1 - 2: Core &amp; Master</span>
                        <i class="fa-solid fa-check text-emerald-600"></i>
                    </div>
                    <p class="text-neutral-muted">Database migrations, 5 roles Spatie, Session 120m, CRUD Master Data &amp; Access Middleware.</p>
                </div>

                <div class="bg-[#FFFAF3] border border-neutral-border rounded-xl p-4">
                    <div class="flex items-center justify-between text-brand-blue font-bold mb-1">
                        <span>Fase 3 - 4: Attendance &amp; LMS</span>
                        <i class="fa-solid fa-check text-emerald-600"></i>
                    </div>
                    <p class="text-neutral-muted">Presensi QR Meja, Profil 360°, ALTER LMS One-Page Player, English Vocab CBT &amp; Oral Rubric.</p>
                </div>

                <div class="bg-[#FFFAF3] border border-neutral-border rounded-xl p-4">
                    <div class="flex items-center justify-between text-brand-blue font-bold mb-1">
                        <span>Fase 5 - 6: BK, Ortu &amp; PDF</span>
                        <i class="fa-solid fa-check text-emerald-600"></i>
                    </div>
                    <p class="text-neutral-muted">Matriks EWS Kak Nisa, Portal Orang Tua, WhatsApp Gateway, dan Sertifikat Tryout Resmi A4 PDF.</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
