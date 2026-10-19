<x-layouts.app>
    <x-slot:title>Evaluasi &amp; Hasil Tryout SNBT</x-slot:title>

    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-neutral-border/80 p-6 sm:p-8 shadow-sm">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-brand-blue via-accent-gold to-brand-blue"></div>
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-soft text-brand-primary border border-blue-200 mb-3">
                        <span class="w-2 h-2 rounded-full bg-brand-primary animate-pulse"></span>
                        <i class="fa-solid fa-chart-line text-[10px]"></i>
                        Simulasi Ujian UTBK-SNBT Terstandar
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-neutral-text tracking-tight">Evaluasi &amp; Nilai Tryout SNBT</h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Pantau grafik perkembangan skor subtes, akurasi penalaran, dan unduh sertifikat resmi Tryout Alto.
                    </p>
                </div>
            </div>
        </div>

        <!-- KPI Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white border border-neutral-border/60 rounded-xl p-5 border-l-4 border-l-brand-primary shadow-sm">
                <span class="text-xs font-bold text-neutral-muted uppercase tracking-wider">Ujian Diikuti</span>
                <div class="text-3xl font-extrabold text-neutral-text mt-2">{{ $scores->count() }} <span class="text-sm font-normal text-neutral-muted">Paket</span></div>
                <p class="text-xs text-neutral-muted mt-1">Tersedia {{ $availableTryouts->count() }} paket aktif</p>
            </div>

            <div class="bg-white border border-neutral-border/60 rounded-xl p-5 border-l-4 border-l-emerald-500 shadow-sm">
                <span class="text-xs font-bold text-neutral-muted uppercase tracking-wider">Rata-Rata Kumulatif</span>
                <div class="text-3xl font-extrabold text-emerald-600 mt-2">{{ round($overallAverage, 2) }}</div>
                <p class="text-xs text-neutral-muted mt-1">Passing grade PTN kompetitif</p>
            </div>

            <div class="bg-white border border-neutral-border/60 rounded-xl p-5 border-l-4 border-l-amber-500 shadow-sm">
                <span class="text-xs font-bold text-neutral-muted uppercase tracking-wider">Skor Tertinggi</span>
                <div class="text-3xl font-extrabold text-amber-accent mt-2">{{ $highestScore }}</div>
                <p class="text-xs text-neutral-muted mt-1">Pencapaian terbaik</p>
            </div>
        </div>

        <!-- Available CBT Tryouts Section -->
        <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-neutral-border">
                <div>
                    <h2 class="text-base font-bold text-neutral-text flex items-center gap-2">
                        <i class="fa-solid fa-laptop-code text-brand-primary"></i>
                        <span>Paket Tryout CBT Aktif</span>
                    </h2>
                    <p class="text-xs text-neutral-muted mt-0.5">Pilih paket simulasi UTBK-SNBT untuk mengerjakan ujian secara langsung.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pt-2">
                @forelse ($availableTryouts as $available)
                    <div class="rounded-xl border border-neutral-border p-5 bg-neutral-subtle/40 hover:border-brand-primary/40 transition-all flex flex-col justify-between space-y-4 shadow-2xs">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-soft text-brand-primary border border-brand-primary/20">
                                    {{ $available->duration_minutes ?? 90 }} Menit
                                </span>
                                @if (in_array($available->id, $completedTryoutIds ?? []))
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Sudah Dikerjakan
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        Tersedia
                                    </span>
                                @endif
                            </div>
                            <h3 class="font-bold text-neutral-text text-sm leading-snug">{{ $available->title }}</h3>
                            <p class="text-[11px] text-neutral-muted mt-1">
                                Periode: {{ $available->date_start ? \Carbon\Carbon::parse($available->date_start)->format('d M') : 'Sekarang' }} s.d {{ $available->date_end ? \Carbon\Carbon::parse($available->date_end)->format('d M Y') : 'Selesai' }}
                            </p>
                        </div>

                        <div>
                            @if (in_array($available->id, $completedTryoutIds ?? []))
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('siswa.tryout.show', $available->id) }}" class="py-2 px-3 text-center bg-white hover:bg-neutral-subtle text-neutral-text text-xs font-bold rounded-xl border border-neutral-border shadow-2xs transition-all">
                                        Lihat Hasil
                                    </a>
                                    <a href="{{ route('siswa.tryout.take', $available->id) }}" class="flex-1 py-2 px-3 text-center bg-brand-primary hover:bg-brand-navy text-white text-xs font-bold rounded-xl shadow-xs transition-all flex items-center justify-center gap-1.5">
                                        <i class="fa-solid fa-play text-[11px]"></i>
                                        <span>Mulai Kerjakan Tryout</span>
                                    </a>
                                </div>
                            @else
                                <a href="{{ route('siswa.tryout.take', $available->id) }}" class="block w-full py-2.5 text-center bg-brand-primary hover:bg-brand-navy text-white text-xs font-bold rounded-xl shadow-xs transition-all flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-play text-[11px]"></i>
                                    <span>Mulai Kerjakan Tryout</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-8 text-center text-neutral-muted text-xs">
                        Belum ada paket tryout aktif saat ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Tryout Scores History -->
        <div class="bg-white border border-neutral-border/60 rounded-2xl p-6 shadow-sm">
            <h2 class="text-lg font-bold text-neutral-text mb-4">Riwayat Nilai Tryout SNBT</h2>

            @if ($scores->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-neutral-text">
                        <thead class="text-xs uppercase bg-[#F8FAFC] text-neutral-muted border-b border-neutral-border">
                            <tr>
                                <th class="px-4 py-3 font-bold">Paket Tryout</th>
                                <th class="px-4 py-3 text-center font-bold">TPS</th>
                                <th class="px-4 py-3 text-center font-bold">Literasi Indo</th>
                                <th class="px-4 py-3 text-center font-bold">Literasi Inggris</th>
                                <th class="px-4 py-3 text-center font-bold">Matematika</th>
                                <th class="px-4 py-3 text-center font-bold">Rata-Rata</th>
                                <th class="px-4 py-3 text-right font-bold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-border">
                            @foreach ($scores as $score)
                                <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                    <td class="px-4 py-3.5 font-bold text-brand-primary">
                                        {{ $score->tryout?->title }}
                                        <span class="block text-xs font-normal text-neutral-muted">{{ $score->created_at->format('d M Y') }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center font-mono font-semibold">{{ $score->subtest_tps }}</td>
                                    <td class="px-4 py-3.5 text-center font-mono font-semibold">{{ $score->subtest_indo }}</td>
                                    <td class="px-4 py-3.5 text-center font-mono font-semibold">{{ $score->subtest_inggris }}</td>
                                    <td class="px-4 py-3.5 text-center font-mono font-semibold">{{ $score->subtest_matematika }}</td>
                                    <td class="px-4 py-3.5 text-center font-mono font-extrabold text-brand-primary text-base">
                                        {{ $score->average_score }}
                                    </td>
                                    <td class="px-4 py-3.5 text-right space-x-2">
                                        <a href="{{ route('siswa.tryout.show', $score->tryout_id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-neutral-subtle hover:bg-neutral-200 text-xs font-bold text-neutral-text transition-colors">
                                            <i class="fa-solid fa-eye text-neutral-muted"></i> Detail
                                        </a>
                                        <a href="{{ route('reports.tryout.pdf', $score->id) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-brand-primary hover:bg-brand-navy text-xs font-bold text-white shadow-2xs transition-colors">
                                            <i class="fa-solid fa-print"></i> PDF
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12 text-neutral-muted">
                    <i class="fa-solid fa-file-signature text-3xl mb-2 text-neutral-muted/50 block"></i>
                    <p class="font-semibold text-neutral-text">Belum Ada Riwayat Ujian</p>
                    <p class="text-xs text-neutral-muted mt-1">Hasil simulasi tryout yang telah Anda ikuti akan muncul secara otomatis di sini.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
