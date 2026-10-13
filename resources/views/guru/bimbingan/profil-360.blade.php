<x-layouts.app>
    <x-slot:title>Profil 360° Siswa — {{ $student->user?->name }}</x-slot:title>

    <div class="space-y-6">
        <!-- Top Nav Link -->
        <div class="flex items-center justify-between text-xs">
            <a href="{{ url()->previous(route('guru.presensi.session.log')) }}" class="text-brand-primary hover:text-brand-navy flex items-center gap-1.5 font-semibold">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Kembali</span>
            </a>
            <span class="text-neutral-muted font-mono">NISN: {{ $student->nisn }}</span>
        </div>

        <!-- Student Hero Card (Profil 360° Header) -->
        <div class="bg-white border border-neutral-border rounded-2xl p-6 sm:p-8 shadow-sm relative overflow-hidden">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-brand-primary flex items-center justify-center text-white font-extrabold text-2xl shadow-sm shrink-0">
                        {{ strtoupper(substr($student->user?->name ?? 'S', 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-bold text-neutral-text">{{ $student->user?->name }}</h1>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $student->status_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-accent-soft text-red-accent-text border border-red-accent/20' }}">
                                <span class="inline-block w-2 h-2 rounded-full {{ $student->status_active ? 'bg-emerald-500' : 'bg-red-accent' }} mr-1.5 align-middle"></span> {{ $student->status_active ? 'AKTIF' : 'NON-AKTIF' }}
                            </span>
                        </div>
                        <p class="text-xs text-neutral-muted mt-1">
                            Kelas <span class="text-neutral-text font-semibold">{{ $student->academicClass?->name }}</span> • Jurusan <span class="text-brand-primary font-semibold">{{ $student->major?->name }}</span>
                        </p>
                        <p class="text-xs text-neutral-muted mt-0.5">
                            Target Siswa: <span class="text-amber-accent font-medium">Lolos PTN Impian</span>
                        </p>
                    </div>
                </div>

                <!-- Quick WhatsApp Actions -->
                <div class="flex flex-wrap items-center gap-2">
                    @if ($student->parent_phone_number)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->parent_phone_number) }}" target="_blank"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 transition-all shadow-sm">
                            <i class="fa-brands fa-whatsapp text-emerald-600 text-sm"></i>
                            <span>Hubungi Orang Tua ({{ $student->parent_phone_number }})</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Mentor & Counselor Grid -->
            <div class="mt-6 pt-5 border-t border-neutral-border grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div class="bg-neutral-subtle/60 p-3.5 rounded-xl border border-neutral-border">
                    <span class="text-[10px] uppercase font-bold text-neutral-muted block">Guru Mentor Resmi:</span>
                    <span class="font-bold text-brand-primary mt-0.5 block">{{ $student->mentor?->user?->name ?? 'Belum Ditugaskan' }}</span>
                </div>
                <div class="bg-neutral-subtle/60 p-3.5 rounded-xl border border-neutral-border">
                    <span class="text-[10px] uppercase font-bold text-neutral-muted block">Guru BK / Konselor:</span>
                    <span class="font-bold text-neutral-text mt-0.5 block">{{ $student->counselor?->name ?? 'Kak Nisa (BK Alto)' }}</span>
                </div>
                <div class="bg-neutral-subtle/60 p-3.5 rounded-xl border border-neutral-border">
                    <span class="text-[10px] uppercase font-bold text-neutral-muted block">Token QR Siswa:</span>
                    <span class="font-mono text-brand-primary mt-0.5 block truncate">{{ $student->qr_identity_token }}</span>
                </div>
            </div>
        </div>

        <!-- Attendance Health Matrix KPI & Cumulative Score -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Left Scorecard -->
            @php
                $colorThemes = [
                    'green' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
                    'yellow' => 'bg-amber-accent-soft border-amber-accent/30 text-amber-accent-text',
                    'red' => 'bg-red-accent-soft border-red-accent/30 text-red-accent-text',
                ];
                $theme = $colorThemes[$stats['color']] ?? $colorThemes['green'];
            @endphp
            <div class="bg-white border border-neutral-border rounded-2xl p-5 flex flex-col justify-between shadow-sm">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider block text-neutral-muted">Tingkat Kehadiran</span>
                    <div class="text-4xl font-extrabold text-neutral-text mt-1">{{ $stats['percentage'] }}%</div>
                    <span class="inline-block mt-2 text-xs px-2.5 py-0.5 rounded-full font-bold border {{ $stats['color'] === 'green' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($stats['color'] === 'yellow' ? 'bg-amber-accent-soft text-amber-accent-text border-amber-accent/30' : 'bg-red-accent-soft text-red-accent-text border-red-accent/30') }}">
                        Kategori {{ $stats['status_label'] }}
                    </span>
                </div>
                <p class="text-[11px] text-neutral-muted mt-4">
                    @if ($stats['percentage'] >= 90)
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1.5 align-middle"></span> Kehadiran sangat baik. Tidak ada indikasi resiko ketertinggalan.
                    @elseif ($stats['percentage'] >= 75)
                        <span class="inline-block w-2 h-2 rounded-full bg-amber-accent mr-1.5 align-middle"></span> Peringatan dini: Siswa memerlukan pemantauan presensi berkala.
                    @else
                        <span class="inline-block w-2 h-2 rounded-full bg-red-accent mr-1.5 align-middle"></span> Resiko Kritis: Perlu segera dijadwalkan sesi konseling bimbingan BK!
                    @endif
                </p>
            </div>

            <!-- Right 3 Cols: Metric Breakdown Cards -->
            <div class="md:col-span-3 grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="bg-white border border-neutral-border rounded-2xl p-4 flex flex-col justify-between shadow-sm">
                    <span class="text-[10px] uppercase font-bold text-neutral-muted">Total Sesi Terjadwal</span>
                    <div class="text-2xl font-extrabold text-neutral-text mt-2">{{ $stats['total'] }}</div>
                    <span class="text-[10px] text-neutral-muted">Pertemuan kelas</span>
                </div>

                <div class="bg-white border border-neutral-border rounded-2xl p-4 flex flex-col justify-between border-b-2 border-b-emerald-500 shadow-sm">
                    <span class="text-[10px] uppercase font-bold text-emerald-600">Hadir Tepat</span>
                    <div class="text-2xl font-extrabold text-neutral-text mt-2">{{ $stats['hadir'] }}</div>
                    <span class="text-[10px] text-neutral-muted">Tepat waktu</span>
                </div>

                <div class="bg-white border border-neutral-border rounded-2xl p-4 flex flex-col justify-between border-b-2 border-b-amber-accent shadow-sm">
                    <span class="text-[10px] uppercase font-bold text-amber-accent">Terlambat</span>
                    <div class="text-2xl font-extrabold text-neutral-text mt-2">{{ $stats['terlambat'] }}</div>
                    <span class="text-[10px] text-neutral-muted">Masuk terlambat</span>
                </div>

                <div class="bg-white border border-neutral-border rounded-2xl p-4 flex flex-col justify-between border-b-2 border-b-red-accent shadow-sm">
                    <span class="text-[10px] uppercase font-bold text-red-accent">Alfa (Tanpa Kabar)</span>
                    <div class="text-2xl font-extrabold text-neutral-text mt-2">{{ $stats['alfa'] }}</div>
                    <span class="text-[10px] text-neutral-muted">Izin/Sakit: {{ $stats['izin'] + $stats['sakit'] }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Box: 10 Sesi Presensi Terakhir -->
            <div class="bg-white border border-neutral-border rounded-2xl overflow-hidden shadow-sm">
                <div class="p-4 border-b border-neutral-border flex items-center justify-between">
                    <h2 class="text-sm font-bold text-neutral-text flex items-center gap-2">
                        <i class="fa-solid fa-calendar-check text-brand-primary"></i>
                        <span>10 Log Presensi Terakhir</span>
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-neutral-text">
                        <thead class="bg-neutral-subtle/80 text-neutral-muted uppercase tracking-wider border-b border-neutral-border">
                            <tr>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Waktu</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Dipindai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-border">
                            @forelse ($recentAttendances as $att)
                                <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                    <td class="px-4 py-3 font-semibold text-neutral-text">
                                        {{ $att->date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 font-mono text-neutral-muted">
                                        {{ substr($att->session_time, 0, 5) }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @php
                                            $badges = [
                                                'hadir' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'terlambat' => 'bg-amber-accent-soft text-amber-accent-text border-amber-accent/30',
                                                'sakit' => 'bg-brand-soft text-brand-primary border-brand-primary/20',
                                                'izin' => 'bg-brand-soft text-brand-navy border-brand-navy/20',
                                                'alfa' => 'bg-red-accent-soft text-red-accent-text border-red-accent/30',
                                            ];
                                        @endphp
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold border {{ $badges[$att->status] ?? 'bg-neutral-subtle text-neutral-muted border-neutral-border' }}">
                                            {{ ucfirst($att->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-neutral-muted truncate max-w-[120px]">
                                        {{ $att->scannedBy?->user?->name ?? 'Sistem' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-neutral-muted">
                                        Belum ada rekaman presensi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Box: Riwayat Bimbingan & Konsultasi -->
            <div class="bg-white border border-neutral-border rounded-2xl overflow-hidden shadow-sm">
                <div class="p-4 border-b border-neutral-border flex items-center justify-between">
                    <h2 class="text-sm font-bold text-neutral-text flex items-center gap-2">
                        <i class="fa-solid fa-comments text-brand-primary"></i>
                        <span>Riwayat Konsultasi & Tentoring</span>
                    </h2>
                </div>

                <div class="p-4 space-y-3">
                    @forelse ($consultationBookings as $cb)
                        <div class="bg-neutral-subtle/50 border border-neutral-border rounded-xl p-3.5 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-neutral-text text-xs">{{ $cb->slot?->topic ?? 'Sesi Bimbingan' }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $cb->status === 'confirmed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-neutral-subtle text-neutral-muted border border-neutral-border' }}">
                                    {{ ucfirst($cb->status) }}
                                </span>
                            </div>
                            <div class="text-[11px] text-neutral-muted flex items-center justify-between">
                                <span>Mentor: <strong class="text-brand-primary">{{ $cb->slot?->mentor?->user?->name ?? '-' }}</strong></span>
                                <span>{{ $cb->slot?->date_time?->format('d M Y, H:i') }} WIB</span>
                            </div>
                            @if ($cb->notes)
                                <p class="text-[11px] text-neutral-muted italic bg-white p-2 rounded-lg border border-neutral-border">
                                    "{{ $cb->notes }}"
                                </p>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-neutral-muted text-center py-6">
                            Siswa belum pernah memesan sesi konsultasi atau tentoring.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
