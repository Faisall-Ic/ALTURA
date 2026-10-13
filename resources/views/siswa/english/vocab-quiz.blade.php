<x-layouts.app>
    <x-slot:title>English Vocab Mastery CBT (400 Words)</x-slot:title>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-neutral-border">
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-neutral-text flex items-center gap-2">
                    <i class="fa-solid fa-book-open text-brand-primary"></i>
                    <span>English Vocab Mastery (Bank 400 Kata)</span>
                </h1>
                <p class="text-xs text-neutral-muted mt-1">
                    Evaluasi CBT kosakata bahasa Inggris akademik dalam 8 modul modular. Ambang batas kelulusan minimal 75%.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('siswa.english.tasks.index') }}"
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-neutral-text bg-white border border-neutral-border hover:bg-neutral-subtle shadow-2xs transition-colors flex items-center gap-1.5">
                    <span>Naskah Storytelling &amp; Wacana</span>
                    <i class="fa-solid fa-arrow-right text-xs text-neutral-muted"></i>
                </a>
            </div>
        </div>

        <!-- Completion Stats Overview -->
        @php
            $passedCount = collect($modules)->where('passed', true)->count();
            $percentagePassed = round(($passedCount / 8) * 100);
        @endphp
        <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="space-y-1 text-center sm:text-left">
                <span class="text-[11px] font-bold uppercase tracking-wider text-brand-primary">Progres Kelulusan Modul</span>
                <div class="text-2xl sm:text-3xl font-extrabold text-neutral-text">
                    {{ $passedCount }} / 8 Modul Selesai ({{ $percentagePassed }}%)
                </div>
                <p class="text-xs text-neutral-muted">
                    Selesaikan seluruh 8 modul untuk memenuhi prasyarat ujian kelulusan lisan (Oral Examination).
                </p>
            </div>

            <!-- Visual Bar -->
            <div class="w-full sm:w-64 bg-neutral-subtle rounded-full h-3.5 border border-neutral-border overflow-hidden">
                <div class="bg-brand-primary h-full rounded-full transition-all duration-500"
                    style="width: {{ $percentagePassed }}%;"></div>
            </div>
        </div>

        <!-- 8 Modules Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($modules as $m)
                <div class="bg-white border border-neutral-border rounded-2xl p-5 flex flex-col justify-between space-y-4 hover:border-brand-primary transition-all shadow-sm">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="w-8 h-8 rounded-xl bg-brand-soft border border-blue-200 text-brand-primary flex items-center justify-center font-extrabold text-xs font-mono">
                                #{{ $m['number'] }}
                            </span>
                            @if ($m['passed'])
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> LULUS ({{ $m['best_score'] }}%)
                                </span>
                            @elseif ($m['attempts'] > 0)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> PERLU ULANG ({{ $m['best_score'] }}%)
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-neutral-subtle text-neutral-muted border border-neutral-border">
                                    Belum Diambil
                                </span>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-sm font-bold text-neutral-text">Modul Kosakata {{ $m['number'] }}</h3>
                            <p class="text-xs text-neutral-muted mt-1">{{ $m['word_count'] ?? 50 }} Kosakata Akademik</p>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-neutral-border flex items-center justify-between">
                        <span class="text-[11px] text-neutral-muted font-mono">{{ $m['attempts'] }}x percobaan</span>
                        <a href="{{ route('siswa.english.vocab.take', $m['number']) }}"
                            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors {{ $m['passed'] ? 'bg-neutral-subtle hover:bg-neutral-200 text-neutral-text' : 'bg-brand-primary hover:bg-brand-navy text-white shadow-2xs' }}">
                            {{ $m['passed'] ? 'Ulangi Kuis' : 'Mulai CBT' }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.app>
