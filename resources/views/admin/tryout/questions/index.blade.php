<x-layouts.app>
    <x-slot:title>Kelola Bank Soal — {{ $tryout->title }}</x-slot:title>

    <div x-data="{
        showCreateModal: false,
        showImportModal: false,
        showEditModal: false,
        editQuestion: {
            id: null,
            subtest_code: 'TPS',
            question_text: '',
            image_path: '',
            option_a: '',
            option_b: '',
            option_c: '',
            option_d: '',
            option_e: '',
            correct_answer: 'A',
            explanation: ''
        },
        openEdit(q) {
            this.editQuestion = {
                id: q.id,
                subtest_code: q.subtest_code,
                question_text: q.question_text,
                image_path: q.image_path || '',
                option_a: q.options ? (q.options.A || '') : '',
                option_b: q.options ? (q.options.B || '') : '',
                option_c: q.options ? (q.options.C || '') : '',
                option_d: q.options ? (q.options.D || '') : '',
                option_e: q.options ? (q.options.E || '') : '',
                correct_answer: q.correct_answer || 'A',
                explanation: q.explanation || ''
            };
            this.showEditModal = true;
        }
    }" class="space-y-6">

        <!-- Top Header Card -->
        <div class="bg-white border border-neutral-border rounded-2xl p-6 sm:p-8 shadow-xs">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <a href="{{ route('admin.tryout.index') }}" class="text-xs font-semibold text-brand-primary hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-arrow-left text-[11px]"></i>
                            <span>Daftar Paket Tryout</span>
                        </a>
                        <span class="text-neutral-muted text-xs">/</span>
                        <span class="text-xs font-mono font-semibold text-neutral-muted">Kelola Soal</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-soft border border-brand-primary/20 flex items-center justify-center text-brand-primary shrink-0 shadow-2xs">
                            <i class="fa-solid fa-file-circle-question text-lg"></i>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-neutral-text leading-tight">{{ $tryout->title }}</h1>
                            <p class="text-xs text-neutral-muted mt-0.5">
                                Gelombang: <strong class="text-neutral-text">{{ $tryout->batch_name ?? 'Reguler' }}</strong> &bull; Total Soal: <strong class="text-brand-primary">{{ $tryout->questions_count }} Butir</strong>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-2.5">
                    <button type="button" @click="showImportModal = true"
                        class="px-4 py-2.5 rounded-xl bg-white hover:bg-neutral-subtle text-neutral-text text-xs font-bold border border-neutral-border shadow-2xs transition-all flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-file-excel text-emerald-600"></i>
                        <span>Import Soal via Excel</span>
                    </button>

                    <button type="button" @click="showCreateModal = true"
                        class="px-4 py-2.5 rounded-xl bg-brand-primary hover:bg-brand-navy text-white text-xs font-bold shadow-xs transition-all flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Tambah Soal Manual</span>
                    </button>
                </div>
            </div>

            <!-- Subtest Filter Bar -->
            <div class="mt-6 pt-5 border-t border-neutral-border/70 flex flex-wrap items-center gap-2">
                <span class="text-xs font-semibold text-neutral-muted mr-1">Filter Subtes:</span>
                <a href="{{ route('admin.tryouts.questions.index', $tryout->id) }}"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ empty($selectedSubtest) ? 'bg-brand-primary text-white shadow-2xs' : 'bg-neutral-subtle text-neutral-text hover:bg-neutral-border/60 border border-neutral-border' }}">
                    Semua Subtes ({{ $tryout->questions_count }})
                </a>

                @foreach ($subtests as $code => $name)
                    @php
                        $count = $subtestCounts[$code] ?? 0;
                        $isActive = $selectedSubtest === $code;
                    @endphp
                    <a href="{{ route('admin.tryouts.questions.index', ['id' => $tryout->id, 'subtest' => $code]) }}"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $isActive ? 'bg-brand-primary text-white shadow-2xs' : 'bg-neutral-subtle text-neutral-text hover:bg-neutral-border/60 border border-neutral-border' }}">
                        <span>{{ $code }}</span>
                        <span class="ml-1 text-[10px] {{ $isActive ? 'text-blue-100' : 'text-neutral-muted' }}">({{ $count }})</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Questions Listing -->
        <div class="space-y-4">
            @forelse ($questions as $index => $q)
                <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs space-y-4 hover:border-brand-primary/40 transition-all">
                    <!-- Question Top Bar -->
                    <div class="flex items-start justify-between gap-4 pb-3 border-b border-neutral-border/60">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <span class="w-7 h-7 rounded-lg bg-neutral-subtle border border-neutral-border flex items-center justify-center text-xs font-bold font-mono text-neutral-text">
                                #{{ $questions->firstItem() + $index }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-soft text-brand-primary border border-brand-primary/20">
                                {{ $q->subtest_code }} &bull; {{ $subtests[$q->subtest_code] ?? $q->subtest_code }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Kunci: {{ $q->correct_answer }}
                            </span>
                        </div>

                        <!-- Action Controls -->
                        <div class="flex items-center gap-1.5 shrink-0">
                            <button type="button" @click="openEdit({{ json_encode($q) }})"
                                class="p-2 text-neutral-muted hover:text-brand-primary hover:bg-brand-soft rounded-lg transition-colors cursor-pointer text-xs"
                                title="Edit Soal">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <form action="{{ route('admin.tryouts.questions.destroy', [$tryout->id, $q->id]) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus butir soal ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-neutral-muted hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer text-xs"
                                    title="Hapus Soal">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Question Content -->
                    <div class="space-y-3">
                        @if ($q->image_path)
                            <div class="rounded-xl overflow-hidden border border-neutral-border bg-neutral-subtle max-h-64 inline-block">
                                <img src="{{ $q->image_path }}" alt="Gambar Soal" class="max-h-64 object-contain">
                            </div>
                        @endif

                        <div class="text-sm text-neutral-text leading-relaxed whitespace-pre-line">
                            {{ $q->question_text }}
                        </div>
                    </div>

                    <!-- Options Grid A-E -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2.5 pt-2">
                        @php
                            $opts = is_array($q->options) ? $q->options : (json_decode($q->options, true) ?? []);
                        @endphp
                        @foreach (['A', 'B', 'C', 'D', 'E'] as $key)
                            @php
                                $val = $opts[$key] ?? '';
                                $isCorrect = $q->correct_answer === $key;
                            @endphp
                            <div class="p-3 rounded-xl border text-xs transition-all {{ $isCorrect ? 'bg-emerald-50 border-emerald-300 text-emerald-900 font-semibold shadow-2xs' : 'bg-surface border-neutral-border text-neutral-text' }}">
                                <div class="flex items-center justify-between gap-1 mb-1">
                                    <span class="w-5 h-5 rounded-md flex items-center justify-center font-bold text-[10px] {{ $isCorrect ? 'bg-emerald-600 text-white' : 'bg-neutral-subtle text-neutral-muted' }}">
                                        {{ $key }}
                                    </span>
                                    @if ($isCorrect)
                                        <i class="fa-solid fa-check text-[11px] text-emerald-600"></i>
                                    @endif
                                </div>
                                <span class="line-clamp-3">{{ $val ?: '-' }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Explanation Box -->
                    @if ($q->explanation)
                        <div class="p-3.5 bg-neutral-subtle/70 rounded-xl border border-neutral-border/80 text-xs text-neutral-muted space-y-1">
                            <span class="font-bold text-neutral-text text-[11px] uppercase tracking-wider block">
                                <i class="fa-solid fa-lightbulb text-amber-500 mr-1"></i> Pembahasan Soal:
                            </span>
                            <p class="whitespace-pre-line">{{ $q->explanation }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white border border-neutral-border rounded-2xl p-12 text-center text-neutral-muted shadow-xs space-y-3">
                    <div class="w-14 h-14 rounded-2xl bg-neutral-subtle border border-neutral-border flex items-center justify-center mx-auto text-neutral-muted text-xl">
                        <i class="fa-solid fa-folder-open"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-neutral-text text-sm">Belum Ada Butir Soal</h3>
                        <p class="text-xs text-neutral-muted mt-1">Tambahkan soal secara manual atau gunakan tombol import Excel untuk memasukkan bank soal.</p>
                    </div>
                </div>
            @endforelse

            <div class="pt-2">
                {{ $questions->links() }}
            </div>
        </div>

        <!-- Create Question Modal -->
        <div x-show="showCreateModal" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
            <div @click.away="showCreateModal = false"
                class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-xl border border-neutral-border text-left space-y-4 max-h-[90vh] overflow-y-auto animate-scale-in">
                
                <div class="flex items-center justify-between pb-3 border-b border-neutral-border">
                    <h3 class="text-base font-bold text-neutral-text flex items-center gap-2">
                        <i class="fa-solid fa-circle-plus text-brand-primary"></i>
                        <span>Tambah Butir Soal Baru</span>
                    </h3>
                    <button type="button" @click="showCreateModal = false" class="text-neutral-muted hover:text-neutral-text cursor-pointer">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <form action="{{ route('admin.tryouts.questions.store', $tryout->id) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-neutral-text mb-1">Subtes SNBT <span class="text-red-accent">*</span></label>
                        <select name="subtest_code" required class="w-full bg-surface border border-neutral-border rounded-xl p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                            @foreach ($subtests as $code => $name)
                                <option value="{{ $code }}">{{ $code }} — {{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-neutral-text mb-1">Teks / Narasi Soal <span class="text-red-accent">*</span></label>
                        <textarea name="question_text" rows="4" required placeholder="Tuliskan stimulus, teks bacaan, atau pertanyaan soal di sini..."
                            class="w-full bg-surface border border-neutral-border rounded-xl p-3 text-xs text-neutral-text focus:outline-none focus:border-brand-primary"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-neutral-text mb-1">URL / Path Gambar Ilustrasi (Opsional)</label>
                        <input type="text" name="image_path" placeholder="https://example.com/image.png atau /storage/soal/img1.png"
                            class="w-full bg-surface border border-neutral-border rounded-xl p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                    </div>

                    <!-- Options A-E -->
                    <div class="space-y-2.5 pt-2 border-t border-neutral-border">
                        <span class="block text-xs font-bold text-neutral-text">Pilihan Jawaban (A - E) &amp; Kunci Jawaban</span>
                        
                        @foreach (['A', 'B', 'C', 'D', 'E'] as $opt)
                            <div class="flex items-center gap-2">
                                <label class="w-8 h-8 rounded-lg bg-neutral-subtle border border-neutral-border flex items-center justify-center font-bold text-xs text-neutral-text shrink-0">
                                    {{ $opt }}
                                </label>
                                <input type="text" name="option_{{ strtolower($opt) }}" required placeholder="Teks pilihan jawaban {{ $opt }}..."
                                    class="flex-1 bg-surface border border-neutral-border rounded-xl p-2 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                                <label class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-neutral-border bg-neutral-subtle cursor-pointer text-xs">
                                    <input type="radio" name="correct_answer" value="{{ $opt }}" {{ $opt === 'A' ? 'checked' : '' }}
                                        class="text-brand-primary focus:ring-0">
                                    <span class="font-bold text-[10px]">Kunci</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-neutral-text mb-1">Pembahasan &amp; Keterangan Kunci (Opsional)</label>
                        <textarea name="explanation" rows="3" placeholder="Tuliskan langkah pengerjaan atau alasan jawaban benar..."
                            class="w-full bg-surface border border-neutral-border rounded-xl p-3 text-xs text-neutral-text focus:outline-none focus:border-brand-primary"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-neutral-border">
                        <button type="button" @click="showCreateModal = false"
                            class="px-4 py-2 rounded-xl border border-neutral-border bg-white hover:bg-neutral-subtle text-neutral-text text-xs font-bold transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2 rounded-xl bg-brand-primary hover:bg-brand-navy text-white text-xs font-bold shadow-xs transition-all cursor-pointer">
                            Simpan Soal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Question Modal -->
        <div x-show="showEditModal" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
            <div @click.away="showEditModal = false"
                class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-xl border border-neutral-border text-left space-y-4 max-h-[90vh] overflow-y-auto animate-scale-in">
                
                <div class="flex items-center justify-between pb-3 border-b border-neutral-border">
                    <h3 class="text-base font-bold text-neutral-text flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-brand-primary"></i>
                        <span>Edit Butir Soal</span>
                    </h3>
                    <button type="button" @click="showEditModal = false" class="text-neutral-muted hover:text-neutral-text cursor-pointer">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <form :action="'{{ url('/admin/tryouts/' . $tryout->id . '/questions') }}/' + editQuestion.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-neutral-text mb-1">Subtes SNBT <span class="text-red-accent">*</span></label>
                        <select name="subtest_code" x-model="editQuestion.subtest_code" required class="w-full bg-surface border border-neutral-border rounded-xl p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                            @foreach ($subtests as $code => $name)
                                <option value="{{ $code }}">{{ $code }} — {{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-neutral-text mb-1">Teks / Narasi Soal <span class="text-red-accent">*</span></label>
                        <textarea name="question_text" x-model="editQuestion.question_text" rows="4" required
                            class="w-full bg-surface border border-neutral-border rounded-xl p-3 text-xs text-neutral-text focus:outline-none focus:border-brand-primary"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-neutral-text mb-1">URL / Path Gambar Ilustrasi (Opsional)</label>
                        <input type="text" name="image_path" x-model="editQuestion.image_path"
                            class="w-full bg-surface border border-neutral-border rounded-xl p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                    </div>

                    <!-- Options A-E -->
                    <div class="space-y-2.5 pt-2 border-t border-neutral-border">
                        <span class="block text-xs font-bold text-neutral-text">Pilihan Jawaban (A - E) &amp; Kunci Jawaban</span>
                        
                        @foreach (['A', 'B', 'C', 'D', 'E'] as $opt)
                            <div class="flex items-center gap-2">
                                <label class="w-8 h-8 rounded-lg bg-neutral-subtle border border-neutral-border flex items-center justify-center font-bold text-xs text-neutral-text shrink-0">
                                    {{ $opt }}
                                </label>
                                <input type="text" name="option_{{ strtolower($opt) }}" x-model="editQuestion.option_{{ strtolower($opt) }}" required
                                    class="flex-1 bg-surface border border-neutral-border rounded-xl p-2 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                                <label class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-neutral-border bg-neutral-subtle cursor-pointer text-xs">
                                    <input type="radio" name="correct_answer" value="{{ $opt }}" :checked="editQuestion.correct_answer === '{{ $opt }}'"
                                        class="text-brand-primary focus:ring-0">
                                    <span class="font-bold text-[10px]">Kunci</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-neutral-text mb-1">Pembahasan &amp; Keterangan Kunci (Opsional)</label>
                        <textarea name="explanation" x-model="editQuestion.explanation" rows="3"
                            class="w-full bg-surface border border-neutral-border rounded-xl p-3 text-xs text-neutral-text focus:outline-none focus:border-brand-primary"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-neutral-border">
                        <button type="button" @click="showEditModal = false"
                            class="px-4 py-2 rounded-xl border border-neutral-border bg-white hover:bg-neutral-subtle text-neutral-text text-xs font-bold transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2 rounded-xl bg-brand-primary hover:bg-brand-navy text-white text-xs font-bold shadow-xs transition-all cursor-pointer">
                            Perbarui Soal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Excel Bulk Import Modal -->
        <div x-show="showImportModal" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
            <div @click.away="showImportModal = false"
                class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-neutral-border text-left space-y-4 animate-scale-in">
                
                <div class="flex items-center justify-between pb-3 border-b border-neutral-border">
                    <h3 class="text-base font-bold text-neutral-text flex items-center gap-2">
                        <i class="fa-solid fa-file-excel text-emerald-600"></i>
                        <span>Import Bank Soal via Excel</span>
                    </h3>
                    <button type="button" @click="showImportModal = false" class="text-neutral-muted hover:text-neutral-text cursor-pointer">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <form action="{{ route('admin.tryouts.questions.import', $tryout->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div class="bg-brand-soft border border-blue-200 rounded-xl p-4 text-xs text-brand-dark space-y-2">
                        <span class="font-bold flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-info text-brand-primary"></i>
                            Panduan Format Kolom Spreadsheet:
                        </span>
                        <p class="leading-relaxed text-[11px] text-neutral-muted">
                            Pastikan file spreadsheet (<code class="bg-white px-1 py-0.5 rounded font-mono">.xlsx</code>, <code class="bg-white px-1 py-0.5 rounded font-mono">.xls</code>, atau <code class="bg-white px-1 py-0.5 rounded font-mono">.csv</code>) memiliki baris header berikut:
                        </p>
                        <div class="p-2 bg-white rounded-lg border border-neutral-border font-mono text-[10px] text-neutral-text overflow-x-auto whitespace-nowrap">
                            subtest_code, question_text, option_a, option_b, option_c, option_d, option_e, correct_answer, explanation, image_path
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-neutral-text mb-1.5">Pilih Berkas Excel Bank Soal</label>
                        <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" required
                            class="w-full text-xs text-neutral-muted file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-soft file:text-brand-primary hover:file:bg-blue-100 cursor-pointer border border-neutral-border rounded-xl p-1.5">
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-neutral-border">
                        <button type="button" @click="showImportModal = false"
                            class="px-4 py-2 rounded-xl border border-neutral-border bg-white hover:bg-neutral-subtle text-neutral-text text-xs font-bold transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition-all cursor-pointer flex items-center gap-1.5">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <span>Mulai Import Soal</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
