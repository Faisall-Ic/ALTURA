<x-layouts.app>
    <x-slot:title>Detail Hasil: {{ $tryout->title }}</x-slot:title>

    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="rounded-2xl bg-white border border-neutral-border p-6 sm:p-8 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20 mb-3">
                        <span class="w-2 h-2 rounded-full bg-brand-primary"></span>
                        Kartu Skor Ujian Terverifikasi
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-neutral-text">{{ $tryout->title }}</h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Tanggal Ujian: {{ $score->created_at->translatedFormat('l, d F Y') }} • Peserta: {{ $student->user->name }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('siswa.tryout.index') }}" class="px-4 py-2.5 rounded-xl bg-white hover:bg-neutral-subtle text-neutral-text text-xs font-semibold border border-neutral-border transition-all shadow-sm">
                        <i class="fa-solid fa-arrow-left mr-1.5"></i> Semua Tryout
                    </a>
                    <a href="{{ route('reports.tryout.pdf', $score->id) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-brand-primary hover:bg-brand-navy text-white text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                        <i class="fa-solid fa-print mr-1.5"></i> Cetak / Unduh Sertifikat PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Big Score Hero Card -->
        <div class="bg-white border border-neutral-border rounded-2xl p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
            <div class="space-y-2 text-center md:text-left">
                <span class="text-xs font-bold text-brand-primary uppercase tracking-wider block">Skor Rata-Rata Kumulatif</span>
                <div class="text-5xl font-black text-neutral-text font-mono">{{ $score->average_score }} <span class="text-lg font-normal text-neutral-muted">/ 1000</span></div>
                <p class="text-sm text-neutral-muted">
                    Status: <span class="text-emerald-700 font-bold">Lolos Target Standar PTN Pilihan</span>
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4 w-full md:w-auto">
                <div class="bg-neutral-subtle/60 border border-neutral-border p-4 rounded-xl text-center min-w-[130px]">
                    <span class="text-[10px] text-neutral-muted uppercase block font-semibold">Subtes Terbaik</span>
                    <span class="text-xl font-bold text-emerald-700 mt-1 block font-mono">
                        {{ max($score->subtest_tps, $score->subtest_indo, $score->subtest_inggris, $score->subtest_matematika) }}
                    </span>
                </div>
                <div class="bg-neutral-subtle/60 border border-neutral-border p-4 rounded-xl text-center min-w-[130px]">
                    <span class="text-[10px] text-neutral-muted uppercase block font-semibold">Persentil Estimasi</span>
                    <span class="text-xl font-bold text-brand-primary mt-1 block font-mono">Top 12%</span>
                </div>
            </div>
        </div>

        <!-- Subtest Breakdown Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- 1. TPS -->
            <div class="bg-white border border-neutral-border rounded-2xl p-5 space-y-2 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-brand-primary uppercase tracking-wider">Subtes 1</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-brand-primary"></span>
                </div>
                <h3 class="text-base font-bold text-neutral-text">Tes Potensi Skolastik (TPS)</h3>
                <div class="text-3xl font-black text-brand-primary font-mono">{{ $score->subtest_tps }}</div>
                <div class="w-full bg-neutral-subtle rounded-full h-1.5 mt-2">
                    <div class="h-1.5 rounded-full bg-brand-primary" style="width: {{ min(100, $score->subtest_tps / 10) }}%"></div>
                </div>
                <p class="text-[11px] text-neutral-muted">Penalaran umum &amp; kuantitatif</p>
            </div>

            <!-- 2. Literasi Indonesia -->
            <div class="bg-white border border-neutral-border rounded-2xl p-5 space-y-2 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Subtes 2</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                </div>
                <h3 class="text-base font-bold text-neutral-text">Literasi B. Indonesia</h3>
                <div class="text-3xl font-black text-emerald-600 font-mono">{{ $score->subtest_indo }}</div>
                <div class="w-full bg-neutral-subtle rounded-full h-1.5 mt-2">
                    <div class="h-1.5 rounded-full bg-emerald-500" style="width: {{ min(100, $score->subtest_indo / 10) }}%"></div>
                </div>
                <p class="text-[11px] text-neutral-muted">Pemahaman bacaan &amp; wacana kritis</p>
            </div>

            <!-- 3. Literasi Inggris -->
            <div class="bg-white border border-neutral-border rounded-2xl p-5 space-y-2 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-amber-accent uppercase tracking-wider">Subtes 3</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-accent"></span>
                </div>
                <h3 class="text-base font-bold text-neutral-text">Literasi B. Inggris</h3>
                <div class="text-3xl font-black text-amber-accent font-mono">{{ $score->subtest_inggris }}</div>
                <div class="w-full bg-neutral-subtle rounded-full h-1.5 mt-2">
                    <div class="h-1.5 rounded-full bg-amber-accent" style="width: {{ min(100, $score->subtest_inggris / 10) }}%"></div>
                </div>
                <p class="text-[11px] text-neutral-muted">Contextual vocab &amp; reading</p>
            </div>

            <!-- 4. Penalaran Matematika -->
            <div class="bg-white border border-neutral-border rounded-2xl p-5 space-y-2 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-brand-navy uppercase tracking-wider">Subtes 4</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-brand-navy"></span>
                </div>
                <h3 class="text-base font-bold text-neutral-text">Penalaran Matematika</h3>
                <div class="text-3xl font-black text-brand-navy font-mono">{{ $score->subtest_matematika }}</div>
                <div class="w-full bg-neutral-subtle rounded-full h-1.5 mt-2">
                    <div class="h-1.5 rounded-full bg-brand-navy" style="width: {{ min(100, $score->subtest_matematika / 10) }}%"></div>
                </div>
                <p class="text-[11px] text-neutral-muted">Aplikasi konsep matematika terapan</p>
            </div>
        </div>
    </div>
</x-layouts.app>
