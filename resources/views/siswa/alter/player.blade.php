<x-layouts.app>
    <x-slot:title>Ruang Belajar ALTER — {{ $currentChapter?->title ?? 'Pembelajaran Mandiri' }}</x-slot:title>

    <div class="space-y-6">
        <!-- Top Nav & Breadcrumb -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-neutral-border">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-brand-primary">
                    ALTER LMS • {{ $currentCourse?->title ?? 'Modul Belajar' }}
                </span>
                <h1 class="text-xl sm:text-2xl font-extrabold text-neutral-text mt-0.5">
                    {{ $currentChapter?->title ?? 'Pilih Bab Pembelajaran' }}
                </h1>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('siswa.alter.kurikulum') }}"
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-neutral-text bg-white border border-neutral-border hover:bg-neutral-subtle shadow-2xs transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left text-xs text-neutral-muted"></i>
                    <span>Silabus Kurikulum</span>
                </a>
            </div>
        </div>

        @php
            $chapters = $chapters ?? ($currentCourse?->chapters ?? collect());
        @endphp

        @if ($currentChapter)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Left 8 Cols: One-Page Learning Player (Video + Problem Solver) -->
                <div class="lg:col-span-8 space-y-6">
                    <!-- Video Player Container (YouTube Embed / Plyr Wrapper) -->
                    <div class="bg-white border border-neutral-border rounded-2xl overflow-hidden shadow-sm">
                        <div class="aspect-video w-full bg-brand-dark">
                            <iframe 
                                class="w-full h-full"
                                src="https://www.youtube-nocookie.com/embed/{{ $currentChapter->youtube_video_id }}?modestbranding=1&rel=0&controls=1" 
                                title="{{ $currentChapter->title }}"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                            </iframe>
                        </div>
                        <div class="p-4 bg-surface border-t border-neutral-border flex items-center justify-between text-xs text-neutral-muted">
                            <span class="font-bold text-neutral-text">Bab {{ $currentChapter->chapter_number }}: {{ $currentChapter->title }}</span>
                            <span class="px-2.5 py-0.5 rounded-full bg-brand-soft text-brand-primary border border-blue-200 text-[10px] font-semibold">
                                Mode Belajar Mandiri
                            </span>
                        </div>
                    </div>

                    <!-- Clean Essay Problem Viewer (Solve in Physical Notebook) -->
                    <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-sm space-y-5">
                        <div class="flex items-center justify-between border-b border-neutral-border pb-4">
                            <div>
                                <h2 class="text-base font-bold text-neutral-text flex items-center gap-2">
                                    <i class="fa-solid fa-file-pen text-brand-primary"></i>
                                    <span>Lembar Latihan Soal Uraian Mandiri</span>
                                </h2>
                                <p class="text-xs text-neutral-muted mt-0.5">
                                    Kerjakan langsung pada buku catatan Anda sesuai instruksi video tanpa formulir rumit.
                                </p>
                            </div>
                            <span class="text-xs font-mono font-bold text-brand-primary bg-brand-soft px-3 py-1 rounded-full border border-blue-200">
                                {{ $currentChapter->problems->count() }} Butir Soal
                            </span>
                        </div>

                        <div class="space-y-4">
                            @forelse ($currentChapter->problems as $problem)
                                <div class="bg-neutral-subtle/50 border border-neutral-border rounded-xl p-5 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-6 h-6 rounded-lg bg-brand-primary flex items-center justify-center text-xs font-extrabold text-white">
                                                {{ $problem->problem_number }}
                                            </span>
                                            <span class="text-xs font-bold text-neutral-text">
                                                Soal No. {{ $problem->problem_number }} — {{ $problem->type_name }}
                                            </span>
                                        </div>
                                        <span class="text-[10px] font-semibold text-neutral-muted bg-white px-2.5 py-0.5 rounded-md border border-neutral-border">
                                            Buku Catatan
                                        </span>
                                    </div>

                                    <!-- Problem Crop Image Representation -->
                                    <div class="p-4 bg-white rounded-xl border border-neutral-border text-xs text-neutral-text leading-relaxed space-y-2">
                                        <div class="flex items-center gap-1.5 text-brand-primary text-[11px] font-bold">
                                            <i class="fa-solid fa-image text-xs"></i>
                                            <span>Klip Dokumen Soal #{{ $problem->problem_number }}</span>
                                        </div>
                                        <div class="p-3.5 rounded-lg bg-neutral-subtle border border-neutral-border text-neutral-text">
                                            <p class="text-xs font-medium">
                                                Berdasarkan konsep pada video di atas, tentukan penyelesaian matematis dan buktikan langkah kalkulasinya secara sistematis. Catat hasil akhir pada buku latihan Alto Anda.
                                            </p>
                                            <div class="mt-2 text-[10px] text-neutral-muted font-mono">
                                                Ref: {{ $problem->problem_image_path }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-neutral-muted text-xs">
                                    Belum ada butir soal uraian yang ditautkan pada bab ini.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right 4 Cols: Chapter Playlist -->
                <div class="lg:col-span-4 space-y-4">
                    <div class="bg-white border border-neutral-border rounded-2xl p-5 shadow-sm">
                        <h3 class="text-sm font-bold text-neutral-text mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-list-check text-brand-primary"></i>
                            <span>Daftar Bab Pembelajaran</span>
                        </h3>

                        <div class="space-y-1.5">
                            @foreach ($chapters as $chap)
                                <a href="{{ route('siswa.alter.player', $chap->id) }}"
                                    class="block p-3 rounded-xl border text-xs transition-all {{ $chap->id === $currentChapter->id ? 'bg-brand-soft border-brand-primary text-brand-primary font-bold shadow-2xs' : 'bg-surface border-neutral-border text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text' }}">
                                    <div class="flex items-center justify-between">
                                        <span class="font-semibold truncate">
                                            {{ $chap->chapter_number }}. {{ $chap->title }}
                                        </span>
                                        @if ($chap->id === $currentChapter->id)
                                            <span class="w-2 h-2 rounded-full bg-brand-primary shrink-0 ml-2"></span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-neutral-muted mt-1 flex items-center gap-3">
                                        <span><i class="fa-solid fa-video mr-1"></i> Video</span>
                                        <span>•</span>
                                        <span><i class="fa-solid fa-file-pen mr-1"></i> {{ $chap->problems->count() }} Soal</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white border border-neutral-border rounded-2xl p-12 text-center shadow-sm">
                <i class="fa-solid fa-book-open text-4xl text-neutral-muted/60 mb-3 block"></i>
                <h3 class="text-base font-bold text-neutral-text">Tidak Ada Bab Yang Dipilih</h3>
                <p class="text-xs text-neutral-muted mt-1 max-w-md mx-auto">Silakan kembali ke silabus kurikulum untuk memilih bab pembelajaran mandiri yang aktif.</p>
                <div class="mt-4">
                    <a href="{{ route('siswa.alter.kurikulum') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy transition-colors">
                        Buka Silabus Kurikulum &rarr;
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
