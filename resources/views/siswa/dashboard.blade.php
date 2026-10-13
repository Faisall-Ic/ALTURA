<x-layouts.app>
    <x-slot:title>Dashboard Siswa</x-slot:title>

    <div class="space-y-6">
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
                        <div class="text-neutral-muted">NISN: <span class="text-neutral-text font-mono font-bold">{{ $student->nisn }}</span></div>
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
                    <span class="text-xs font-bold text-neutral-muted uppercase tracking-wider">Tugas Rumah</span>
                    <span class="p-2.5 rounded-xl bg-amber-50 text-amber-700">
                        <i class="fa-solid fa-book-open text-base"></i>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-bold text-neutral-text">{{ $assignments->count() }} Tugas</div>
                    <span class="text-xs text-neutral-muted font-medium block mt-1">Perlu Dikerjakan</span>
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

            <!-- Homework Assignments Section -->
            <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-bold text-neutral-text flex items-center gap-2">
                        <i class="fa-solid fa-book-open text-amber-600"></i> Tugas Rumah Aktif
                    </h2>
                    <span class="text-xs text-neutral-muted">Tenggat Waktu</span>
                </div>

                @if ($assignments->isNotEmpty())
                    <div class="space-y-2.5">
                        @foreach ($assignments as $task)
                            <div class="p-3 rounded-xl border border-neutral-border bg-surface text-xs space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-neutral-text">{{ $task->title }}</span>
                                    <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-neutral-subtle text-neutral-text border border-neutral-border">
                                        Due: {{ $task->due_date?->format('d M, H:i') }}
                                    </span>
                                </div>
                                <div class="text-[11px] text-neutral-muted line-clamp-1">
                                    {{ $task->instructions }}
                                </div>
                                <div class="text-[10px] text-brand-primary font-semibold pt-1">
                                    {{ $task->subject?->name }} &bull; Pengampu: {{ $task->teacher?->user?->name ?? 'Guru' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-neutral-muted py-6 text-center">Tidak ada tugas rumah aktif saat ini.</p>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
