<x-layouts.app>
    <x-slot:title>Dashboard Siswa</x-slot:title>

    <div class="space-y-6">
        @if ($student?->needsProfileCompletion())
            <div class="rounded-2xl bg-amber-50 border border-amber-200 p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xs">
                <div class="flex items-start gap-3">
                    <span class="p-2 rounded-xl bg-amber-100 text-amber-700 shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-base"></i>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-amber-900">Lengkapi Profil Siswa (NISN Belum Terisi)</h2>
                        <p class="text-xs text-amber-700 mt-0.5">
                            Nomor Induk Siswa Nasional (NISN) Anda belum terdata. Lengkapi profil Anda untuk sinkronisasi Tryout CBT dan verifikasi SNBT.
                        </p>
                    </div>
                </div>
                <a href="{{ route('siswa.profil') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold shrink-0 transition-colors">
                    <i class="fa-solid fa-user-pen"></i> Lengkapi Profil
                </a>
            </div>
        @endif

        <!-- Welcome Hero Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-neutral-border shadow-xs p-6 sm:p-8">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Status Siswa Aktif
                        </span>
                        @if ($isTkaActive)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-brand-primary border border-brand-primary/20">
                                <i class="fa-solid fa-graduation-cap text-[10px]"></i> Kurikulum TKA + SNBT Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-300">
                                <i class="fa-solid fa-bullseye text-[10px]"></i> Mode Fokus UTBK / SNBT (TKA Dinonaktifkan)
                            </span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-neutral-text">Selamat Datang, {{ $user->name }}</h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Portal Akademik &amp; Pembelajaran Mandiri ALTURA — Kelas {{ $student?->academicClass?->name ?? 'Reguler' }}
                    </p>
                </div>

                @if ($student)
                    <div class="bg-surface border border-neutral-border rounded-xl p-4 text-xs space-y-1.5 min-w-[260px] shadow-2xs">
                        <div class="text-neutral-muted">NISN: <span class="font-mono font-bold {{ $student->nisn ? 'text-neutral-text' : 'text-amber-600 italic' }}">{{ $student->nisn ?: 'Belum Dilengkapi' }}</span></div>
                        <div class="text-neutral-muted">Jurusan: <span class="text-neutral-text font-semibold">{{ $student->major?->name }}</span></div>
                        <div class="text-neutral-muted">Guru Mentor: <span class="text-brand-primary font-bold">{{ $student->mentor?->user?->name ?? 'Belum Ditentukan' }}</span></div>
                        <div class="text-neutral-muted">Guru BK: <span class="text-purple-700 font-bold">{{ $student->counselor?->name ?? 'Kak Nisa' }}</span></div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Metric Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl shadow-xs border border-neutral-border p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-neutral-muted uppercase tracking-wider">Jadwal Kelas</span>
                    <span class="p-2.5 rounded-xl bg-blue-50 text-brand-primary">
                        <i class="fa-solid fa-calendar-days text-base"></i>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-bold text-neutral-text">{{ $schedules->count() }} Sesi</div>
                    <span class="text-xs text-neutral-muted font-medium block mt-1">Jadwal Mingguan</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xs border border-neutral-border p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-neutral-muted uppercase tracking-wider">Konsultasi</span>
                    <span class="p-2.5 rounded-xl bg-purple-50 text-purple-700">
                        <i class="fa-solid fa-comments text-base"></i>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-bold text-neutral-text">Tentoring</div>
                    <span class="text-xs text-neutral-muted font-medium block mt-1">Layanan Konsultasi Mapel</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xs border border-neutral-border p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-neutral-muted uppercase tracking-wider">Mata Pelajaran</span>
                    <span class="p-2.5 rounded-xl bg-emerald-50 text-emerald-700">
                        <i class="fa-solid fa-layer-group text-base"></i>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-bold text-neutral-text">{{ $activeSubjects->count() }} Mapel</div>
                    <span class="text-xs text-neutral-muted font-medium block mt-1">{{ $isTkaActive ? 'SNBT + TKA' : 'SNBT Subtes Saja' }}</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xs border border-neutral-border p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-neutral-muted uppercase tracking-wider">Presensi Siswa</span>
                    <span class="p-2.5 rounded-xl bg-purple-50 text-purple-700">
                        <i class="fa-solid fa-qrcode text-base"></i>
                    </span>
                </div>
                <div class="mt-4">
                    <a href="{{ route('siswa.presensi.qr') }}" class="text-xs font-bold text-brand-primary hover:underline block">
                        Tampilkan QR Presensi &rarr;
                    </a>
                    <span class="text-[11px] text-neutral-muted mt-1 block">Scan saat sesi bimbel</span>
                </div>
            </div>
        </div>

        <!-- 2 Column: Schedules & Homework -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Schedule Section -->
            <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-bold text-neutral-text flex items-center gap-2">
                        <i class="fa-solid fa-calendar-check text-brand-primary"></i> Jadwal Belajar Mingguan
                    </h2>
                    <span class="text-xs text-neutral-muted">{{ $student?->academicClass?->name }}</span>
                </div>

                @if ($schedules->isNotEmpty())
                    <div class="space-y-2.5">
                        @foreach ($schedules as $sched)
                            <div class="flex items-center justify-between p-3 rounded-xl border border-neutral-border bg-surface text-xs">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-neutral-text">{{ $sched->day_of_week }}</span>
                                        <span class="text-[11px] text-brand-primary font-mono font-semibold">
                                            {{ \Illuminate\Support\Str::substr($sched->time_start, 0, 5) }} - {{ \Illuminate\Support\Str::substr($sched->time_end, 0, 5) }}
                                        </span>
                                    </div>
                                    <div class="font-medium text-neutral-text mt-0.5">{{ $sched->subject?->name }}</div>
                                    <div class="text-[11px] text-neutral-muted">
                                        Guru: {{ $sched->teacher?->user?->name ?? 'Pengajar' }} &bull; Ruang: {{ $sched->room }}
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20">
                                    {{ $sched->subject?->code }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-neutral-muted py-6 text-center">Belum ada jadwal belajar yang terdaftar untuk kelas ini.</p>
                @endif
            </div>

            <!-- Subject Consultations Section (Replaces PR) -->
            <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-bold text-neutral-text flex items-center gap-2">
                        <i class="fa-solid fa-comments text-brand-primary"></i> Layanan Konsultasi Mapel
                    </h2>
                    <a href="{{ route('siswa.konsultasi.index') }}" class="text-xs text-brand-primary font-semibold hover:underline">
                        Lihat Semua Slot &rarr;
                    </a>
                </div>

                <div class="p-4 rounded-xl bg-brand-soft border border-brand-primary/10 text-xs text-neutral-text space-y-2">
                    <div class="font-bold flex items-center gap-1.5 text-brand-primary">
                        <i class="fa-solid fa-chalkboard-user"></i> Butuh Bedah Soal atau Tentoring Tambahan?
                    </div>
                    <p class="text-neutral-muted leading-relaxed">
                        Slot konsultasi mata pelajaran dibuka langsung oleh Admin ALTO bersama guru pengampu. Pilih jadwal yang sesuai untuk mendiskusikan materi pelajaran atau persiapan UTBK.
                    </p>
                    <div class="pt-1">
                        <a href="{{ route('siswa.konsultasi.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-brand-primary hover:bg-brand-navy text-white text-xs font-semibold transition-colors">
                            <i class="fa-solid fa-calendar-plus text-[10px]"></i> Booking Konsultasi Mapel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
