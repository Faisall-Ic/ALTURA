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
