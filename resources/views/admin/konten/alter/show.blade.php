<x-layouts.app>
    <x-slot:title>Detail ALTER: {{ $course->title }}</x-slot:title>

    <div class="space-y-6">
        <!-- Header -->
        <div>
            <a href="{{ route('admin.alter.index') }}" class="inline-flex items-center gap-1.5 text-xs text-brand-primary hover:text-brand-navy font-semibold mb-2 transition-colors">
                <i class="fa-solid fa-arrow-left text-[11px]"></i> Kembali ke Daftar Modul ALTER
            </a>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-neutral-text flex items-center gap-2">
                        <span>{{ $course->title }}</span>
                    </h1>
                    <p class="text-xs text-neutral-muted mt-1">
                        Mata Pelajaran: <span class="font-semibold text-neutral-text">{{ $course->subject?->name }} ({{ $course->subject?->code }})</span> &bull; 
                        Status: <span class="font-semibold text-neutral-text">{{ $course->is_snbt_core ? 'SNBT Core (Wajib)' : 'TKA Peminatan' }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.alter.edit', $course) }}"
                        class="px-3.5 py-2 rounded-xl text-xs font-semibold text-neutral-text bg-white hover:bg-neutral-subtle border border-neutral-border shadow-xs transition-colors">
                        <i class="fa-solid fa-pen-to-square mr-1"></i> Edit Judul
                    </a>
                </div>
            </div>
        </div>

        <!-- Add Chapter Form Card -->
        <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs">
            <h2 class="text-sm font-bold text-neutral-text flex items-center gap-2 mb-4">
                <i class="fa-solid fa-folder-plus text-brand-primary"></i> Tambah Bab / Chapter Video Baru
            </h2>
            <form action="{{ route('admin.alter.chapter.store', $course) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                @csrf
                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-semibold uppercase text-neutral-muted mb-1.5">No. Bab</label>
                    <input type="number" name="chapter_number" value="{{ old('chapter_number', $course->chapters->count() + 1) }}" required min="1"
                        class="w-full px-3 py-2 rounded-xl bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary font-mono text-center">
                </div>
                <div class="sm:col-span-5">
                    <label class="block text-[11px] font-semibold uppercase text-neutral-muted mb-1.5">Judul Materi / Bab</label>
                    <input type="text" name="title" placeholder="Contoh: Operasi Aljabar & Teori Bilangan" required
                        class="w-full px-3 py-2 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                </div>
                <div class="sm:col-span-3">
                    <label class="block text-[11px] font-semibold uppercase text-neutral-muted mb-1.5">YouTube Video ID</label>
                    <input type="text" name="youtube_video_id" placeholder="Contoh: dQw4w9WgXcQ" required
                        class="w-full px-3 py-2 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs font-mono focus:outline-none focus:border-brand-primary">
                </div>
                <div class="sm:col-span-2">
                    <button type="submit" class="w-full px-4 py-2 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-colors">
                        <i class="fa-solid fa-plus mr-1"></i> Simpan Bab
                    </button>
                </div>
            </form>
        </div>

        <!-- Chapters & Problems List -->
        <div class="space-y-4">
            <h2 class="text-base font-bold text-neutral-text flex items-center gap-2">
                <i class="fa-solid fa-layer-group text-brand-primary"></i> Daftar Bab & Bank Soal ({{ $course->chapters->count() }} Bab)
            </h2>

            @forelse ($course->chapters as $chapter)
                <div class="bg-white border border-neutral-border rounded-2xl p-5 shadow-xs space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-neutral-border/60 pb-3">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-brand-soft text-brand-primary flex items-center justify-center font-bold text-xs border border-brand-primary/20">
                                {{ $chapter->chapter_number }}
                            </span>
                            <div>
                                <h3 class="text-sm font-bold text-neutral-text">{{ $chapter->title }}</h3>
                                <div class="flex items-center gap-3 text-[11px] text-neutral-muted font-mono mt-0.5">
                                    <span><i class="fa-brands fa-youtube text-red-500 mr-1"></i>ID: {{ $chapter->youtube_video_id }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $chapter->problems->count() }} Soal Latihan</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="https://www.youtube.com/watch?v={{ $chapter->youtube_video_id }}" target="_blank"
                                class="px-2.5 py-1.5 rounded-lg text-xs font-medium text-neutral-muted hover:text-neutral-text hover:bg-neutral-subtle border border-neutral-border transition-colors">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] mr-1"></i> Buka Video
                            </a>
                            <form action="{{ route('admin.alter.chapter.destroy', $chapter) }}" method="POST" class="inline" onsubmit="return confirm('Hapus Bab ini beserta semua soal latihannya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-neutral-muted hover:text-red-accent hover:bg-neutral-subtle rounded-lg transition-colors cursor-pointer">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Problems in this Chapter -->
                    <div class="space-y-3">
                        <div class="text-xs font-semibold text-neutral-muted uppercase tracking-wider">
                            Daftar Soal Latihan (Problems)
                        </div>

                        @if ($chapter->problems->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach ($chapter->problems as $prob)
                                    <div class="p-3 rounded-xl border border-neutral-border bg-surface flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-6 h-6 rounded-lg bg-neutral-subtle text-neutral-text flex items-center justify-center font-mono font-bold text-[11px]">
                                                #{{ $prob->problem_number }}
                                            </span>
                                            <div>
                                                <div class="font-semibold text-neutral-text">{{ $prob->type_name }}</div>
                                                @if ($prob->problem_image_path)
                                                    <span class="text-[10px] text-brand-primary truncate max-w-[150px] block">
                                                        <i class="fa-solid fa-image text-[9px] mr-0.5"></i>{{ basename($prob->problem_image_path) }}
                                                    </span>
                                                @else
                                                    <span class="text-[10px] text-neutral-muted">Teks / Non-Gambar</span>
                                                @endif
                                            </div>
                                        </div>

                                        <form action="{{ route('admin.alter.problem.destroy', $prob) }}" method="POST" class="inline" onsubmit="return confirm('Hapus soal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-neutral-muted hover:text-red-accent rounded transition-colors cursor-pointer">
                                                <i class="fa-solid fa-xmark text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-[11px] text-neutral-muted italic">Belum ada soal latihan untuk bab ini.</p>
                        @endif

                        <!-- Inline Add Problem Form -->
                        <form action="{{ route('admin.alter.problem.store', $chapter) }}" method="POST" class="pt-2 flex flex-wrap sm:flex-nowrap gap-2 items-center">
                            @csrf
                            <input type="number" name="problem_number" value="{{ $chapter->problems->count() + 1 }}" required min="1"
                                placeholder="No Soal"
                                class="w-20 px-3 py-1.5 rounded-lg bg-surface border border-neutral-border text-neutral-text text-xs text-center font-mono focus:outline-none focus:border-brand-primary">
                            <input type="text" name="type_name" placeholder="Tipe Soal (e.g. Essay 1 / Isian Singkat)" required
                                class="flex-1 px-3 py-1.5 rounded-lg bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                            <input type="text" name="problem_image_path" placeholder="Path Gambar (opsional, e.g. /images/alter/prob1.png)"
                                class="flex-1 px-3 py-1.5 rounded-lg bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs font-mono focus:outline-none focus:border-brand-primary">
                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text border border-neutral-border text-xs font-semibold transition-colors">
                                <i class="fa-solid fa-plus text-[10px] mr-1"></i> Tambah Soal
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white border border-neutral-border rounded-2xl p-8 text-center text-neutral-muted text-xs">
                    Belum ada Bab/Chapter yang ditambahkan ke kursus ini. Gunakan form di atas untuk menambahkan Bab 1.
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.app>
