<x-layouts.app>
    <x-slot:title>Rekapitulasi Presensi & Health Classification Siswa</x-slot:title>

    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="rounded-2xl bg-white border border-neutral-border p-6 sm:p-8 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20 mb-3">
                        <span class="w-2 h-2 rounded-full bg-brand-primary"></span>
                        Analisis Kehadiran Terpadu
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-neutral-text flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-list text-brand-primary"></i>
                        <span>Rekapitulasi Presensi Akademik</span>
                    </h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Klasifikasi Otomatis Kesehatan Presensi Berdasarkan Standar Bimbel Alto
                    </p>
                </div>
            </div>
        </div>

        <!-- Filter & KPI Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div
                class="bg-white border border-neutral-border rounded-2xl p-5 border-l-4 border-l-emerald-500 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-neutral-muted uppercase"><span
                            class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1.5 align-middle"></span> Normal
                        (≥ 90%)</span>
                    <div class="text-2xl font-bold text-neutral-text mt-1">{{ $totalNormal }} Siswa</div>
                </div>
                <a href="{{ route('bk.rekap.presensi', ['color' => 'green']) }}"
                    class="text-xs text-brand-primary font-semibold hover:underline">Filter &rarr;</a>
            </div>

            <div
                class="bg-white border border-neutral-border rounded-2xl p-5 border-l-4 border-l-amber-accent shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-neutral-muted uppercase"><span
                            class="inline-block w-2 h-2 rounded-full bg-amber-accent mr-1.5 align-middle"></span>
                        Warning (75% - 89%)</span>
                    <div class="text-2xl font-bold text-neutral-text mt-1">{{ $totalWarning }} Siswa</div>
                </div>
                <a href="{{ route('bk.rekap.presensi', ['color' => 'yellow']) }}"
                    class="text-xs text-brand-primary font-semibold hover:underline">Filter &rarr;</a>
            </div>

            <div
                class="bg-white border border-neutral-border rounded-2xl p-5 border-l-4 border-l-red-accent shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-neutral-muted uppercase"><span
                            class="inline-block w-2 h-2 rounded-full bg-red-accent mr-1.5 align-middle"></span> Kritis
                        (&lt; 75% / Alfa)</span>
                    <div class="text-2xl font-bold text-neutral-text mt-1">{{ $totalCritical }} Siswa</div>
                </div>
                <a href="{{ route('bk.rekap.presensi', ['color' => 'red']) }}"
                    class="text-xs text-brand-primary font-semibold hover:underline">Filter &rarr;</a>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white border border-neutral-border rounded-2xl p-4 shadow-sm">
            <form method="GET" action="{{ route('bk.rekap.presensi') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div>
                    <label class="block text-[11px] font-semibold text-neutral-muted mb-1">Jurusan</label>
                    <select name="major_id"
                        class="w-full bg-white border border-neutral-border rounded-lg px-3 py-2 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                        <option value="">Semua Jurusan</option>
                        @foreach ($majors as $m)
                            <option value="{{ $m->id }}" {{ $majorId == $m->id ? 'selected' : '' }}>{{ $m->name }}
                                ({{ $m->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-neutral-muted mb-1">Kelas</label>
                    <select name="class_id"
                        class="w-full bg-white border border-neutral-border rounded-lg px-3 py-2 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                        <option value="">Semua Kelas</option>
                        @foreach ($classes as $c)
                            <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-neutral-muted mb-1">Status Kesehatan</label>
                    <select name="color"
                        class="w-full bg-white border border-neutral-border rounded-lg px-3 py-2 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                        <option value="">Semua Status</option>
                        <option value="green" {{ $healthColor === 'green' ? 'selected' : '' }}>Normal (≥ 90%)</option>
                        <option value="yellow" {{ $healthColor === 'yellow' ? 'selected' : '' }}>Warning (75% - 89%)
                        </option>
                        <option value="red" {{ $healthColor === 'red' ? 'selected' : '' }}>Kritis (&lt; 75%)</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 py-2 rounded-lg bg-brand-primary hover:bg-brand-navy text-white text-xs font-semibold shadow-sm transition-all cursor-pointer">
                        Terapkan Filter
                    </button>
                    <a href="{{ route('bk.rekap.presensi') }}"
                        class="px-3 py-2 rounded-lg bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold border border-neutral-border">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Attendance Table -->
        <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-neutral-text">
                    <thead class="uppercase bg-neutral-subtle/80 text-neutral-muted border-b border-neutral-border">
                        <tr>
                            <th class="px-4 py-3">Nama Siswa</th>
                            <th class="px-4 py-3">Kelas & Jurusan</th>
                            <th class="px-4 py-3 text-center">Hadir</th>
                            <th class="px-4 py-3 text-center">Terlambat</th>
                            <th class="px-4 py-3 text-center">Sakit</th>
                            <th class="px-4 py-3 text-center">Izin</th>
                            <th class="px-4 py-3 text-center">Alfa</th>
                            <th class="px-4 py-3">Tingkat Kehadiran</th>
                            <th class="px-4 py-3">Status Matriks</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-border">
                        @forelse ($rows as $row)
                            @php
                                $s = $row['student'];
                                $st = $row['stats'];
                            @endphp
                            <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                <td class="px-4 py-3.5">
                                    <div class="font-semibold text-neutral-text">{{ $s->user->name }}</div>
                                    <div class="font-mono text-xs text-neutral-muted">NISN: {{ $s->nisn }}</div>
                                </td>
                                <td class="px-4 py-3.5 text-xs text-neutral-text">
                                    {{ $s->academicClass?->name }}
                                    <span class="block text-[11px] text-neutral-muted">{{ $s->major?->name }}</span>
                                </td>
                                <td class="px-4 py-3.5 text-center font-semibold text-emerald-600">{{ $st['hadir'] }}</td>
                                <td class="px-4 py-3.5 text-center font-semibold text-amber-accent">{{ $st['terlambat'] }}
                                </td>
                                <td class="px-4 py-3.5 text-center text-brand-primary">{{ $st['sakit'] }}</td>
                                <td class="px-4 py-3.5 text-center text-brand-navy">{{ $st['izin'] }}</td>
                                <td class="px-4 py-3.5 text-center font-bold text-red-accent">{{ $st['alfa'] }}</td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-16 bg-neutral-subtle rounded-full h-2">
                                            <div class="h-2 rounded-full {{ $st['color'] === 'green' ? 'bg-emerald-500' : ($st['color'] === 'yellow' ? 'bg-amber-accent' : 'bg-red-accent') }}"
                                                style="width: {{ $st['percentage'] }}%"></div>
                                        </div>
                                        <span class="font-bold text-xs text-neutral-text">{{ $st['percentage'] }}%</span>
                                    </div>
                                    <span class="text-[10px] text-neutral-muted">{{ $st['total'] }} Total Sesi</span>
                                </td>
                                <td class="px-4 py-3.5">
                                    @if ($st['color'] === 'green')
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span
                                                class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1.5 align-middle"></span>
                                            Normal
                                        </span>
                                    @elseif ($st['color'] === 'yellow')
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-accent-soft text-amber-accent-text border border-amber-accent/30">
                                            <span
                                                class="inline-block w-2 h-2 rounded-full bg-amber-accent mr-1.5 align-middle"></span>
                                            Warning
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-accent-soft text-red-accent-text border border-red-accent/30">
                                            <span
                                                class="inline-block w-2 h-2 rounded-full bg-red-accent mr-1.5 align-middle"></span>
                                            Kritis
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-right">
                                    <a href="{{ route('bk.rekap.detail_presensi', $s->id) }}"
                                        class="text-xs text-brand-primary hover:text-brand-navy font-semibold">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-12 text-center text-neutral-muted">
                                    Tidak ada data siswa yang cocok dengan filter yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>