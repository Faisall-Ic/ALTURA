<x-layouts.app>
    <x-slot:title>Silabus Kurikulum ALTER</x-slot:title>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-text flex items-center gap-2">
                    <i class="fa-solid fa-book text-brand-primary"></i>
                    <span>Silabus & Kurikulum ALTER LMS</span>
                </h1>
                <p class="text-xs text-neutral-muted mt-1">
                    Struktur materi pembelajaran mandiri terpadu berbasis video dan latihan soal uraian analitis.
                </p>
            </div>
            <a href="{{ route('siswa.alter.player') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-sm transition-all">
                <span>Buka Player Belajar</span>
                <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
            </a>
        </div>

        <!-- Course Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse ($courses as $course)
                <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-sm space-y-4 flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full {{ $course->is_snbt_core ? 'bg-amber-accent-soft text-amber-accent-text border border-amber-accent/20' : 'bg-brand-soft text-brand-primary border border-brand-primary/20' }}">
                                {!! $course->is_snbt_core ? '<i class="fa-solid fa-fire mr-1 text-amber-accent"></i> SNBT Core Track' : '<i class="fa-solid fa-bookmark mr-1 text-brand-primary"></i> Materi Jurusan' !!}
                            </span>
                            <span class="text-xs font-mono text-neutral-muted">
                                {{ $course->chapters->count() }} Bab
                            </span>
                        </div>

                        <h2 class="text-lg font-bold text-neutral-text">{{ $course->title }}</h2>

                        <!-- Chapters List -->
                        <div class="space-y-2 pt-2">
                            @foreach ($course->chapters as $ch)
                                <a href="{{ route('siswa.alter.player', $ch->id) }}"
                                    class="p-3 rounded-xl bg-neutral-subtle/70 border border-neutral-border hover:border-brand-primary/50 hover:bg-brand-soft/40 flex items-center justify-between text-xs text-neutral-text transition-all group">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-6 h-6 rounded-lg bg-white border border-neutral-border text-neutral-muted flex items-center justify-center text-[10px] font-bold font-mono group-hover:bg-brand-primary group-hover:text-white transition-colors">
                                            {{ $ch->chapter_number }}
                                        </span>
                                        <span class="font-medium group-hover:text-brand-primary">{{ $ch->title }}</span>
                                    </div>
                                    <span class="text-[10px] font-mono text-neutral-muted group-hover:text-brand-primary flex items-center gap-1">
                                        {{ $ch->problems->count() }} Soal
                                        <i class="fa-solid fa-chevron-right text-[9px]"></i>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4 border-t border-neutral-border">
                        <a href="{{ route('siswa.alter.player', $course->chapters->first()?->id) }}"
                            class="w-full py-2.5 bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold rounded-xl block text-center transition-colors">
                            Mulai Belajar Kursus Ini
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-2 bg-white border border-neutral-border rounded-2xl p-12 text-center text-neutral-muted text-xs shadow-sm">
                    Belum ada kurikulum yang terdaftar untuk program belajar ini.
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.app>
