<x-layouts.app>
    <x-slot:title>Kuis CBT Kosakata (Essay) — Modul {{ $module }}</x-slot:title>

    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Top Navigation -->
        <div class="flex items-center justify-between text-xs pb-3 border-b border-neutral-border">
            <a href="{{ route('siswa.english.vocab.index') }}" class="text-brand-primary hover:underline font-semibold flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Batalkan &amp; Kembali ke Modul</span>
            </a>
            <span class="text-neutral-muted font-mono font-semibold">
                Kuis Evaluasi Modul #{{ $module }} ({{ count($words) }} Soal Input Teks)
            </span>
        </div>

        <form action="{{ route('siswa.english.vocab.submit', $module) }}" method="POST" id="vocab-essay-form" class="space-y-6">
            @csrf

            <!-- Instructions Banner -->
            <div class="bg-brand-soft border border-blue-200 rounded-2xl p-4 text-xs text-brand-dark flex items-start sm:items-center gap-3">
                <i class="fa-solid fa-keyboard text-brand-primary text-base shrink-0 mt-0.5 sm:mt-0"></i>
                <p class="leading-relaxed">
                    Ketik terjemahan atau padanan kata dalam bahasa Inggris yang tepat untuk setiap kosakata berikut secara manual. Sistem mencocokkan input Anda dengan sinonim baku yang diakui (<code class="bg-blue-100 text-brand-primary px-1.5 py-0.5 rounded font-mono text-[11px]">acceptable_answers</code>). Minimal kelulusan adalah <strong>75%</strong>.
                </p>
            </div>

            <!-- Questions List -->
            @foreach ($words as $index => $word)
                <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <span class="text-[10px] font-mono uppercase tracking-wider text-neutral-muted font-bold block">
                                PERTANYAAN #{{ $index + 1 }} DARI {{ count($words) }}
                            </span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-xs font-semibold text-neutral-muted">Prompt Kosakata:</span>
                                <h2 class="text-xl font-extrabold text-neutral-text font-mono tracking-tight">
                                    "{{ $word->indonesian_meaning }}"
                                </h2>
                            </div>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-brand-soft text-brand-primary border border-blue-200 shrink-0">
                            Input Teks Manual
                        </span>
                    </div>

                    @if ($word->example_sentence)
                        <div class="p-3 bg-neutral-subtle/60 rounded-xl border border-neutral-border text-xs text-neutral-muted italic">
                            Contoh Kalimat / Konteks: "{{ $word->example_sentence }}"
                        </div>
                    @endif

                    <!-- Single Text Input Box (Pure Essay) -->
                    <div class="space-y-1.5 pt-1">
                        <label for="answer-{{ $word->id }}" class="block text-xs font-semibold text-neutral-text">
                            Jawaban Terjemahan Bahasa Inggris:
                        </label>
                        <input 
                            id="answer-{{ $word->id }}"
                            type="text" 
                            name="answers[{{ $word->id }}]" 
                            class="w-full px-4 py-3 rounded-xl border border-neutral-border focus:border-brand-primary focus:ring-2 focus:ring-brand-soft text-sm font-medium text-neutral-text placeholder:text-neutral-muted transition-all"
                            placeholder="Ketik terjemahan / padanan bahasa Inggris..."
                            autocomplete="off"
                            required
                        >
                    </div>
                </div>
            @endforeach

            <!-- Submit Section -->
            <div class="bg-white border border-neutral-border rounded-2xl p-5 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
                <span class="text-xs text-neutral-muted font-medium">
                    Pastikan seluruh {{ count($words) }} pertanyaan telah diisi sebelum mengirimkan.
                </span>
                <button type="submit" onclick="return confirm('Kirimkan seluruh jawaban essay ini untuk dievaluasi secara otomatis?')"
                    class="w-full sm:w-auto px-6 py-2.5 bg-brand-primary hover:bg-brand-navy text-white text-xs font-bold rounded-xl shadow-xs transition-all cursor-pointer flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    <span>Selesai &amp; Kumpulkan Jawaban</span>
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
