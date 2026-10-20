<x-layouts.app>
    <x-slot:title>Peran Guru Mapel: Sesi Kelas &amp; Presensi</x-slot:title>

    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="bg-white border border-neutral-border rounded-2xl p-6 sm:p-8 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20 mb-2">
                        <i class="fa-solid fa-chalkboard-user text-xs"></i>
                        <span>Peran Akademik: Guru Mata Pelajaran</span>
                    </div>
                    <h1 class="text-2xl font-bold text-neutral-text">Sesi Pembelajaran &amp; Monitoring Kehadiran</h1>
                    <p class="text-xs text-neutral-muted mt-1">
                        Pantau absensi tatap muka siswa pada jam mengajar Anda dan kelola materi pelajaran rombel.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('guru.pembimbing.index') }}" class="px-4 py-2.5 rounded-xl border border-neutral-border bg-white hover:bg-neutral-subtle text-neutral-text text-xs font-bold shadow-2xs transition-all flex items-center gap-2">
                        <i class="fa-solid fa-user-group text-brand-primary"></i>
                        <span>Beralih ke Menu Pembimbing</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Bar: Subject & Class Selector -->
        <div class="bg-white border border-neutral-border rounded-2xl p-5 shadow-xs">
            <form method="GET" action="{{ route('guru.mapel.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-xs font-bold text-neutral-muted uppercase tracking-wider mb-2">Pilih Mata Pelajaran:</label>
                    <select name="subject_id" onchange="this.form.submit()" class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-neutral-border text-neutral-text text-xs font-semibold focus:outline-none focus:border-brand-primary">
                        @foreach ($mySubjects as $sub)
                            <option value="{{ $sub->id }}" {{ $selectedSubjectId === $sub->id ? 'selected' : '' }}>
                                {{ $sub->name }} ({{ $sub->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-neutral-muted uppercase tracking-wider mb-2">Pilih Rombel / Kelas:</label>
                    <select name="class_id" onchange="this.form.submit()" class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-neutral-border text-neutral-text text-xs font-semibold focus:outline-none focus:border-brand-primary">
                        @foreach ($myClasses as $cls)
                            <option value="{{ $cls->id }}" {{ $selectedClassId === $cls->id ? 'selected' : '' }}>
                                {{ $cls->name }} (Tingkat {{ $cls->grade_level }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <button type="submit" class="w-full py-2.5 bg-brand-primary hover:bg-brand-navy text-white text-xs font-bold rounded-xl shadow-xs transition-all">
                        <i class="fa-solid fa-filter mr-1.5"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Session Schedule Card -->
        @if ($schedules->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($schedules as $sch)
                    <div class="bg-white border border-neutral-border rounded-xl p-4 shadow-2xs flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-brand-soft border border-brand-primary/20 flex items-center justify-center text-brand-primary shrink-0">
                                <i class="fa-solid fa-calendar-day"></i>
                            </div>
                            <div>
                                <span class="font-bold text-neutral-text text-xs block">{{ ucfirst($sch->day_of_week) }}</span>
                                <span class="text-[11px] text-neutral-muted">{{ substr($sch->time_start, 0, 5) }} - {{ substr($sch->time_end, 0, 5) }} WIB</span>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-neutral-subtle border border-neutral-border text-neutral-text">
                            Ruang {{ $sch->room }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Class Attendance Status Table (Read-Only) -->
        <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-neutral-border gap-2">
                <div>
                    <h2 class="text-base font-bold text-neutral-text flex items-center gap-2">
                        <i class="fa-solid fa-users-viewfinder text-brand-primary"></i>
                        <span>Monitoring Kehadiran Kelas: {{ $activeClass?->name ?? '-' }}</span>
                    </h2>
                    <p class="text-xs text-neutral-muted mt-0.5">
                        Status presensi tercatat otomatis oleh Tim Administrasi Meja Depan saat siswa tiba di ALTO.
                    </p>
                </div>
                <span class="text-xs font-mono font-bold text-brand-primary bg-brand-soft px-3 py-1 rounded-full border border-brand-primary/20 self-start sm:self-auto">
                    {{ $attendancesToday->where('status', 'hadir')->count() }} / {{ $students->count() }} Hadir Hari Ini
                </span>
            </div>

            @if ($students->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-neutral-text">
                        <thead class="bg-neutral-subtle uppercase text-[10px] font-bold text-neutral-muted border-b border-neutral-border">
                            <tr>
                                <th class="px-4 py-3">No</th>
                                <th class="px-4 py-3">Nama Siswa</th>
                                <th class="px-4 py-3">NISN</th>
                                <th class="px-4 py-3">Waktu Masuk</th>
                                <th class="px-4 py-3 text-center">Status Presensi</th>
                                <th class="px-4 py-3 text-right">Profil Akademik</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-border">
                            @foreach ($students as $index => $std)
                                @php
                                    $att = $attendancesToday->get($std->id);
                                @endphp
                                <tr class="hover:bg-neutral-subtle/40 transition-colors">
                                    <td class="px-4 py-3 font-mono text-neutral-muted">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-bold text-neutral-text">
                                        {{ $std->user?->name }}
                                    </td>
                                    <td class="px-4 py-3 font-mono text-neutral-muted">
                                        {{ $std->nisn ?? 'Belum ada NISN' }}
                                    </td>
                                    <td class="px-4 py-3 font-mono text-neutral-muted">
                                        {{ $att?->time_in ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if ($att)
                                            <span class="inline-block px-2.5 py-0.5 rounded-full font-bold text-[10px] {{ $att->status === 'hadir' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($att->status === 'terlambat' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-red-50 text-red-700 border border-red-200') }}">
                                                {{ ucfirst($att->status) }}
                                            </span>
                                        @else
                                            <span class="inline-block px-2.5 py-0.5 rounded-full font-bold text-[10px] bg-slate-100 text-slate-500 border border-slate-200">
                                                Belum Masuk
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('guru.bimbingan.student.profile', $std->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-neutral-subtle hover:bg-neutral-200 text-neutral-text font-bold text-xs transition-colors">
                                            <i class="fa-solid fa-address-card text-brand-primary"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-10 text-neutral-muted">
                    <i class="fa-solid fa-users-slash text-2xl mb-2 text-neutral-muted/40 block"></i>
                    <p class="font-semibold text-neutral-text text-sm">Tidak ada siswa terdaftar di rombel ini.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
