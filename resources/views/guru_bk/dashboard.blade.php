<x-layouts.app>
    <x-slot:title>Dashboard Guru BK &amp; Matriks EWS</x-slot:title>

    <div class="space-y-6">
        <!-- Hero Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-neutral-border/80 p-6 sm:p-8 shadow-sm">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-brand-blue via-accent-gold to-brand-blue"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200 mb-3">
                        <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                        <i class="fa-solid fa-brain text-[10px]"></i>
                        Bimbingan Konseling &amp; Pemantauan Perilaku Siswa
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-neutral-dark tracking-tight">Selamat Datang, {{ $user->name }}!</h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Sistem Monitoring Matriks Kesehatan Kehadiran, Flagging Otomatis, dan Konsultasi Wali Murid
                    </p>
                </div>
            </div>
        </div>

        <!-- Real-Time Attendance Health Matrix (Green / Yellow / Red) -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <!-- Green Card -->
            <a href="{{ route('bk.rekap.presensi', ['color' => 'green']) }}" class="group bg-white rounded-xl shadow-sm border border-neutral-border/60 hover:border-emerald-500 p-5 border-l-4 border-l-emerald-500 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Normal (&ge; 90%)
                    </span>
                    <i class="fa-solid fa-arrow-right text-xs text-neutral-muted group-hover:text-emerald-600 transition-colors"></i>
                </div>
                <div class="text-3xl font-extrabold text-neutral-dark mt-2">{{ $greenCount }} <span class="text-sm font-normal text-neutral-muted">Siswa</span></div>
                <p class="text-xs text-neutral-muted mt-1">Kehadiran konsisten &amp; disiplin</p>
            </a>

            <!-- Yellow Card -->
            <a href="{{ route('bk.rekap.presensi', ['color' => 'yellow']) }}" class="group bg-white rounded-xl shadow-sm border border-neutral-border/60 hover:border-amber-500 p-5 border-l-4 border-l-amber-500 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-amber-800 uppercase tracking-wider inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span> Warning (75% - 89%)
                    </span>
                    <i class="fa-solid fa-arrow-right text-xs text-neutral-muted group-hover:text-amber-600 transition-colors"></i>
                </div>
                <div class="text-3xl font-extrabold text-neutral-dark mt-2">{{ $yellowCount }} <span class="text-sm font-normal text-neutral-muted">Siswa</span></div>
                <p class="text-xs text-neutral-muted mt-1">Perlu pengingat berkala</p>
            </a>

            <!-- Red Card -->
            <a href="{{ route('bk.rekap.presensi', ['color' => 'red']) }}" class="group bg-white rounded-xl shadow-sm border border-neutral-border/60 hover:border-rose-500 p-5 border-l-4 border-l-rose-500 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-rose-700 uppercase tracking-wider inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span> Danger (&lt; 75%)
                    </span>
                    <i class="fa-solid fa-arrow-right text-xs text-neutral-muted group-hover:text-rose-600 transition-colors"></i>
                </div>
                <div class="text-3xl font-extrabold text-neutral-dark mt-2">{{ $redCount }} <span class="text-sm font-normal text-neutral-muted">Siswa</span></div>
                <p class="text-xs text-neutral-muted mt-1">Intervensi &amp; hubungi wali</p>
            </a>

            <!-- Open Flags Card -->
            <a href="{{ route('bk.flagging.index', ['status' => 'open']) }}" class="group bg-white rounded-xl shadow-sm border border-neutral-border/60 hover:border-purple-500 p-5 border-l-4 border-l-purple-500 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-purple-700 uppercase tracking-wider inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-triangle-exclamation text-xs"></i> Pelanggaran
                    </span>
                    <i class="fa-solid fa-arrow-right text-xs text-neutral-muted group-hover:text-purple-600 transition-colors"></i>
                </div>
                <div class="text-3xl font-extrabold text-neutral-dark mt-2">{{ $openFlagsCount }} <span class="text-sm font-normal text-neutral-muted">Antrean</span></div>
                <p class="text-xs text-neutral-muted mt-1">{{ $resolvedFlagsCount }} telah diselesaikan</p>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Active Automated Flags Queue (2 Cols) -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-neutral-border/60 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-neutral-dark flex items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation text-accent-red"></i>
                            Pelanggaran Siswa
                        </h2>
                        <p class="text-xs text-neutral-muted">Deteksi otomatis keterlambatan berulang &amp; ketidakhadiran kritis</p>
                    </div>
                    <a href="{{ route('bk.flagging.index') }}" class="text-xs font-bold text-brand-blue hover:underline">
                        Kelola Semua &rarr;
                    </a>
                </div>

                @if ($openFlags->isNotEmpty())
                    <div class="space-y-3">
                        @foreach ($openFlags as $flag)
                            <div class="p-4 rounded-xl bg-[#FFFAF3] border border-neutral-border flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        @if ($flag->flag_type === 'late_series')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200 flex items-center gap-1">
                                                <i class="fa-solid fa-stopwatch text-[9px]"></i> Terlambat Seri (&ge; 3x)
                                            </span>
                                        @elseif ($flag->flag_type === 'absence_critical')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-accent-red-soft text-accent-red border border-accent-red-border flex items-center gap-1">
                                                <i class="fa-solid fa-circle-exclamation text-[9px]"></i> Ketidakhadiran Kritis
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200 flex items-center gap-1">
                                                <i class="fa-solid fa-book text-[9px]"></i> Vocab Tertinggal
                                            </span>
                                        @endif
                                        <span class="text-xs text-neutral-muted">{{ $flag->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="text-sm font-bold text-brand-blue">{{ $flag->student->user->name }}</div>
                                    <p class="text-xs text-neutral-muted">{{ $flag->reason }}</p>
                                    <div class="text-[11px] text-neutral-muted">
                                        Kelas: {{ $flag->student->academicClass?->name }} • Mentor: {{ $flag->student->mentor?->user?->name }}
                                    </div>
                                </div>

                                <div class="shrink-0 flex items-center gap-2">
                                    <form method="POST" action="{{ route('bk.flagging.resolve', $flag->id) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="resolved">
                                        <input type="hidden" name="resolution_notes" value="Telah dikonfirmasi dan ditindaklanjuti oleh Guru BK melalui dashboard.">
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-bold border border-emerald-300 transition-all flex items-center gap-1">
                                            <i class="fa-solid fa-check text-[10px]"></i> Selesai
                                        </button>
                                    </form>
                                    <a href="{{ route('bk.flagging.index') }}" class="px-3 py-1.5 rounded-lg bg-white hover:bg-neutral-100 text-neutral-dark text-xs font-semibold border border-neutral-border transition-all">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-10 text-center text-neutral-muted bg-[#FFFAF3] rounded-xl border border-neutral-border">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-3xl block mb-2"></i>
                        <p class="text-sm font-bold text-neutral-dark">Semua Indikator Pelanggaran Bersih</p>
                        <p class="text-xs text-neutral-muted mt-1">Tidak ada ananda yang membutuhkan intervensi darurat saat ini.</p>
                    </div>
                @endif
            </div>

            <!-- Upcoming BK Consultation Sessions (1 Col) -->
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-border/60 p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-neutral-dark flex items-center gap-2">
                            <i class="fa-solid fa-hand-holding-heart text-purple-600"></i>
                            Sesi Konsultasi BK
                        </h2>
                        <a href="{{ route('bk.konsultasi.sessions') }}" class="text-xs font-bold text-brand-blue hover:underline">
                            Semua &rarr;
                        </a>
                    </div>

                    @if ($upcomingBkConsultations->isNotEmpty())
                        <div class="space-y-3">
                            @foreach ($upcomingBkConsultations as $booking)
                                <div class="p-3.5 rounded-xl bg-[#FFFAF3] border border-neutral-border text-xs space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-brand-blue">{{ $booking->student?->user?->name ?? 'Siswa' }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $booking->status === 'confirmed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-neutral-100 text-neutral-dark border border-neutral-200' }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>
                                    <div class="text-neutral-muted">
                                        Wali: {{ $booking->parent?->name ?? 'Wali Murid' }}
                                    </div>
                                    <div class="text-brand-blue font-semibold flex items-center gap-1.5">
                                        <i class="fa-solid fa-calendar-days text-[10px]"></i>
                                        {{ \Carbon\Carbon::parse($booking->slot?->date_time)->translatedFormat('d M Y, H:i') }} WIB
                                    </div>
                                    @if ($booking->notes)
                                        <p class="text-neutral-muted italic text-[11px] bg-white p-2 rounded border border-neutral-border">"{{ $booking->notes }}"</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-8 text-center text-neutral-muted">
                            <i class="fa-solid fa-calendar text-2xl text-neutral-muted/60 block mb-2"></i>
                            <p class="text-xs">Belum ada booking konsultasi wali murid yang menunggu.</p>
                        </div>
                    @endif
                </div>

                <div class="mt-4 pt-4 border-t border-neutral-border">
                    <a href="{{ route('bk.rekap.target') }}" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 rounded-xl bg-white hover:bg-[#FFFAF3] text-neutral-dark text-xs font-bold border border-neutral-border shadow-2xs transition-all">
                        <i class="fa-solid fa-bullseye text-accent-gold"></i>
                        <span>Lihat Rekap Target Bulanan Siswa &rarr;</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Real-Time Student Health Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-border/60 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <div>
                    <h2 class="text-lg font-bold text-neutral-dark">Monitoring Siswa Bimbingan</h2>
                    <p class="text-xs text-neutral-muted">Matriks presensi dan profil akademik siswa aktif</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('bk.rekap.presensi') }}" class="px-3.5 py-1.5 rounded-lg bg-white hover:bg-[#FFFAF3] text-xs font-bold text-neutral-dark border border-neutral-border shadow-2xs">
                        <i class="fa-solid fa-clipboard-check mr-1.5 text-brand-blue"></i> Rekap Lengkap Presensi
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-neutral-dark">
                    <thead class="text-xs uppercase bg-[#FFFAF3] text-neutral-muted border-b border-neutral-border">
                        <tr>
                            <th class="px-4 py-3 font-bold">Nama Siswa</th>
                            <th class="px-4 py-3 font-bold">NISN</th>
                            <th class="px-4 py-3 font-bold">Kelas &amp; Jurusan</th>
                            <th class="px-4 py-3 font-bold">Mentor Tertaut</th>
                            <th class="px-4 py-3 font-bold">Tingkat Kehadiran</th>
                            <th class="px-4 py-3 font-bold">Status Matriks</th>
                            <th class="px-4 py-3 text-right font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-border">
                        @foreach ($students as $student)
                            @php
                                $stats = $studentStats[$student->id] ?? ['percentage' => 100, 'color' => 'green', 'status_label' => 'Normal'];
                            @endphp
                            <tr class="hover:bg-[#FFFAF3]/60 transition-colors">
                                <td class="px-4 py-3.5 font-bold text-brand-blue">
                                    {{ $student->user->name }}
                                </td>
                                <td class="px-4 py-3.5 font-mono text-xs text-neutral-muted">
                                    {{ $student->nisn }}
                                </td>
                                <td class="px-4 py-3.5 text-xs">
                                    {{ $student->academicClass?->name }} ({{ $student->major?->name }})
                                </td>
                                <td class="px-4 py-3.5 text-xs text-brand-blue font-semibold">
                                    {{ $student->mentor?->user?->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-16 bg-neutral-200 rounded-full h-2">
                                            <div class="h-2 rounded-full {{ $stats['color'] === 'green' ? 'bg-emerald-500' : ($stats['color'] === 'yellow' ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $stats['percentage'] }}%"></div>
                                        </div>
                                        <span class="font-extrabold text-xs text-neutral-dark">{{ $stats['percentage'] }}%</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5">
                                    @if ($stats['color'] === 'green')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Normal
                                        </span>
                                    @elseif ($stats['color'] === 'yellow')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Warning
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Kritis
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-right">
                                    <a href="{{ route('guru.bimbingan.student.profile', $student->id) }}" class="text-xs text-brand-blue hover:underline font-bold">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
