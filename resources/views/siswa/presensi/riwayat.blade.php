<x-layouts.app>
    <x-slot:title>Riwayat Presensi Siswa</x-slot:title>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-text flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-brand-primary"></i>
                    <span>Riwayat Presensi Belajar</span>
                </h1>
                <p class="text-xs text-neutral-muted mt-1">
                    Rekapitulasi log presensi tatap muka dan persentase kehadiran belajar Anda di Alto.
                </p>
            </div>
            <a href="{{ route('siswa.presensi.qr') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-sm transition-all">
                <i class="fa-solid fa-qrcode mr-2 text-xs"></i>
                <span>Tampilkan QR Identitas</span>
            </a>
        </div>

        <!-- Health Matrix KPI Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            <div class="bg-white border border-neutral-border rounded-2xl p-4 shadow-sm">
                <span class="text-[10px] uppercase font-bold text-neutral-muted">Total Sesi</span>
                <div class="text-2xl font-extrabold text-neutral-text mt-1">{{ $stats['total'] }}</div>
                <span class="text-[10px] text-neutral-muted">Pertemuan kelas</span>
            </div>

            <div class="bg-white border border-neutral-border rounded-2xl p-4 shadow-sm border-b-2 border-b-emerald-500">
                <span class="text-[10px] uppercase font-bold text-emerald-600">Hadir Tepat</span>
                <div class="text-2xl font-extrabold text-neutral-text mt-1">{{ $stats['hadir'] }}</div>
                <span class="text-[10px] text-neutral-muted">Sesuai jadwal</span>
            </div>

            <div class="bg-white border border-neutral-border rounded-2xl p-4 shadow-sm border-b-2 border-b-amber-accent">
                <span class="text-[10px] uppercase font-bold text-amber-accent">Terlambat</span>
                <div class="text-2xl font-extrabold text-neutral-text mt-1">{{ $stats['terlambat'] }}</div>
                <span class="text-[10px] text-neutral-muted">Tercatat masuk</span>
            </div>

            <div class="bg-white border border-neutral-border rounded-2xl p-4 shadow-sm border-b-2 border-b-brand-active">
                <span class="text-[10px] uppercase font-bold text-brand-primary">Izin / Sakit</span>
                <div class="text-2xl font-extrabold text-neutral-text mt-1">{{ $stats['izin'] + $stats['sakit'] }}</div>
                <span class="text-[10px] text-neutral-muted">Dengan surat</span>
            </div>

            <div class="bg-white border border-neutral-border rounded-2xl p-4 shadow-sm border-b-2 border-b-red-accent col-span-2 sm:col-span-1">
                <span class="text-[10px] uppercase font-bold text-red-accent">Tanpa Keterangan</span>
                <div class="text-2xl font-extrabold text-neutral-text mt-1">{{ $stats['alfa'] }}</div>
                <span class="text-[10px] text-neutral-muted">Alfa</span>
            </div>
        </div>

        <!-- Cumulative Attendance Scorecard Banner -->
        @php
            $colorClasses = [
                'green' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
                'yellow' => 'bg-amber-accent-soft border-amber-accent/30 text-amber-accent-text',
                'red' => 'bg-red-accent-soft border-red-accent/30 text-red-accent-text',
            ];
            $currentClass = $colorClasses[$stats['color']] ?? $colorClasses['green'];
        @endphp
        <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-muted block">Indeks Kehadiran Akumulatif</span>
                <div class="text-3xl font-extrabold text-neutral-text mt-1 flex items-center gap-3">
                    <span>{{ $stats['percentage'] }}%</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full font-bold border {{ $stats['color'] === 'green' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($stats['color'] === 'yellow' ? 'bg-amber-accent-soft text-amber-accent-text border-amber-accent/30' : 'bg-red-accent-soft text-red-accent-text border-red-accent/30') }}">
                        Kategori {{ $stats['status_label'] }}
                    </span>
                </div>
                <p class="text-xs text-neutral-muted mt-2">
                    @if ($stats['percentage'] >= 90)
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1.5 align-middle"></span> Kehadiran sangat baik. Pertahankan kedisiplinan belajar Anda!
                    @elseif ($stats['percentage'] >= 75)
                        <span class="inline-block w-2 h-2 rounded-full bg-amber-accent mr-1.5 align-middle"></span> Peringatan: Kehadiran berada di ambang batas minimum evaluasi bimbingan.
                    @else
                        <span class="inline-block w-2 h-2 rounded-full bg-red-accent mr-1.5 align-middle"></span> Kritis: Segera konsultasikan dengan Guru BK terkait absensi Anda.
                    @endif
                </p>
            </div>
        </div>

        <!-- Attendance Logs Table -->
        <div class="bg-white border border-neutral-border rounded-2xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-neutral-border">
                <h2 class="text-sm font-bold text-neutral-text">Log Riwayat Presensi Terakhir</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-neutral-text">
                    <thead class="bg-neutral-subtle/80 text-neutral-muted uppercase tracking-wider border-b border-neutral-border">
                        <tr>
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">Waktu Scan</th>
                            <th class="px-5 py-3.5">Status Presensi</th>
                            <th class="px-5 py-3.5">Dipindai Oleh Guru</th>
                            <th class="px-5 py-3.5">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-border">
                        @forelse ($attendances as $row)
                            <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                <td class="px-5 py-4 font-semibold text-neutral-text">
                                    {{ $row->date->format('d M Y') }}
                                </td>
                                <td class="px-5 py-4 font-mono text-neutral-muted">
                                    {{ substr($row->session_time, 0, 5) }} WIB
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $statusBadges = [
                                            'hadir' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'terlambat' => 'bg-amber-accent-soft text-amber-accent-text border-amber-accent/30',
                                            'sakit' => 'bg-brand-soft text-brand-primary border-brand-primary/20',
                                            'izin' => 'bg-brand-soft text-brand-navy border-brand-navy/20',
                                            'alfa' => 'bg-red-accent-soft text-red-accent-text border-red-accent/30',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border {{ $statusBadges[$row->status] ?? 'bg-neutral-subtle text-neutral-muted border-neutral-border' }}">
                                        {{ ucfirst($row->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-brand-primary font-medium">
                                    {{ $row->scannedBy?->user?->name ?? '-' }}
                                </td>
                                <td class="px-5 py-4 text-neutral-muted italic">
                                    {{ $row->remarks ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-neutral-muted">
                                    Belum ada catatan presensi yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($attendances->hasPages())
                <div class="p-4 border-t border-neutral-border">
                    {{ $attendances->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
