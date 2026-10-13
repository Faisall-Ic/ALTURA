<x-layouts.app>
    <x-slot:title>Manajemen English Mastery (Vocab, Storytelling, Wacana)</x-slot:title>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-text flex items-center gap-2">
                    <span><i class="fa-solid fa-language text-brand-primary"></i> English Mastery Manager</span>
                </h1>
                <p class="text-xs text-neutral-muted mt-1">
                    Kontrol database kosakata (Vocabulary), naskah Storytelling, dan teks Wacana Bahasa Inggris secara dinamis.
                </p>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex border-b border-neutral-border gap-2">
            <a href="{{ route('admin.english.index', ['tab' => 'vocab', 'module' => $moduleNumber]) }}"
                class="px-5 py-3 text-xs font-bold border-b-2 transition-all flex items-center gap-2 {{ $activeTab === 'vocab' ? 'border-brand-primary text-brand-primary bg-brand-soft/40 rounded-t-xl' : 'border-transparent text-neutral-muted hover:text-neutral-text' }}">
                <i class="fa-solid fa-spell-check text-xs"></i>
                <span>Bank Kosakata (Vocab Modules)</span>
            </a>
            <a href="{{ route('admin.english.index', ['tab' => 'storytelling']) }}"
                class="px-5 py-3 text-xs font-bold border-b-2 transition-all flex items-center gap-2 {{ $activeTab === 'storytelling' ? 'border-brand-primary text-brand-primary bg-brand-soft/40 rounded-t-xl' : 'border-transparent text-neutral-muted hover:text-neutral-text' }}">
                <i class="fa-solid fa-microphone-lines text-xs"></i>
                <span>Naskah Storytelling ({{ $storytellings->count() }})</span>
            </a>
            <a href="{{ route('admin.english.index', ['tab' => 'wacana']) }}"
                class="px-5 py-3 text-xs font-bold border-b-2 transition-all flex items-center gap-2 {{ $activeTab === 'wacana' ? 'border-brand-primary text-brand-primary bg-brand-soft/40 rounded-t-xl' : 'border-transparent text-neutral-muted hover:text-neutral-text' }}">
                <i class="fa-solid fa-file-lines text-xs"></i>
                <span>Teks Wacana & Reading ({{ $wacanas->count() }})</span>
            </a>
        </div>

        @if ($activeTab === 'vocab')
            <!-- TAB 1: VOCABULARY -->
            <div class="space-y-6">
                <!-- Module Selector Pills -->
                <div class="bg-white border border-neutral-border rounded-2xl p-4 shadow-xs">
                    <div class="text-xs font-semibold text-neutral-muted uppercase mb-3">Pilih Modul Kosakata:</div>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($modules as $mod)
                            <a href="{{ route('admin.english.index', ['tab' => 'vocab', 'module' => $mod->module_number]) }}"
                                class="px-4 py-2 rounded-xl text-xs font-bold transition-all border {{ (int)$moduleNumber === (int)$mod->module_number ? 'bg-brand-primary text-white border-brand-primary shadow-xs' : 'bg-surface text-neutral-text border-neutral-border hover:border-brand-primary' }}">
                                Module {{ $mod->module_number }}
                                <span class="ml-1 text-[11px] opacity-80">({{ $mod->words_count }} kata)</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Add Word Card -->
                <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs">
                    <h3 class="text-sm font-bold text-neutral-text flex items-center gap-2 mb-4">
                        <i class="fa-solid fa-plus text-brand-primary"></i> Tambah Kata Baru ke Module {{ $moduleNumber }}
                    </h3>
                    <form action="{{ route('admin.english.vocab.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                        @csrf
                        <input type="hidden" name="module_number" value="{{ $moduleNumber }}">

                        <div class="sm:col-span-3">
                            <label class="block text-[11px] font-semibold uppercase text-neutral-muted mb-1.5">English Word <span class="text-red-accent">*</span></label>
                            <input type="text" name="english_word" placeholder="e.g. Resilience" required
                                class="w-full px-3 py-2 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                        </div>
                        <div class="sm:col-span-3">
                            <label class="block text-[11px] font-semibold uppercase text-neutral-muted mb-1.5">Arti / Makna Indonesia <span class="text-red-accent">*</span></label>
                            <input type="text" name="indonesian_meaning" placeholder="e.g. Ketangguhan / daya lentur" required
                                class="w-full px-3 py-2 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                        </div>
                        <div class="sm:col-span-4">
                            <label class="block text-[11px] font-semibold uppercase text-neutral-muted mb-1.5">Contoh Kalimat (Example)</label>
                            <input type="text" name="example_sentence" placeholder="e.g. She showed great resilience."
                                class="w-full px-3 py-2 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                        </div>
                        <div class="sm:col-span-2">
                            <button type="submit" class="w-full px-4 py-2 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-colors">
                                Simpan Kata
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Word Table -->
                <div class="bg-white border border-neutral-border rounded-2xl overflow-hidden shadow-xs">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-neutral-text">
                            <thead class="bg-neutral-subtle/70 text-neutral-muted uppercase tracking-wider border-b border-neutral-border font-semibold">
                                <tr>
                                    <th class="px-5 py-3.5">No.</th>
                                    <th class="px-5 py-3.5">Kata (English)</th>
                                    <th class="px-5 py-3.5">Terjemahan / Makna</th>
                                    <th class="px-5 py-3.5">Contoh Kalimat</th>
                                    <th class="px-5 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-border">
                                @forelse ($vocabWords as $index => $vw)
                                    <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                        <td class="px-5 py-3 font-mono text-neutral-muted">{{ $vocabWords->firstItem() + $index }}</td>
                                        <td class="px-5 py-3 font-bold text-neutral-text text-sm font-serif">{{ $vw->english_word }}</td>
                                        <td class="px-5 py-3 text-neutral-text font-medium">{{ $vw->indonesian_meaning }}</td>
                                        <td class="px-5 py-3 text-neutral-muted italic text-[11px]">{{ $vw->example_sentence ?? '-' }}</td>
                                        <td class="px-5 py-3 text-right">
                                            <form action="{{ route('admin.english.vocab.destroy', $vw) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kosakata ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-neutral-muted hover:text-red-accent hover:bg-neutral-subtle rounded-lg transition-colors cursor-pointer">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-8 text-center text-neutral-muted">
                                            Belum ada kata dalam Module {{ $moduleNumber }}.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($vocabWords->hasPages())
                        <div class="p-4 border-t border-neutral-border">
                            {{ $vocabWords->links() }}
                        </div>
                    @endif
                </div>
            </div>

        @elseif ($activeTab === 'storytelling')
            <!-- TAB 2: STORYTELLING -->
            <div class="space-y-6">
                <!-- Add Storytelling Form -->
                <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs">
                    <h3 class="text-sm font-bold text-neutral-text flex items-center gap-2 mb-4">
                        <i class="fa-solid fa-plus text-brand-primary"></i> Tambah Naskah Storytelling Baru
                    </h3>
                    <form action="{{ route('admin.english.storytelling.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <div class="sm:col-span-3">
                                <label class="block text-[11px] font-semibold uppercase text-neutral-muted mb-1.5">Judul Cerita <span class="text-red-accent">*</span></label>
                                <input type="text" name="title" placeholder="Contoh: The Legend of Roro Jonggrang" required
                                    class="w-full px-3 py-2 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold uppercase text-neutral-muted mb-1.5">Target Durasi (Menit) <span class="text-red-accent">*</span></label>
                                <input type="number" name="target_duration_minutes" value="3" required min="1" max="15"
                                    class="w-full px-3 py-2 rounded-xl bg-surface border border-neutral-border text-neutral-text text-xs text-center font-mono focus:outline-none focus:border-brand-primary">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold uppercase text-neutral-muted mb-1.5">Naskah Script Lengkap <span class="text-red-accent">*</span></label>
                            <textarea name="script_text" rows="5" required
                                placeholder="Tuliskan naskah cerita bahasa inggris..."
                                class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary"></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-colors">
                                Simpan Naskah Storytelling
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Storytelling Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse ($storytellings as $story)
                        <div class="bg-white border border-neutral-border rounded-2xl p-5 shadow-xs flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-2">
                                    <h4 class="font-bold text-neutral-text text-sm">{{ $story->title }}</h4>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-brand-soft text-brand-primary border border-brand-primary/20">
                                        <i class="fa-regular fa-clock mr-1"></i> {{ $story->target_duration_minutes }} Menit
                                    </span>
                                </div>
                                <div class="text-xs text-neutral-muted leading-relaxed line-clamp-4 font-serif mt-2 p-3 bg-surface rounded-xl border border-neutral-border/60">
                                    {{ $story->script_text }}
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-t border-neutral-border flex justify-end">
                                <form action="{{ route('admin.english.storytelling.destroy', $story) }}" method="POST" onsubmit="return confirm('Hapus naskah storytelling ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-xs text-red-accent hover:bg-red-50 rounded-lg transition-colors font-semibold">
                                        <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full p-8 text-center text-xs text-neutral-muted bg-white rounded-2xl border border-neutral-border">
                            Belum ada naskah storytelling.
                        </div>
                    @endforelse
                </div>
            </div>

        @elseif ($activeTab === 'wacana')
            <!-- TAB 3: WACANA -->
            <div class="space-y-6">
                <!-- Add Wacana Form -->
                <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs">
                    <h3 class="text-sm font-bold text-neutral-text flex items-center gap-2 mb-4">
                        <i class="fa-solid fa-plus text-brand-primary"></i> Tambah Teks Wacana Baru
                    </h3>
                    <form action="{{ route('admin.english.wacana.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <div class="sm:col-span-3">
                                <label class="block text-[11px] font-semibold uppercase text-neutral-muted mb-1.5">Judul Wacana / Artikel <span class="text-red-accent">*</span></label>
                                <input type="text" name="title" placeholder="Contoh: The Impact of Artificial Intelligence on Future Jobs" required
                                    class="w-full px-3 py-2 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold uppercase text-neutral-muted mb-1.5">Jumlah Paragraf <span class="text-red-accent">*</span></label>
                                <input type="number" name="paragraph_count" value="3" required min="1" max="10"
                                    class="w-full px-3 py-2 rounded-xl bg-surface border border-neutral-border text-neutral-text text-xs text-center font-mono focus:outline-none focus:border-brand-primary">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold uppercase text-neutral-muted mb-1.5">Isi Teks Bacaan Wacana <span class="text-red-accent">*</span></label>
                            <textarea name="script_text" rows="5" required
                                placeholder="Tuliskan teks wacana bacaan lengkap..."
                                class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary"></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-colors">
                                Simpan Teks Wacana
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Wacana Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse ($wacanas as $wacana)
                        <div class="bg-white border border-neutral-border rounded-2xl p-5 shadow-xs flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-2">
                                    <h4 class="font-bold text-neutral-text text-sm">{{ $wacana->title }}</h4>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-neutral-subtle text-neutral-text border border-neutral-border">
                                        <i class="fa-solid fa-align-left mr-1"></i> {{ $wacana->paragraph_count }} Paragraf
                                    </span>
                                </div>
                                <div class="text-xs text-neutral-muted leading-relaxed line-clamp-4 font-serif mt-2 p-3 bg-surface rounded-xl border border-neutral-border/60">
                                    {{ $wacana->script_text }}
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-t border-neutral-border flex justify-end">
                                <form action="{{ route('admin.english.wacana.destroy', $wacana) }}" method="POST" onsubmit="return confirm('Hapus teks wacana ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-xs text-red-accent hover:bg-red-50 rounded-lg transition-colors font-semibold">
                                        <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full p-8 text-center text-xs text-neutral-muted bg-white rounded-2xl border border-neutral-border">
                            Belum ada teks wacana bacaan.
                        </div>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
