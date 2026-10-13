<x-layouts.app>
    <x-slot:title>Portal Orang Tua / Wali Murid</x-slot:title>

    <div class="space-y-6">
        <!-- Hero Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-neutral-border/80 p-6 sm:p-8 shadow-sm">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-brand-blue via-accent-gold to-brand-blue"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200 mb-3">
                        <span class="w-2 h-2 rounded-full bg-accent-gold animate-pulse"></span>
                        <i class="fa-solid fa-users text-[10px]"></i>
                        Transparansi Akademik Putra-Putri Anda
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-neutral-dark tracking-tight">Selamat Datang, {{ $user->name }}!</h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Pantau riwayat presensi harian secara transparan, capaian tryout SNBT, setoran kosakata, dan gateway notifikasi WhatsApp.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <a href="{{ route('ortu.konsultasi.booking') }}" class="px-4 py-2.5 rounded-xl bg-brand-blue hover:bg-brand-blue-hover text-white text-xs font-bold shadow-md transition-all flex items-center gap-2">
                        <i class="fa-solid fa-calendar-plus text-accent-gold"></i>
                        <span>Booking Konsultasi BK</span>
                    </a>
                    <a href="{{ route('ortu.kontak') }}" class="px-4 py-2.5 rounded-xl bg-white hover:bg-[#FFFAF3] text-neutral-dark text-xs font-bold border border-neutral-border shadow-2xs transition-all flex items-center gap-2">
                        <i class="fa-solid fa-address-book text-brand-blue"></i>
                        <span>Kontak Pembimbing</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Children Overviews -->
        @foreach ($studentData as $data)
            @php
                $child = $data['student'];
                $stats = $data['attendance_stats'];
                $tryout = $data['latest_tryout'];
                $flags = $data['active_flags'];
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border border-neutral-border/60 p-6 relative overflow-hidden space-y-6">
                <!-- Child Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-neutral-border">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-accent-gold">Putra / Putri Terdaftar</span>
                            @if ($flags->isNotEmpty())
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-accent-red-soft text-accent-red border border-accent-red-border flex items-center gap-1">
                                    <i class="fa-solid fa-triangle-exclamation text-[9px]"></i> Perlu Perhatian ({{ $flags->count() }} Alert)
                                </span>
                            @endif
                        </div>
                        <h2 class="text-2xl font-extrabold text-neutral-dark mt-1">{{ $child->user?->name }}</h2>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-neutral-muted mt-1">
                            <span>NISN: <span class="font-mono text-neutral-dark font-bold">{{ $child->nisn }}</span></span>
                            <span>•</span>
                            <span>Kelas: <span class="text-neutral-dark font-medium">{{ $child->academicClass?->name }}</span></span>
                            <span>•</span>
                            <span>Jurusan: <span class="text-neutral-dark font-medium">{{ $child->major?->name }}</span></span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('ortu.rekap.presensi') }}" class="px-3.5 py-1.5 rounded-lg bg-white hover:bg-[#FFFAF3] text-neutral-dark text-xs font-bold border border-neutral-border shadow-2xs flex items-center gap-1.5">
                            <i class="fa-solid fa-calendar-check text-brand-blue"></i> Riwayat Presensi
                        </a>
                        <a href="{{ route('ortu.rekap.hasil') }}" class="px-3.5 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-brand-blue text-xs font-bold border border-blue-200 transition-all flex items-center gap-1.5">
                            <i class="fa-solid fa-chart-pie text-brand-blue"></i> Raport &amp; Nilai TO
                        </a>
                    </div>
                </div>

                <!-- KPI Metric Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <!-- Attendance Rate -->
                    <div class="bg-[#FFFAF3] border border-neutral-border rounded-xl p-4">
                        <span class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider block">Tingkat Kehadiran</span>
                        <div class="flex items-baseline gap-2 mt-2">
                            <span class="text-2xl font-extrabold text-neutral-dark">{{ $stats['percentage'] }}%</span>
                            @if ($stats['color'] === 'green')
                                <span class="text-xs font-bold text-emerald-700">Normal</span>
                            @elseif ($stats['color'] === 'yellow')
                                <span class="text-xs font-bold text-amber-800">Warning</span>
                            @else
                                <span class="text-xs font-bold text-accent-red">Kritis</span>
                            @endif
                        </div>
                        <div class="w-full bg-neutral-200 rounded-full h-1.5 mt-2">
                            <div class="h-1.5 rounded-full {{ $stats['color'] === 'green' ? 'bg-emerald-500' : ($stats['color'] === 'yellow' ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $stats['percentage'] }}%"></div>
                        </div>
                        <span class="text-[10px] text-neutral-muted mt-1 block">{{ $stats['hadir'] }} Hadir • {{ $stats['terlambat'] }} Terlambat • {{ $stats['alfa'] }} Alfa</span>
                    </div>

                    <!-- Tryout SNBT Score -->
                    <div class="bg-[#FFFAF3] border border-neutral-border rounded-xl p-4">
                        <span class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider block">Rata-Rata Tryout SNBT</span>
                        <div class="text-2xl font-extrabold text-neutral-dark mt-2">
                            {{ $tryout ? $tryout->average_score : '-' }}
                        </div>
                        <span class="text-[11px] text-neutral-muted mt-1 block truncate">
                            {{ $tryout ? $tryout->tryout?->title : 'Belum mengikuti ujian' }}
                        </span>
                    </div>

                    <!-- English Vocab Mastery -->
                    <div class="bg-[#FFFAF3] border border-neutral-border rounded-xl p-4">
                        <span class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider block">Vocab Mastery Module</span>
                        <div class="text-2xl font-extrabold text-neutral-dark mt-2">
                            {{ $data['passed_vocab_count'] }} <span class="text-sm font-normal text-neutral-muted">/ 8 Modul</span>
                        </div>
                        <span class="text-[11px] text-brand-blue font-semibold mt-1 block">Target Lulus &ge; 75 pts</span>
                    </div>

                    <!-- Mentorship Link -->
                    <div class="bg-[#FFFAF3] border border-neutral-border rounded-xl p-4">
                        <span class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider block">Tim Pembimbing</span>
                        <div class="text-xs font-bold text-neutral-dark mt-2">
                            Guru Mentor: <span class="text-brand-blue font-semibold">{{ $child->mentor?->user?->name ?? '-' }}</span>
                        </div>
                        <div class="text-xs font-bold text-neutral-dark mt-1">
                            Guru BK: <span class="text-purple-700 font-semibold">{{ $child->counselor?->name ?? 'Kak Nisa' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Attendance Logs & Alerts -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                    <!-- Recent Presensi -->
                    <div class="bg-white border border-neutral-border rounded-xl p-4 shadow-2xs">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-bold text-neutral-dark flex items-center gap-1.5">
                                <i class="fa-solid fa-calendar-days text-brand-blue"></i> Riwayat Presensi Terakhir
                            </h3>
                            <a href="{{ route('ortu.rekap.presensi') }}" class="text-xs font-bold text-brand-blue hover:underline">Semua &rarr;</a>
                        </div>

                        @if ($data['recent_attendances']->isNotEmpty())
                            <div class="space-y-2">
                                @foreach ($data['recent_attendances'] as $att)
                                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-[#FFFAF3] border border-neutral-border text-xs">
                                        <div>
                                            <span class="font-bold text-neutral-dark">{{ \Carbon\Carbon::parse($att->date)->translatedFormat('l, d M Y') }}</span>
                                            <span class="text-neutral-muted block text-[11px]">{{ substr($att->session_time, 0, 5) }} WIB</span>
                                        </div>
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $att->status === 'hadir' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($att->status === 'terlambat' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                                            {{ $att->status }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-neutral-muted py-4 text-center">Belum ada riwayat kehadiran tercatat.</p>
                        @endif
                    </div>

                    <!-- Active Warnings / Early Alert -->
                    <div class="bg-white border border-neutral-border rounded-xl p-4 flex flex-col justify-between shadow-2xs">
                        <div>
                            <h3 class="text-sm font-bold text-neutral-dark mb-3 flex items-center gap-1.5">
                                <i class="fa-solid fa-triangle-exclamation text-accent-red"></i> Status Pantauan Khusus
                            </h3>
                            @if ($flags->isNotEmpty())
                                <div class="space-y-2">
                                    @foreach ($flags as $f)
                                        <div class="p-3 rounded-lg bg-accent-red-soft border border-accent-red-border text-xs text-accent-red">
                                            <div class="font-bold mb-0.5 flex items-center gap-1">
                                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i> {{ strtoupper($f->flag_type) }}
                                            </div>
                                            <p class="text-neutral-dark mt-0.5">{{ $f->reason }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-center">
                                    <i class="fa-solid fa-circle-check text-emerald-600 text-xl block mb-1"></i>
                                    <p class="text-xs font-bold text-emerald-800">Tidak Ada Peringatan</p>
                                    <p class="text-[11px] text-neutral-muted mt-0.5">Semua indikator belajar putra/putri Anda berada dalam batas normal dan terpantau dengan baik.</p>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 pt-3 border-t border-neutral-border">
                            <a href="{{ route('ortu.konsultasi.booking') }}" class="w-full inline-flex justify-center items-center gap-1.5 py-2 rounded-lg bg-[#FFFAF3] hover:bg-neutral-100 text-neutral-dark text-xs font-bold border border-neutral-border transition-all">
                                <i class="fa-solid fa-comments text-brand-blue"></i>
                                <span>Butuh Diskusi? Jadwalkan Konsultasi dengan Guru BK &rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Recent WhatsApp Automated Gateway Notification Logs -->
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-border/60 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-neutral-dark flex items-center gap-2">
                        <i class="fa-brands fa-whatsapp text-emerald-600 text-lg"></i>
                        <span>Log Notifikasi Otomatis WhatsApp</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    </h3>
                    <p class="text-xs text-neutral-muted">Pesan otomatis yang dikirimkan ke nomor WhatsApp terdaftar Anda</p>
                </div>
                <span class="text-xs font-mono font-bold text-brand-blue">{{ $studentData[0]['student']->parent_phone_number ?? '' }}</span>
            </div>

            @if ($recentWaLogs->isNotEmpty())
                <div class="space-y-3">
                    @foreach ($recentWaLogs as $waLog)
                        <div class="p-3.5 rounded-xl bg-[#FFFAF3] border border-neutral-border text-xs">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="font-bold text-emerald-700 flex items-center gap-1.5">
                                    <i class="fa-solid fa-check-double text-emerald-600"></i>
                                    <span>Pesan Terkirim</span>
                                    <span class="text-neutral-muted font-normal">({{ strtoupper(str_replace('_', ' ', $waLog->trigger_event)) }})</span>
                                </span>
                                <span class="text-neutral-muted">{{ $waLog->sent_at?->diffForHumans() }}</span>
                            </div>
                            <p class="text-neutral-dark font-mono text-[11px] whitespace-pre-line bg-white p-2.5 rounded border border-neutral-border">{{ $waLog->message_payload }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-neutral-muted py-6 text-center">Belum ada riwayat pesan WhatsApp terkirim.</p>
            @endif
        </div>
    </div>
</x-layouts.app>
