<x-layouts.app>
    <x-slot:title>Raport & Hasil Belajar Ananda — Portal Orang Tua</x-slot:title>

    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-neutral-border shadow-xs p-6 sm:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20 mb-3">
                        <span class="w-2 h-2 rounded-full bg-brand-primary animate-pulse"></span>
                        Transparansi Capaian Akademik
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-neutral-text">Hasil Belajar: {{ $child->user?->name }} <i class="fa-solid fa-chart-pie ml-1 text-brand-primary"></i></h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Rekapitulasi Nilai Tryout SNBT, Setoran Modul Bahasa Inggris (Vocab Mastery), dan Ujian Praktik Speaking
                    </p>
                </div>
                <div>
                    <a href="{{ route('ortu.dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold border border-neutral-border transition-all">
                        <i class="fa-solid fa-arrow-left text-[11px]"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- 1. Tryout SNBT Results Section -->
        <div class="bg-white border border-neutral-border rounded-2xl p-6 space-y-4 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-neutral-text flex items-center gap-2">
                        <span><i class="fa-solid fa-bullseye text-brand-primary"></i> Hasil Ujian Tryout SNBT Alto</span>
                    </h2>
                    <p class="text-xs text-neutral-muted">Rincian skor per subtes dan rerata total capaian</p>
                </div>
            </div>

            @if ($tryoutScores->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-neutral-text">
                        <thead class="text-xs uppercase bg-neutral-subtle/70 text-neutral-muted border-b border-neutral-border font-semibold">
                            <tr>
                                <th class="px-4 py-3">Paket Tryout</th>
                                <th class="px-4 py-3 text-center">TPS & Penalaran</th>
                                <th class="px-4 py-3 text-center">Literasi B. Indonesia</th>
                                <th class="px-4 py-3 text-center">Literasi B. Inggris</th>
                                <th class="px-4 py-3 text-center">Penalaran Matematika</th>
                                <th class="px-4 py-3 text-center">Rata-Rata Total</th>
                                <th class="px-4 py-3 text-right">Tanggal Ujian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-border">
                            @foreach ($tryoutScores as $score)
                                <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                    <td class="px-4 py-3.5 font-bold text-neutral-text">
                                        {{ $score->tryout?->title ?? 'Tryout SNBT' }}
                                    </td>
                                    <td class="px-4 py-3.5 text-center font-mono font-semibold text-brand-primary">
                                        {{ $score->subtest_tps }}
                                    </td>
                                    <td class="px-4 py-3.5 text-center font-mono font-semibold text-emerald-600">
                                        {{ $score->subtest_indo }}
                                    </td>
                                    <td class="px-4 py-3.5 text-center font-mono font-semibold text-amber-accent-text">
                                        {{ $score->subtest_inggris }}
                                    </td>
                                    <td class="px-4 py-3.5 text-center font-mono font-semibold text-purple-700">
                                        {{ $score->subtest_matematika }}
                                    </td>
                                    <td class="px-4 py-3.5 text-center font-mono font-extrabold text-base text-neutral-text">
                                        <span class="px-2.5 py-1 rounded-lg bg-brand-soft text-brand-primary border border-brand-primary/20">
                                            {{ $score->average_score }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-right text-xs text-neutral-muted">
                                        {{ $score->created_at->translatedFormat('d M Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-8 text-center text-neutral-muted">
                    <p class="text-xs">Belum ada nilai tryout tercatat.</p>
                </div>
            @endif
        </div>

        <!-- 2. English Vocab Mastery (8 Modules) -->
        <div class="bg-white border border-neutral-border rounded-2xl p-6 space-y-4 shadow-xs">
            <div>
                <h2 class="text-lg font-bold text-neutral-text flex items-center gap-2">
                    <span><i class="fa-solid fa-book-open text-brand-primary"></i> Progres English Vocab Mastery (400 Kosakata)</span>
                </h2>
                <p class="text-xs text-neutral-muted">Target kelulusan modul adalah skor minimum 75 pts.</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @for ($m = 1; $m <= 8; $m++)
                    @php
                        $res = $vocabResults[$m] ?? null;
                    @endphp
                    <div class="p-3.5 rounded-xl border {{ $res && $res->is_passed ? 'bg-emerald-50/70 border-emerald-200' : ($res ? 'bg-amber-accent-soft/50 border-amber-accent/30' : 'bg-neutral-subtle/40 border-neutral-border') }} text-xs space-y-1.5">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-neutral-text">Modul {{ $m }}</span>
                            @if ($res && $res->is_passed)
                                <span class="text-emerald-700 font-bold"><i class="fa-solid fa-check text-[11px]"></i> Lulus</span>
                            @elseif ($res)
                                <span class="text-amber-accent-text font-bold">Remedial</span>
                            @else
                                <span class="text-neutral-muted">Belum Kuis</span>
                            @endif
                        </div>
                        <div class="flex items-baseline justify-between text-neutral-muted pt-1">
                            <span>Skor Kuis:</span>
                            <span class="font-mono font-bold text-sm text-neutral-text">{{ $res ? $res->score : '0' }} <span class="text-[10px] font-normal text-neutral-muted">pts</span></span>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- 3. Speaking Oral Rubric Evaluations -->
        <div class="bg-white border border-neutral-border rounded-2xl p-6 space-y-4 shadow-xs">
            <div>
                <h2 class="text-lg font-bold text-neutral-text flex items-center gap-2">
                    <span><i class="fa-solid fa-microphone-lines text-brand-primary"></i> Penilaian Ujian Praktik Speaking (Storytelling & Wacana)</span>
                </h2>
                <p class="text-xs text-neutral-muted">Hasil evaluasi lisan tatap muka langsung oleh Guru Mentor</p>
            </div>

            @if ($speakingBookings->isNotEmpty())
                <div class="space-y-3">
                    @foreach ($speakingBookings as $b)
                        <div class="p-4 rounded-xl bg-neutral-subtle/40 border border-neutral-border space-y-3">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div>
                                    <div class="text-sm font-bold text-neutral-text">{{ $b->speakingTask?->title ?? 'Tugas Speaking' }}</div>
                                    <div class="text-xs text-neutral-muted">Mentor Penilai: <span class="text-brand-primary font-medium">{{ $b->mentor?->user?->name }}</span></div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if ($b->status === 'completed')
                                        <div class="text-right">
                                            <span class="text-xs text-neutral-muted block">Total Nilai</span>
                                            <span class="text-xl font-extrabold text-emerald-600">{{ $b->grade }} <span class="text-xs text-neutral-muted">/ 100</span></span>
                                        </div>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-accent-soft text-amber-accent-text border border-amber-accent/30">
                                            <i class="fa-solid fa-hourglass-half mr-1 text-[11px] text-amber-accent"></i> Menunggu Sesi Ujian
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if ($b->status === 'completed')
                                <!-- Rubric Subscores -->
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2 border-t border-neutral-border text-xs">
                                    <div class="bg-surface p-2 rounded-lg border border-neutral-border">Pronunciation: <span class="font-bold text-neutral-text">{{ $b->rubric_pronunciation }} / 25</span></div>
                                    <div class="bg-surface p-2 rounded-lg border border-neutral-border">Fluency: <span class="font-bold text-neutral-text">{{ $b->rubric_fluency }} / 25</span></div>
                                    <div class="bg-surface p-2 rounded-lg border border-neutral-border">Vocabulary: <span class="font-bold text-neutral-text">{{ $b->rubric_vocabulary }} / 25</span></div>
                                    <div class="bg-surface p-2 rounded-lg border border-neutral-border">Comprehension: <span class="font-bold text-neutral-text">{{ $b->rubric_comprehension }} / 25</span></div>
                                </div>

                                @if ($b->rubric_notes)
                                    <div class="p-2.5 rounded-lg bg-surface border border-neutral-border text-xs text-neutral-text">
                                        <span class="font-semibold text-neutral-muted block mb-0.5">Catatan Evaluasi Guru:</span>
                                        "{{ $b->rubric_notes }}"
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-8 text-center text-neutral-muted">
                    <p class="text-xs">Belum ada riwayat ujian speaking tercatat.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
