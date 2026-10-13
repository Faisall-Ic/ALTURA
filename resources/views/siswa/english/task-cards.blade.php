<x-layouts.app>
    <x-slot:title>Naskah Storytelling & Wacana Akademik</x-slot:title>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-text flex items-center gap-2">
                    <i class="fa-solid fa-microphone-lines text-rose-500"></i>
                    <span>Naskah Storytelling & Wacana Akademik</span>
                </h1>
                <p class="text-xs text-neutral-muted mt-1">
                    Pelajari dan latih pelafalan naskah setoran lisan. Ujian dinilai langsung secara tatap muka oleh Guru Mentor Anda.
                </p>
            </div>
            <a href="{{ route('siswa.english.speaking.booking') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-sm transition-all">
                <i class="fa-solid fa-calendar-days mr-1.5"></i>
                <span>Pesan Jadwal Ujian Lisan</span>
                <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
            </a>
        </div>

        <!-- Section 1: Storytelling Scripts -->
        <div class="space-y-4">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <h2 class="text-sm font-bold uppercase tracking-wider text-neutral-text">Bagian 1: Cerita Rakyat (Storytelling)</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($storytellingTasks as $task)
                    <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-sm space-y-4 flex flex-col justify-between hover:shadow-md transition-shadow">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wider">
                                    Storytelling
                                </span>
                                <span class="text-[11px] font-mono text-neutral-muted">
                                    Batas Lulus: {{ $task->minimum_pass_grade }}
                                </span>
                            </div>

                            <h3 class="text-base font-bold text-neutral-text">{{ $task->title }}</h3>

                            <div class="bg-neutral-subtle/70 p-4 rounded-xl border border-neutral-border text-xs text-neutral-text leading-relaxed font-serif max-h-64 overflow-y-auto whitespace-pre-line">
                                {{ $task->script_text }}
                            </div>
                        </div>

                        <div class="pt-3 border-t border-neutral-border">
                            <a href="{{ route('siswa.english.speaking.booking') }}"
                                class="w-full py-2 bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold rounded-xl block text-center transition-colors">
                                Pilih Naskah Ini untuk Ujian
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Section 2: Wacana Akademik Scripts -->
        <div class="space-y-4 pt-4">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-brand-active"></span>
                <h2 class="text-sm font-bold uppercase tracking-wider text-neutral-text">Bagian 2: Wacana Diskursus Akademik</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($wacanaTasks as $task)
                    <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-sm space-y-4 flex flex-col justify-between hover:shadow-md transition-shadow">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-soft text-brand-primary border border-brand-primary/20 uppercase tracking-wider">
                                    Wacana Akademik
                                </span>
                                <span class="text-[11px] font-mono text-neutral-muted">
                                    Batas Lulus: {{ $task->minimum_pass_grade }}
                                </span>
                            </div>

                            <h3 class="text-base font-bold text-neutral-text">{{ $task->title }}</h3>

                            <div class="bg-neutral-subtle/70 p-4 rounded-xl border border-neutral-border text-xs text-neutral-text leading-relaxed font-serif max-h-64 overflow-y-auto whitespace-pre-line">
                                {{ $task->script_text }}
                            </div>
                        </div>

                        <div class="pt-3 border-t border-neutral-border">
                            <a href="{{ route('siswa.english.speaking.booking') }}"
                                class="w-full py-2 bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold rounded-xl block text-center transition-colors">
                                Pilih Naskah Ini untuk Ujian
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app>
