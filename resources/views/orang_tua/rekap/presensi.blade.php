<x-layouts.app>
    <x-slot:title>Riwayat Presensi Ananda — Portal Orang Tua</x-slot:title>

    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-neutral-border shadow-xs p-6 sm:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20 mb-3">
                        <span class="w-2 h-2 rounded-full bg-brand-primary animate-pulse"></span>
                        Transparansi Presensi Harian
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-neutral-text">Riwayat Kehadiran: {{ $child->user?->name }} <i class="fa-solid fa-calendar-days ml-1 text-brand-primary"></i></h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Kelas: {{ $child->academicClass?->name }} ({{ $child->major?->name }}) • NISN: {{ $child->nisn }}
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('ortu.dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold border border-neutral-border transition-all">
                        <i class="fa-solid fa-arrow-left text-[11px]"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Attendance Stats KPI Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-6 gap-3">
            <div class="bg-white border border-neutral-border rounded-xl p-4 text-center shadow-xs">
                <span class="text-[10px] font-bold text-neutral-muted uppercase tracking-wider block">Total Sesi</span>
                <span class="text-2xl font-extrabold text-neutral-text mt-1 block">{{ $stats['total'] }}</span>
            </div>
            <div class="bg-white border border-neutral-border rounded-xl p-4 text-center shadow-xs">
                <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider block">Hadir</span>
                <span class="text-2xl font-extrabold text-emerald-600 mt-1 block">{{ $stats['hadir'] }}</span>
            </div>
            <div class="bg-white border border-neutral-border rounded-xl p-4 text-center shadow-xs">
                <span class="text-[10px] font-bold text-amber-accent-text uppercase tracking-wider block">Terlambat</span>
                <span class="text-2xl font-extrabold text-amber-accent mt-1 block">{{ $stats['terlambat'] }}</span>
            </div>
            <div class="bg-white border border-neutral-border rounded-xl p-4 text-center shadow-xs">
                <span class="text-[10px] font-bold text-brand-primary uppercase tracking-wider block">Sakit</span>
                <span class="text-2xl font-extrabold text-brand-primary mt-1 block">{{ $stats['sakit'] }}</span>
            </div>
            <div class="bg-white border border-neutral-border rounded-xl p-4 text-center shadow-xs">
                <span class="text-[10px] font-bold text-purple-700 uppercase tracking-wider block">Izin</span>
                <span class="text-2xl font-extrabold text-purple-700 mt-1 block">{{ $stats['izin'] }}</span>
            </div>
            <div class="bg-white border border-neutral-border rounded-xl p-4 text-center shadow-xs">
                <span class="text-[10px] font-bold text-red-accent-text uppercase tracking-wider block">Alfa</span>
                <span class="text-2xl font-extrabold text-red-accent mt-1 block">{{ $stats['alfa'] }}</span>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="flex flex-wrap items-center justify-between gap-4 bg-white border border-neutral-border p-4 rounded-xl shadow-xs">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('ortu.rekap.presensi') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ empty($status) ? 'bg-brand-primary text-white shadow-xs' : 'bg-neutral-subtle text-neutral-muted hover:bg-neutral-border/50' }}">Semua</a>
                <a href="{{ route('ortu.rekap.presensi', ['status' => 'hadir']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $status === 'hadir' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-neutral-subtle text-neutral-muted hover:bg-neutral-border/50' }}">Hadir</a>
                <a href="{{ route('ortu.rekap.presensi', ['status' => 'terlambat']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $status === 'terlambat' ? 'bg-amber-accent text-white shadow-xs' : 'bg-neutral-subtle text-neutral-muted hover:bg-neutral-border/50' }}">Terlambat</a>
                <a href="{{ route('ortu.rekap.presensi', ['status' => 'sakit']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $status === 'sakit' ? 'bg-brand-primary text-white shadow-xs' : 'bg-neutral-subtle text-neutral-muted hover:bg-neutral-border/50' }}">Sakit</a>
                <a href="{{ route('ortu.rekap.presensi', ['status' => 'izin']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $status === 'izin' ? 'bg-purple-600 text-white shadow-xs' : 'bg-neutral-subtle text-neutral-muted hover:bg-neutral-border/50' }}">Izin</a>
                <a href="{{ route('ortu.rekap.presensi', ['status' => 'alfa']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $status === 'alfa' ? 'bg-red-accent text-white shadow-xs' : 'bg-neutral-subtle text-neutral-muted hover:bg-neutral-border/50' }}">Alfa</a>
            </div>

            <div class="text-xs text-neutral-muted">
                Persentase Kehadiran Kumulatif: <span class="font-extrabold text-neutral-text">{{ $stats['percentage'] }}%</span>
            </div>
        </div>

        <!-- Attendance Logs Table -->
        <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs">
            @if ($attendances->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-neutral-text">
                        <thead class="text-xs uppercase bg-neutral-subtle/70 text-neutral-muted border-b border-neutral-border font-semibold">
                            <tr>
                                <th class="px-4 py-3">Tanggal Sesi</th>
                                <th class="px-4 py-3">Waktu Check-In</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Pemindai (Guru)</th>
                                <th class="px-4 py-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-border">
                            @foreach ($attendances as $item)
                                <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                    <td class="px-4 py-3.5 font-medium text-neutral-text">
                                        {{ \Carbon\Carbon::parse($item->date)->translatedFormat('l, d F Y') }}
                                    </td>
                                    <td class="px-4 py-3.5 font-mono text-xs text-neutral-muted">
                                        {{ substr($item->session_time, 0, 5) }} WIB
                                    </td>
                                    <td class="px-4 py-3.5">
                                        @if ($item->status === 'hadir')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1.5 align-middle"></span> Hadir
                                            </span>
                                        @elseif ($item->status === 'terlambat')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-accent-soft text-amber-accent-text border border-amber-accent/30">
                                                <span class="inline-block w-2 h-2 rounded-full bg-amber-accent mr-1.5 align-middle"></span> Terlambat
                                            </span>
                                        @elseif ($item->status === 'sakit')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-brand-soft text-brand-primary border border-brand-primary/20">
                                                Sakit
                                            </span>
                                        @elseif ($item->status === 'izin')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                                Izin
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-accent-soft text-red-accent-text border border-red-accent/30">
                                                <span class="inline-block w-2 h-2 rounded-full bg-red-accent mr-1.5 align-middle"></span> Alfa
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-xs text-brand-primary font-medium">
                                        {{ $item->scannedByTeacher?->user?->name ?? 'Petugas Piket' }}
                                    </td>
                                    <td class="px-4 py-3.5 text-xs text-neutral-muted">
                                        {{ $item->remarks ?: '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $attendances->links() }}
                </div>
            @else
                <div class="py-12 text-center text-neutral-muted">
                    <span class="text-3xl block mb-2 text-brand-primary"><i class="fa-solid fa-calendar-days"></i></span>
                    <p class="text-sm font-semibold text-neutral-text">Tidak ada data kehadiran yang cocok.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
