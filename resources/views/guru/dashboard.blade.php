<x-layouts.app>
    <x-slot:title>Dashboard Guru Pengajar &amp; Pembimbing (Mentor)</x-slot:title>

    <div class="space-y-6">
        <!-- Hero Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-neutral-border shadow-xs p-6 sm:p-8">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20 mb-3">
                        <span class="w-2 h-2 rounded-full bg-brand-primary animate-pulse"></span>
                        Portal Pengajar &amp; Guru Pembimbing
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-neutral-text">Selamat Datang, {{ $user->name }}</h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        NIP: <span class="font-mono text-neutral-text font-medium">{{ $teacher?->nip ?? '-' }}</span> &bull; 
                        Kontak: <span class="font-mono text-neutral-text">{{ $teacher?->phone_number ?? '-' }}</span>
                    </p>
                </div>

                <div class="bg-surface border border-neutral-border rounded-xl p-4 text-xs space-y-1.5 min-w-[240px] shadow-2xs">
                    <div class="text-neutral-muted">Mata Pelajaran: 
                        <span class="text-neutral-text font-bold">
                            {{ $teacher?->subjects->pluck('code')->implode(', ') ?: ($teacher?->specialization_subject ?: 'Umum') }}
                        </span>
                    </div>
                    <div class="text-neutral-muted">Siswa Binaan (Mentee): 
                        <span class="text-brand-primary font-bold">{{ $teacher?->mentees?->count() ?? 0 }} Siswa Terkunci</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mode View Switcher (Subject Teacher vs Mentor) -->
        <div class="flex border-b border-neutral-border gap-2">
            <a href="{{ route('guru.dashboard', ['view' => 'guru_mapel']) }}"
                class="px-5 py-3 text-xs font-bold border-b-2 transition-all flex items-center gap-2 {{ $viewMode === 'guru_mapel' ? 'border-brand-primary text-brand-primary bg-brand-soft/40 rounded-t-xl' : 'border-transparent text-neutral-muted hover:text-neutral-text' }}">
                <i class="fa-solid fa-chalkboard-user text-xs"></i>
                <span>Tampilan Guru Mata Pelajaran (Subject Teacher)</span>
            </a>
            <a href="{{ route('guru.dashboard', ['view' => 'mentor']) }}"
                class="px-5 py-3 text-xs font-bold border-b-2 transition-all flex items-center gap-2 {{ $viewMode === 'mentor' ? 'border-brand-primary text-brand-primary bg-brand-soft/40 rounded-t-xl' : 'border-transparent text-neutral-muted hover:text-neutral-text' }}">
                <i class="fa-solid fa-user-graduate text-xs"></i>
                <span>Tampilan Guru Pembimbing (Holistic 360° Mentor)</span>
            </a>
        </div>

        @if ($viewMode === 'guru_mapel')
            <!-- VIEW 1: SUBJECT TEACHER VIEW -->
            <div class="space-y-6">
                <!-- Metrics Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white rounded-2xl shadow-xs border border-neutral-border p-5">
                        <div class="text-xs font-bold text-neutral-muted uppercase">Mapel &amp; Rombel Diampu</div>
                        <div class="mt-3 text-xl font-bold text-neutral-text">
                            {{ $teacher?->subjects->count() ?? 0 }} Mapel &bull; {{ $teacher?->academicClasses->count() ?? 0 }} Kelas
                        </div>
                        <div class="flex flex-wrap gap-1 mt-2">
                            @foreach ($teacher?->subjects ?? [] as $sub)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-brand-soft text-brand-primary border border-brand-primary/20">
                                    {{ $sub->code }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-xs border border-neutral-border p-5">
                        <div class="text-xs font-bold text-neutral-muted uppercase">Jadwal Mengajar</div>
                        <div class="mt-3 text-xl font-bold text-neutral-text">
                            {{ $teacher?->lessonSchedules->count() ?? 0 }} Sesi / Pekan
                        </div>
                        <span class="text-[11px] text-neutral-muted mt-1 block">Jadwal tatap muka reguler</span>
                    </div>

                    <div class="bg-white rounded-2xl shadow-xs border border-neutral-border p-5">
                        <div class="text-xs font-bold text-neutral-muted uppercase">Tugas Mandiri (PR)</div>
                        <div class="mt-3 text-xl font-bold text-neutral-text">
                            {{ $teacher?->homeworkAssignments->count() ?? 0 }} Penugasan
                        </div>
                        <span class="text-[11px] text-neutral-muted mt-1 block">Tugas aktif di kelas yang diampu</span>
                    </div>
                </div>

                <!-- 2 Column: Schedules & Homework Assignments -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Lesson Schedules -->
                    <div class="bg-white rounded-2xl border border-neutral-border p-6 shadow-xs space-y-4">
                        <h2 class="text-base font-bold text-neutral-text flex items-center gap-2">
                            <i class="fa-solid fa-calendar-days text-brand-primary"></i> Sesi Mengajar Mingguan
                        </h2>

                        @if ($teacher && $teacher->lessonSchedules->isNotEmpty())
                            <div class="space-y-2.5">
                                @foreach ($teacher->lessonSchedules as $sched)
                                    <div class="flex items-center justify-between p-3 rounded-xl border border-neutral-border bg-surface text-xs">
                                        <div>
                                            <div class="font-bold text-neutral-text">{{ $sched->day_of_week }}, {{ \Illuminate\Support\Str::substr($sched->time_start, 0, 5) }} - {{ \Illuminate\Support\Str::substr($sched->time_end, 0, 5) }}</div>
                                            <div class="text-neutral-muted mt-0.5">{{ $sched->subject?->name }} &bull; Kelas: {{ $sched->academicClass?->name }}</div>
                                        </div>
                                        <div class="text-right">
                                            <span class="font-mono text-neutral-text font-semibold"><i class="fa-solid fa-location-dot text-[10px] mr-1 text-neutral-muted"></i>{{ $sched->room }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-neutral-muted py-6 text-center">Belum ada jadwal mengajar yang dialokasikan.</p>
                        @endif
                    </div>

                    <!-- Homework Assignments created -->
                    <div class="bg-white rounded-2xl border border-neutral-border p-6 shadow-xs space-y-4">
                        <h2 class="text-base font-bold text-neutral-text flex items-center gap-2">
                            <i class="fa-solid fa-book-open text-amber-600"></i> Tugas Rumah yang Diberikan
                        </h2>

                        @if ($teacher && $teacher->homeworkAssignments->isNotEmpty())
                            <div class="space-y-2.5">
                                @foreach ($teacher->homeworkAssignments as $hw)
                                    <div class="p-3 rounded-xl border border-neutral-border bg-surface text-xs space-y-1">
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-neutral-text">{{ $hw->title }}</span>
                                            <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-neutral-subtle text-neutral-text">
                                                Due: {{ $hw->due_date?->format('d M, H:i') }}
                                            </span>
                                        </div>
                                        <div class="text-[11px] text-neutral-muted">
                                            {{ $hw->subject?->name }} &bull; {{ $hw->academicClass?->name }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-neutral-muted py-6 text-center">Belum ada tugas rumah yang dibuat.</p>
                        @endif
                    </div>
                </div>
            </div>

        @elseif ($viewMode === 'mentor')
            <!-- VIEW 2: GURU PEMBIMBING (MENTOR) 360 VIEW -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-xs border border-neutral-border p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                        <div>
                            <h2 class="text-lg font-bold text-neutral-text flex items-center gap-2">
                                <i class="fa-solid fa-users-viewfinder text-brand-primary"></i>
                                Rekapitulasi 360° Siswa Binaan (Locked Mentees)
                            </h2>
                            <p class="text-xs text-neutral-muted mt-0.5">
                                Pantau perkembangan terpadu Vocab CBT, Oral Assessment (Storytelling/Wacana), dan total presensi siswa binaan.
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-brand-soft text-brand-primary border border-brand-primary/20">
                            {{ $teacher?->mentees->count() ?? 0 }} Siswa Bimbingan
                        </span>
                    </div>

                    @if ($teacher && $teacher->mentees->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs text-neutral-text">
                                <thead class="text-xs uppercase bg-neutral-subtle/70 text-neutral-muted border-b border-neutral-border font-semibold">
                                    <tr>
                                        <th class="px-4 py-3">Nama Siswa &amp; NISN</th>
                                        <th class="px-4 py-3">Kelas &amp; Jurusan</th>
                                        <th class="px-4 py-3">Vocab Quiz Progress</th>
                                        <th class="px-4 py-3">Speaking Assessment</th>
                                        <th class="px-4 py-3">Kehadiran Total</th>
                                        <th class="px-4 py-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-border">
                                    @foreach ($teacher->mentees as $mentee)
                                        @php
                                            $totalAttendances = $mentee->attendances->count();
                                            $hadirCount = $mentee->attendances->where('status', 'hadir')->count();
                                            $latestVocabScore = $mentee->vocabQuizResults->sortByDesc('id')->first();
                                            $speakingCount = $mentee->speakingBookings->count();
                                        @endphp
                                        <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                            <td class="px-4 py-3.5">
                                                <div class="font-bold text-neutral-text">{{ $mentee->user?->name }}</div>
                                                <div class="text-[11px] font-mono text-neutral-muted mt-0.5">NISN: {{ $mentee->nisn }}</div>
                                            </td>
                                            <td class="px-4 py-3.5">
                                                <div class="font-medium text-neutral-text">{{ $mentee->academicClass?->name }}</div>
                                                <span class="text-[10px] text-neutral-muted">{{ $mentee->major?->name }}</span>
                                            </td>
                                            <td class="px-4 py-3.5">
                                                @if ($latestVocabScore)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold {{ $latestVocabScore->passed ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                                        Skor: {{ $latestVocabScore->score }} &bull; {{ $latestVocabScore->passed ? 'Lulus' : 'Remedial' }}
                                                    </span>
                                                @else
                                                    <span class="text-[11px] text-neutral-muted italic">Belum kuis</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3.5">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-neutral-subtle text-neutral-text border border-neutral-border">
                                                    {{ $speakingCount }} Sesi Setoran
                                                </span>
                                            </td>
                                            <td class="px-4 py-3.5">
                                                <span class="font-bold text-neutral-text">{{ $hadirCount }} / {{ $totalAttendances }} Sesi</span>
                                                <span class="text-[10px] text-neutral-muted block">Hadir</span>
                                            </td>
                                            <td class="px-4 py-3.5 text-right">
                                                <a href="{{ route('guru.profil.siswa', $mentee->id) }}"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-brand-primary text-white hover:bg-brand-navy shadow-xs transition-colors">
                                                    <span>Profil 360°</span>
                                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-xs text-neutral-muted py-8 text-center">Belum ada siswa binaan yang dialokasikan ke akun mentor ini.</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
