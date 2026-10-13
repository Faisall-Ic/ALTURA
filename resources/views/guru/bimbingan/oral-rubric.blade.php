<x-layouts.app>
    <x-slot:title>Rubrik Penilaian Ujian Lisan — {{ $booking->student?->user?->name }}</x-slot:title>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Top Nav Link -->
        <div class="flex items-center justify-between text-xs pb-2 border-b border-neutral-border">
            <a href="{{ url()->previous(route('guru.bimbingan.student.profile', $booking->student_id)) }}"
                class="text-brand-primary hover:text-brand-navy flex items-center gap-1.5 font-semibold">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Kembali</span>
            </a>
            <span class="text-neutral-muted font-mono">
                Sesi Ujian Lisan • {{ $booking->booking_date->format('d M Y') }} ({{ $booking->time_slot }})
            </span>
        </div>

        <!-- Student & Task Header Card -->
        <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-brand-primary block">
                    SISWA YANG DIUJI
                </span>
                <h1 class="text-xl font-bold text-neutral-text mt-0.5">{{ $booking->student?->user?->name }}</h1>
                <p class="text-xs text-neutral-muted">
                    {{ $booking->student?->academicClass?->name }} • NISN: <span class="font-mono text-neutral-text">{{ $booking->student?->nisn }}</span>
                </p>
            </div>

            <div class="text-left sm:text-right">
                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-brand-soft text-brand-primary border border-brand-primary/20">
                    {{ strtoupper($booking->speakingTask?->type) }}
                </span>
                <h2 class="text-sm font-bold text-neutral-text mt-1">{{ $booking->speakingTask?->title }}</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left 7 Cols: Interactive Rubric Scoring Form -->
            <div class="lg:col-span-7 space-y-6">
                <form action="{{ route('guru.bimbingan.rubric.store', $booking->id) }}" method="POST" class="bg-white border border-neutral-border rounded-2xl p-6 shadow-sm space-y-6">
                    @csrf

                    <div class="flex items-center justify-between border-b border-neutral-border pb-3">
                        <h3 class="text-sm font-bold text-neutral-text">4 Aspek Penilaian Rubrik Lisan</h3>
                        <div class="flex items-center gap-1.5 font-mono text-xs">
                            <span class="text-neutral-muted">Total:</span>
                            <span id="live-total-score" class="text-lg font-black text-emerald-600">
                                {{ $booking->grade ?? 80 }}
                            </span>
                            <span class="text-neutral-muted">/ 100</span>
                        </div>
                    </div>

                    <!-- Criteria 1: Pronunciation -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <label class="font-bold text-neutral-text flex items-center gap-1.5">
                                <span>1. Pronunciation & Intonation</span>
                            </label>
                            <span id="val-pron" class="font-mono font-bold text-brand-primary">
                                {{ $booking->rubric_pronunciation ?? 20 }} / 25
                            </span>
                        </div>
                        <input type="range" name="rubric_pronunciation" min="1" max="25"
                            value="{{ $booking->rubric_pronunciation ?? 20 }}"
                            oninput="updateRubricScore()" id="slider-pron"
                            class="w-full accent-brand-primary cursor-pointer">
                        <p class="text-[11px] text-neutral-muted">Ketepatan fonem, aksen kata, dan intonasi kalimat.</p>
                    </div>

                    <!-- Criteria 2: Fluency -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <label class="font-bold text-neutral-text flex items-center gap-1.5">
                                <span>2. Fluency & Pace</span>
                            </label>
                            <span id="val-fluency" class="font-mono font-bold text-brand-primary">
                                {{ $booking->rubric_fluency ?? 20 }} / 25
                            </span>
                        </div>
                        <input type="range" name="rubric_fluency" min="1" max="25"
                            value="{{ $booking->rubric_fluency ?? 20 }}"
                            oninput="updateRubricScore()" id="slider-fluency"
                            class="w-full accent-brand-primary cursor-pointer">
                        <p class="text-[11px] text-neutral-muted">Kelancaran berbicara tanpa jeda ragu berlebihan.</p>
                    </div>

                    <!-- Criteria 3: Vocabulary -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <label class="font-bold text-neutral-text flex items-center gap-1.5">
                                <span>3. Lexical Range & Vocabulary</span>
                            </label>
                            <span id="val-vocab" class="font-mono font-bold text-brand-primary">
                                {{ $booking->rubric_vocabulary ?? 20 }} / 25
                            </span>
                        </div>
                        <input type="range" name="rubric_vocabulary" min="1" max="25"
                            value="{{ $booking->rubric_vocabulary ?? 20 }}"
                            oninput="updateRubricScore()" id="slider-vocab"
                            class="w-full accent-brand-primary cursor-pointer">
                        <p class="text-[11px] text-neutral-muted">Kekayaan dan ketepatan pilihan diksi akademik.</p>
                    </div>

                    <!-- Criteria 4: Comprehension -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <label class="font-bold text-neutral-text flex items-center gap-1.5">
                                <span>4. Comprehension & Expression</span>
                            </label>
                            <span id="val-comp" class="font-mono font-bold text-brand-primary">
                                {{ $booking->rubric_comprehension ?? 20 }} / 25
                            </span>
                        </div>
                        <input type="range" name="rubric_comprehension" min="1" max="25"
                            value="{{ $booking->rubric_comprehension ?? 20 }}"
                            oninput="updateRubricScore()" id="slider-comp"
                            class="w-full accent-brand-primary cursor-pointer">
                        <p class="text-[11px] text-neutral-muted">Penghayatan isi naskah dan ekspresi komunikasi.</p>
                    </div>

                    <!-- Qualitative Feedback Notes -->
                    <div class="space-y-1.5 pt-2 border-t border-neutral-border">
                        <label class="block text-xs font-bold text-neutral-muted uppercase tracking-wider">
                            Catatan Kualitatif & Evaluasi Mentor
                        </label>
                        <textarea name="rubric_notes" rows="3"
                            placeholder="Tuliskan evaluasi kekuatan siswa dan hal yang perlu ditingkatkan..."
                            class="w-full px-4 py-2.5 rounded-xl bg-white border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary focus:ring-1 focus:ring-brand-primary">{{ $booking->rubric_notes }}</textarea>
                    </div>

                    <button type="submit"
                        class="w-full py-3 bg-brand-primary hover:bg-brand-navy text-white text-xs font-bold rounded-xl shadow-sm transition-all cursor-pointer">
                        Simpan & Terbitkan Nilai Ujian Lisan
                    </button>
                </form>
            </div>

            <!-- Right 5 Cols: Script Preview Reference for Teacher -->
            <div class="lg:col-span-5 space-y-4">
                <div class="bg-white border border-neutral-border rounded-2xl p-5 shadow-sm space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-muted">
                        Naskah Referensi Guru (Script)
                    </h3>
                    <div class="p-4 bg-neutral-subtle/70 rounded-xl border border-neutral-border text-xs text-neutral-text font-serif leading-relaxed max-h-[500px] overflow-y-auto whitespace-pre-line">
                        {{ $booking->speakingTask?->script_text }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Slider Calc JS -->
    <script>
        function updateRubricScore() {
            const pron = parseInt(document.getElementById('slider-pron').value) || 0;
            const flu = parseInt(document.getElementById('slider-fluency').value) || 0;
            const voc = parseInt(document.getElementById('slider-vocab').value) || 0;
            const comp = parseInt(document.getElementById('slider-comp').value) || 0;

            document.getElementById('val-pron').textContent = pron + ' / 25';
            document.getElementById('val-fluency').textContent = flu + ' / 25';
            document.getElementById('val-vocab').textContent = voc + ' / 25';
            document.getElementById('val-comp').textContent = comp + ' / 25';

            const total = pron + flu + voc + comp;
            document.getElementById('live-total-score').textContent = total;
        }
        document.addEventListener('DOMContentLoaded', updateRubricScore);
    </script>
</x-layouts.app>
