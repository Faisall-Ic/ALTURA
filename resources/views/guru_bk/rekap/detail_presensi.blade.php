<x-layouts.app>
    <x-slot:title>Detail Presensi Siswa - {{ $student->user->name }}</x-slot:title>

    <div class="space-y-6">
        <!-- Header Banner & Tombol Kembali -->
        <div class="rounded-2xl bg-white border border-neutral-border p-6 sm:p-8 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20 mb-3">
                        <span class="w-2 h-2 rounded-full bg-brand-primary"></span>
                        Detail Riwayat Kehadiran Siswa
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-neutral-text flex items-center gap-2">
                        <i class="fa-solid fa-user-clock text-brand-primary"></i>
                        <span>{{ $student->user->name }}</span>
                    </h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        NISN: <span class="font-mono font-semibold text-neutral-text">{{ $student->nisn }}</span> &bull; Kelas: <span class="font-semibold text-neutral-text">{{ $student->academicClass?->name }}</span> ({{ $student->major?->name }})
                    </p>
                </div>
                <div>
                    <a href="{{ route('bk.rekap.presensi') }}" class="px-4 py-2 rounded-xl bg-white hover:bg-neutral-subtle text-neutral-text text-xs font-semibold border border-neutral-border transition-all shadow-sm">
                        <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Rekap Presensi
                    </a>
                </div>
            </div>
        </div>

        <!-- Tabel Log Riwayat Presensi Detail Siswa -->
        <div class="bg-white border border-neutral-border rounded-2xl overflow-hidden shadow-sm">
            <div class="p-5 border-b border-neutral-border flex items-center justify-between">
                <h2 class="text-sm font-bold text-neutral-text flex items-center gap-2">
                    <i class="fa-solid fa-calendar-days text-brand-primary"></i>
                    <span>Log Kehadiran & Riwayat Sesi</span>
                </h2>
                <span class="text-xs text-neutral-muted font-mono">
                    Total: {{ $attendances->count() }} Kehadiran tercatat
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-neutral-text">
                    <thead class="bg-neutral-subtle/80 text-neutral-muted uppercase tracking-wider border-b border-neutral-border">
                        <tr>
                            <th class="px-5 py-3.5">Tanggal & Waktu</th>
                            <th class="px-5 py-3.5">Status Presensi</th>
                            <th class="px-5 py-3.5">Dipindai Oleh</th>
                            <th class="px-5 py-3.5">Keterangan / Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-border">
                        @forelse ($attendances as $row)
                            <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                <td class="px-5 py-4 font-mono text-neutral-text">
                                    <div class="font-semibold">{{ \Carbon\Carbon::parse($row->date ?? $row->created_at)->translatedFormat('d M Y') }}</div>
                                    <span class="text-[11px] text-neutral-muted">{{ substr($row->session_time ?? '00:00', 0, 5) }} WIB</span>
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $badgeClasses = [
                                            'hadir' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'terlambat' => 'bg-amber-accent-soft text-amber-accent-text border-amber-accent/30',
                                            'sakit' => 'bg-brand-soft text-brand-primary border-brand-primary/20',
                                            'izin' => 'bg-brand-soft text-brand-navy border-brand-navy/20',
                                            'alfa' => 'bg-red-accent-soft text-red-accent-text border-red-accent/30',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $badgeClasses[$row->status] ?? 'bg-neutral-subtle text-neutral-muted border-neutral-border' }}">
                                        {{ ucfirst($row->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-brand-primary font-medium">
                                    {{ $row->scannedBy?->user?->name ?? 'Sistem / Mandiri' }}
                                </td>
                                <td class="px-5 py-4 text-neutral-muted italic">
                                    {{ $row->remarks ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center text-neutral-muted">
                                    <i class="fa-solid fa-folder-open text-3xl text-neutral-border block mb-2"></i>
                                    Belum ada catatan riwayat presensi untuk siswa ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>