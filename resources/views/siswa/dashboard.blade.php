<x-layouts.app>
    <x-slot:title>Dashboard Siswa</x-slot:title>

    <div class="space-y-6">
        <!-- Welcome Hero Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-neutral-border/80 p-6 sm:p-8 shadow-sm">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-brand-blue via-accent-gold to-brand-blue"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 mb-3">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                        Status Siswa Aktif • Bebas Paywall
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-neutral-dark tracking-tight">Selamat Datang, {{ $user->name }}!</h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Portal Akademik &amp; Pembelajaran Mandiri — Bimbingan Belajar Alto
                    </p>
                </div>

                @if ($student)
                    <div class="bg-[#FFFAF3] border border-neutral-border rounded-xl p-4 text-xs space-y-1.5 min-w-[260px] shadow-2xs">
                        <div class="text-neutral-muted">NISN: <span class="text-neutral-dark font-mono font-bold">{{ $student->nisn }}</span></div>
                        <div class="text-neutral-muted">Kelas: <span class="text-neutral-dark font-semibold">{{ $student->academicClass?->name }} ({{ $student->major?->name }})</span></div>
                        <div class="text-neutral-muted">Mentor: <span class="text-brand-blue font-bold">{{ $student->mentor?->user?->name }}</span></div>
                        <div class="text-neutral-muted">Guru BK: <span class="text-purple-700 font-bold">{{ $student->counselor?->name }}</span></div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Metric Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-neutral-border/60 p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-neutral-muted uppercase tracking-wider">Presensi QR</span>
                    <span class="p-2.5 rounded-lg bg-emerald-50 text-emerald-600">
                        <i class="fa-solid fa-qrcode text-lg"></i>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-extrabold text-neutral-dark">100%</div>
                    <span class="text-xs text-emerald-700 font-semibold inline-flex items-center gap-1.5 mt-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Kehadiran Normal
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-neutral-border/60 p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-neutral-muted uppercase tracking-wider">ALTER LMS</span>
                    <span class="p-2.5 rounded-lg bg-blue-50 text-brand-blue">
                        <i class="fa-solid fa-laptop-code text-lg"></i>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-extrabold text-neutral-dark">Auto-Enrolled</div>
                    <span class="text-xs text-brand-blue font-semibold block mt-1">Jurusan {{ $student?->major?->name }}</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-neutral-border/60 p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-neutral-muted uppercase tracking-wider">English Mastery</span>
                    <span class="p-2.5 rounded-lg bg-amber-50 text-accent-gold">
                        <i class="fa-solid fa-spell-check text-lg"></i>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-extrabold text-neutral-dark">400 Kata</div>
                    <span class="text-xs text-accent-gold font-semibold block mt-1">CBT Vocab &amp; Oral Rubric</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-neutral-border/60 p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-neutral-muted uppercase tracking-wider">Tryout SNBT</span>
                    <span class="p-2.5 rounded-lg bg-purple-50 text-purple-700">
                        <i class="fa-solid fa-chart-line text-lg"></i>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-extrabold text-neutral-dark">Aktif</div>
                    <span class="text-xs text-purple-700 font-semibold block mt-1">Subtes Terintegrasi</span>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
