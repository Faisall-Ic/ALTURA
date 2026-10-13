<x-layouts.app>
    <x-slot:title>Kuis CBT Kosakata — Modul {{ $module }}</x-slot:title>

    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Top Nav Link -->
        <div class="flex items-center justify-between text-xs pb-3 border-b border-neutral-border">
            <a href="{{ route('siswa.english.vocab.index') }}" class="text-brand-primary hover:underline font-semibold flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Batalkan &amp; Kembali ke Modul</span>
            </a>
            <span class="text-neutral-muted font-mono font-semibold">
                Kuis Evaluasi Modul #{{ $module }} (10 Soal Acak)
            </span>
        </div>

        <form action="{{ route('siswa.english.vocab.submit', $module) }}" method="POST" id="cbt-quiz-form" class="space-y-6">
            @csrf

            <!-- Instructions Banner -->
            <div class="bg-brand-soft border border-blue-200 rounded-2xl p-4 text-xs text-brand-dark flex items-center gap-3">
                <i class="fa-solid fa-circle-info text-brand-primary text-base shrink-0"></i>
                <p>
                    Pilihlah arti kata dalam bahasa Indonesia yang paling tepat untuk masing-masing kosakata di bawah ini. Minimal skor kelulusan adalah <strong>75%</strong> (minimal 8 benar).
                </p>
            </div>

            <!-- Questions List -->
            @foreach ($quizQuestions as $index => $q)
                <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="flex items-start justify-between">
                        <div class="space-y-1">
                            <span class="text-[10px] font-mono uppercase tracking-wider text-neutral-muted font-bold block">
                                PERTANYAAN #{{ $index + 1 }} DARI 10
                            </span>
                            <h2 class="text-xl font-extrabold text-neutral-text font-mono tracking-tight">
                                "{{ $q['english_word'] }}"
                            </h2>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-neutral-subtle text-neutral-muted border border-neutral-border">
                            Pilihan Ganda
                        </span>
                    </div>

                    @if ($q['example_sentence'])
                        <div class="p-3 bg-neutral-subtle/60 rounded-xl border border-neutral-border text-xs text-neutral-muted italic">
                            Contoh Kalimat: "{{ $q['example_sentence'] }}"
                        </div>
                    @endif

                    <!-- 4 Options -->
                    <div class="space-y-2 pt-1">
                        @foreach ($q['options'] as $optIndex => $opt)
                            <label class="flex items-center gap-3 p-3.5 rounded-xl bg-surface border border-neutral-border hover:border-brand-primary hover:bg-brand-soft/40 cursor-pointer transition-all group">
                                <input type="radio" name="answers[{{ $q['id'] }}]" value="{{ $opt }}" required
                                    class="text-brand-primary focus:ring-brand-active border-neutral-border">
                                <span class="text-xs font-semibold text-neutral-text group-hover:text-brand-primary transition-colors">
                                    {{ $opt }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Submit Section -->
            <div class="bg-white border border-neutral-border rounded-2xl p-5 flex items-center justify-between shadow-sm">
                <span class="text-xs text-neutral-muted font-medium">Pastikan seluruh 10 pertanyaan telah dijawab dengan teliti.</span>
                <button type="submit" onclick="return confirm('Kirimkan lembar jawaban kuis ini untuk dinilai otomatis?')"
                    class="px-6 py-2.5 bg-brand-primary hover:bg-brand-navy text-white text-xs font-bold rounded-xl shadow-xs transition-all cursor-pointer">
                    Selesai &amp; Kumpulkan Jawaban
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
