<x-layouts.app>
    <x-slot:title>ALTER LMS Video & Problems</x-slot:title>

    <div class="space-y-6">
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-text flex items-center gap-2">
                    <span><i class="fa-solid fa-play-circle text-brand-primary"></i> ALTER Video & Question Bank Manager</span>
                </h1>
                <p class="text-xs text-neutral-muted mt-1">
                    Kelola modul pembelajaran video interaktif YouTube, bab materi, dan bank soal latihan per subtes/mapel.
                </p>
            </div>
            <a href="{{ route('admin.alter.create') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-all">
                <i class="fa-solid fa-plus mr-1.5"></i>
                Tambah Modul ALTER
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white border border-neutral-border rounded-xl p-4 shadow-xs">
            <form action="{{ route('admin.alter.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text" name="q" value="{{ $search }}"
                        placeholder="Cari judul modul ALTER..."
                        class="w-full pl-9 pr-3 py-2 rounded-lg bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                    <i class="fa-solid fa-magnifying-glass text-neutral-muted absolute left-3 top-2.5 text-xs"></i>
                </div>

                <select name="subject_id" class="px-3 py-2 rounded-lg bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                    <option value="">-- Semua Mata Pelajaran --</option>
                    @foreach ($subjects as $s)
                        <option value="{{ $s->id }}" {{ (string)$subjectId === (string)$s->id ? 'selected' : '' }}>
                            {{ $s->name }} ({{ $s->code }})
                        </option>
                    @endforeach
                </select>

                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold rounded-lg transition-colors border border-neutral-border">
                        Filter
                    </button>
                    @if($subjectId || $search)
                        <a href="{{ route('admin.alter.index') }}" class="px-3 py-2 bg-neutral-subtle/60 hover:bg-neutral-subtle text-neutral-muted text-xs rounded-lg flex items-center border border-neutral-border">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Courses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse ($courses as $course)
                <div class="bg-white border border-neutral-border rounded-2xl p-5 shadow-xs flex flex-col justify-between hover:border-brand-primary/40 transition-all">
                    <div>
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-brand-soft text-brand-primary border border-brand-primary/20">
                                {{ $course->subject?->code ?? 'MAPEL' }} &bull; {{ $course->is_snbt_core ? 'SNBT Core' : 'TKA' }}
                            </span>
                            <span class="text-[11px] text-neutral-muted font-mono">
                                {{ $course->chapters_count }} Chapter
                            </span>
                        </div>
                        <h2 class="text-base font-bold text-neutral-text leading-snug">
                            {{ $course->title }}
                        </h2>
                        @if ($course->description)
                            <p class="text-xs text-neutral-muted mt-2 line-clamp-2">
                                {{ $course->description }}
                            </p>
                        @endif

                        <!-- Summary info -->
                        <div class="mt-4 pt-3 border-t border-neutral-border/60 flex items-center justify-between text-[11px] text-neutral-muted">
                            <span class="flex items-center gap-1">
                                <i class="fa-solid fa-video text-brand-primary text-[10px]"></i>
                                {{ $course->chapters->count() }} Video Pembelajaran
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="fa-solid fa-pen-nib text-brand-primary text-[10px]"></i>
                                {{ $course->chapters->flatMap->problems->count() }} Soal Latihan
                            </span>
                        </div>
                    </div>

                    <div class="mt-5 pt-3 border-t border-neutral-border flex items-center justify-between">
                        <a href="{{ route('admin.alter.show', $course) }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-brand-primary hover:bg-brand-navy text-white text-xs font-semibold shadow-xs transition-colors">
                            <i class="fa-solid fa-list-check text-[10px]"></i>
                            <span>Kelola Materi & Soal</span>
                        </a>

                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.alter.edit', $course) }}"
                                class="p-1.5 text-neutral-muted hover:text-brand-primary hover:bg-neutral-subtle rounded-lg transition-colors">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </a>
                            <form action="{{ route('admin.alter.destroy', $course) }}" method="POST" class="inline" onsubmit="return confirm('Hapus seluruh modul ALTER ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-neutral-muted hover:text-red-accent hover:bg-neutral-subtle rounded-lg transition-colors cursor-pointer">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white border border-neutral-border rounded-2xl p-10 text-center text-neutral-muted text-xs">
                    Belum ada modul ALTER LMS yang dibuat. Klik tombol di atas untuk membuat modul baru.
                </div>
            @endforelse
        </div>

        @if ($courses->hasPages())
            <div class="bg-white border border-neutral-border rounded-xl p-4 shadow-xs">
                {{ $courses->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
