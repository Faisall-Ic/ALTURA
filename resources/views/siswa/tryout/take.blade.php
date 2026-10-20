<x-layouts.app>
    <x-slot:title>CBT Live Exam: {{ $tryout->title }}</x-slot:title>

    <div x-data="cbtExam({
        durationMinutes: {{ $tryout->duration_minutes ?? 90 }},
        questions: {{ json_encode($questionsData) }},
        submitUrl: '{{ route('siswa.tryout.submit', $tryout->id) }}',
        csrfToken: '{{ csrf_token() }}'
    })" class="space-y-6 select-none">
        
        <!-- Top Sticky CBT Status Header Bar -->
        <div class="bg-white border border-neutral-border rounded-2xl p-4 sm:p-5 shadow-xs sticky top-20 z-20 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-soft border border-brand-primary/20 flex items-center justify-center text-brand-primary shrink-0 shadow-2xs">
                    <i class="fa-solid fa-laptop-code text-base"></i>
                </div>
                <div>
                    <h1 class="text-base sm:text-lg font-bold text-neutral-text leading-tight">{{ $tryout->title }}</h1>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-soft text-brand-primary border border-brand-primary/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-primary animate-pulse"></span>
                            <span x-text="currentQuestion.subtest_code || 'TPS'"></span>
                        </span>
                        <span class="text-xs text-neutral-muted">• Soal <span x-text="currentIndex + 1" class="font-bold text-neutral-text"></span> dari <span x-text="questions.length"></span></span>
                    </div>
                </div>
            </div>

            <!-- Timer Countdown Display -->
            <div class="flex items-center gap-4 shrink-0 justify-between sm:justify-end">
                <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-neutral-subtle border border-neutral-border">
                    <i class="fa-regular fa-clock text-xs" :class="timeRemaining < 300 ? 'text-red-accent animate-bounce' : 'text-brand-primary'"></i>
                    <span class="text-xs font-semibold text-neutral-muted">Sisa Waktu:</span>
                    <span class="text-base font-black font-mono tracking-wider" 
                          :class="timeRemaining < 300 ? 'text-red-accent' : 'text-neutral-text'" 
                          x-text="formattedTime"></span>
                </div>

                <button type="button" @click="showFinishModal = true" 
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition-all cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid fa-flag-checkered"></i>
                    <span>Kumpulkan</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Question Content & Options -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white border border-neutral-border rounded-2xl p-6 sm:p-8 shadow-xs min-h-[460px] flex flex-col justify-between">
                    <div>
                        <!-- Subtest Header Badge & Question Number -->
                        <div class="flex items-center justify-between pb-4 border-b border-neutral-border mb-6">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-brand-primary uppercase tracking-wider">Subtes:</span>
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-brand-soft text-brand-primary border border-brand-primary/20" x-text="currentQuestion.subtest_name"></span>
                            </div>
                            <span class="text-xs text-neutral-muted font-medium">Nomor Urut: <strong class="text-neutral-text" x-text="currentIndex + 1"></strong></span>
                        </div>

                        <!-- Question Stimulus / Text -->
                        <div class="prose max-w-none text-neutral-text text-sm sm:text-base leading-relaxed space-y-4">
                            <template x-if="currentQuestion.image_path">
                                <div class="my-4 rounded-xl overflow-hidden border border-neutral-border bg-neutral-subtle max-h-80 flex items-center justify-center">
                                    <img :src="currentQuestion.image_path" alt="Ilustrasi Soal" class="max-h-80 object-contain">
                                </div>
                            </template>
                            <p class="whitespace-pre-line" x-text="currentQuestion.question_text"></p>
                        </div>

                        <!-- Options List (A to E) -->
                        <div class="mt-8 space-y-3">
                            <template x-for="(optionText, optionKey) in currentQuestion.options" :key="optionKey">
                                <label class="flex items-center gap-3.5 p-4 rounded-xl border transition-all cursor-pointer group"
                                    :class="answers[currentQuestion.id] === optionKey ? 'border-brand-primary bg-brand-soft text-brand-primary shadow-2xs font-semibold' : 'border-neutral-border bg-white hover:bg-neutral-subtle/60 text-neutral-text'">
                                    <input type="radio" 
                                           :name="'q_' + currentQuestion.id" 
                                           :value="optionKey" 
                                           x-model="answers[currentQuestion.id]" 
                                           @change="saveAnswer(currentQuestion.id, optionKey)"
                                           class="sr-only">
                                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold shrink-0 transition-colors"
                                        :class="answers[currentQuestion.id] === optionKey ? 'bg-brand-primary text-white' : 'bg-neutral-subtle border border-neutral-border text-neutral-muted group-hover:bg-neutral-border'">
                                        <span x-text="optionKey"></span>
                                    </span>
                                    <span class="text-sm leading-snug flex-1" x-text="optionText"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- Bottom Nav Buttons -->
                    <div class="mt-8 pt-6 border-t border-neutral-border flex items-center justify-between gap-3">
                        <button type="button" @click="prevQuestion()" :disabled="currentIndex === 0"
                            class="px-4 py-2.5 rounded-xl border border-neutral-border bg-white hover:bg-neutral-subtle text-neutral-text text-xs font-bold transition-all disabled:opacity-40 disabled:cursor-not-allowed shadow-2xs flex items-center gap-1.5">
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Sebelumnya</span>
                        </button>

                        <label class="cursor-pointer inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold border transition-all"
                            :class="flagged[currentQuestion.id] ? 'bg-amber-50 border-amber-300 text-amber-800' : 'bg-white border-neutral-border text-neutral-muted hover:bg-neutral-subtle'">
                            <input type="checkbox" x-model="flagged[currentQuestion.id]" class="rounded border-neutral-border text-amber-accent focus:ring-0">
                            <i class="fa-regular fa-bookmark text-xs"></i>
                            <span>Ragu-ragu</span>
                        </label>

                        <button type="button" @click="nextQuestion()" :disabled="currentIndex === questions.length - 1"
                            class="px-5 py-2.5 rounded-xl bg-brand-primary hover:bg-brand-navy text-white text-xs font-bold transition-all disabled:opacity-40 disabled:cursor-not-allowed shadow-xs flex items-center gap-1.5">
                            <span>Berikutnya</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right 1 Col: Question Navigator Grid -->
            <div class="space-y-4">
                <div class="bg-white border border-neutral-border rounded-2xl p-5 sm:p-6 shadow-xs sticky top-44">
                    <div class="flex items-center justify-between pb-3 border-b border-neutral-border">
                        <h2 class="text-sm font-bold text-neutral-text flex items-center gap-2">
                            <i class="fa-solid fa-table-cells text-brand-primary text-xs"></i>
                            <span>Nomor Soal</span>
                        </h2>
                        <span class="text-xs font-bold text-brand-primary">
                            <span x-text="answeredCount"></span>/<span x-text="questions.length"></span> Terjawab
                        </span>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-neutral-subtle h-2 rounded-full overflow-hidden my-3 border border-neutral-border/60">
                        <div class="bg-brand-primary h-full transition-all duration-300" :style="'width: ' + ((answeredCount / questions.length) * 100) + '%'"></div>
                    </div>

                    <!-- Numbered Grid Buttons -->
                    <div class="grid grid-cols-5 gap-2 max-h-[340px] overflow-y-auto pr-1 py-1">
                        <template x-for="(q, idx) in questions" :key="q.id">
                            <button type="button" @click="jumpTo(idx)"
                                class="h-10 rounded-xl text-xs font-bold transition-all relative flex items-center justify-center cursor-pointer shadow-2xs"
                                :class="getNavButtonClass(q.id, idx)">
                                <span x-text="idx + 1"></span>
                                <template x-if="flagged[q.id]">
                                    <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-amber-accent"></span>
                                </template>
                            </button>
                        </template>
                    </div>

                    <!-- Legend -->
                    <div class="mt-5 pt-4 border-t border-neutral-border grid grid-cols-3 gap-2 text-[10px] text-neutral-muted">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-md bg-emerald-600 shrink-0"></span>
                            <span>Dijawab</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-md bg-amber-500 shrink-0"></span>
                            <span>Ragu-ragu</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-md bg-white border border-neutral-border shrink-0"></span>
                            <span>Belum</span>
                        </div>
                    </div>

                    <!-- Submit Exam Button -->
                    <button type="button" @click="showFinishModal = true"
                        class="w-full mt-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Selesaikan Ujian Sekarang</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Finish Confirmation Modal -->
        <div x-show="showFinishModal" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
            <div @click.away="showFinishModal = false"
                class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-neutral-border text-center space-y-4 animate-scale-in">
                
                <div class="w-14 h-14 rounded-2xl bg-brand-soft text-brand-primary border border-brand-primary/20 flex items-center justify-center mx-auto shadow-xs">
                    <i class="fa-solid fa-clipboard-check text-2xl"></i>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-neutral-text">Konfirmasi Kumpulkan Ujian</h3>
                    <p class="text-xs text-neutral-muted mt-1">
                        Apakah Anda yakin ingin menyelesaikan simulasi Tryout SNBT ini? Jawaban tidak dapat diubah setelah dikumpulkan.
                    </p>
                </div>

                <div class="bg-neutral-subtle rounded-xl p-4 border border-neutral-border text-xs grid grid-cols-2 gap-3 text-left">
                    <div>
                        <span class="text-neutral-muted block text-[10px] uppercase font-semibold">Total Soal</span>
                        <span class="font-bold text-neutral-text text-sm font-mono" x-text="questions.length"></span>
                    </div>
                    <div>
                        <span class="text-neutral-muted block text-[10px] uppercase font-semibold">Soal Dijawab</span>
                        <span class="font-bold text-emerald-700 text-sm font-mono" x-text="answeredCount"></span>
                    </div>
                    <div>
                        <span class="text-neutral-muted block text-[10px] uppercase font-semibold">Belum Dijawab</span>
                        <span class="font-bold text-red-accent text-sm font-mono" x-text="questions.length - answeredCount"></span>
                    </div>
                    <div>
                        <span class="text-neutral-muted block text-[10px] uppercase font-semibold">Ragu-ragu</span>
                        <span class="font-bold text-amber-accent text-sm font-mono" x-text="flaggedCount"></span>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="button" @click="showFinishModal = false"
                        class="flex-1 py-2.5 rounded-xl border border-neutral-border bg-white hover:bg-neutral-subtle text-neutral-text text-xs font-bold transition-all shadow-2xs">
                        Periksa Kembali
                    </button>
                    <button type="button" @click="submitExam()" :disabled="isSubmitting"
                        class="flex-1 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-1.5">
                        <span x-show="!isSubmitting">Kumpulkan Sekarang</span>
                        <span x-show="isSubmitting"><i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cbtExam', (config) => ({
                durationSeconds: config.durationMinutes * 60,
                timeRemaining: config.durationMinutes * 60,
                questions: config.questions || [],
                submitUrl: config.submitUrl,
                csrfToken: config.csrfToken,
                currentIndex: 0,
                answers: {},
                flagged: {},
                showFinishModal: false,
                isSubmitting: false,
                timerInterval: null,

                init() {
                    // Load any saved progress from localStorage
                    const storageKey = 'altura_cbt_' + '{{ $tryout->id }}';
                    const saved = localStorage.getItem(storageKey);
                    if (saved) {
                        try {
                            const parsed = JSON.parse(saved);
                            this.answers = parsed.answers || {};
                            this.flagged = parsed.flagged || {};
                        } catch (e) {}
                    }

                    // Start live countdown timer
                    this.timerInterval = setInterval(() => {
                        if (this.timeRemaining > 0) {
                            this.timeRemaining--;
                        } else {
                            clearInterval(this.timerInterval);
                            alert('Waktu ujian telah berakhir! Sistem akan mengumpulkan jawaban Anda secara otomatis.');
                            this.submitExam();
                        }
                    }, 1000);
                },

                get currentQuestion() {
                    return this.questions[this.currentIndex] || {};
                },

                get formattedTime() {
                    const hours = Math.floor(this.timeRemaining / 3600);
                    const minutes = Math.floor((this.timeRemaining % 3600) / 60);
                    const seconds = this.timeRemaining % 60;
                    
                    const pad = (n) => n.toString().padStart(2, '0');
                    if (hours > 0) {
                        return `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
                    }
                    return `${pad(minutes)}:${pad(seconds)}`;
                },

                get answeredCount() {
                    return Object.keys(this.answers).filter(k => this.answers[k] !== undefined && this.answers[k] !== null && this.answers[k] !== '').length;
                },

                get flaggedCount() {
                    return Object.keys(this.flagged).filter(k => this.flagged[k]).length;
                },

                saveAnswer(questionId, optionKey) {
                    this.answers[questionId] = optionKey;
                    const storageKey = 'altura_cbt_' + '{{ $tryout->id }}';
                    localStorage.setItem(storageKey, JSON.stringify({
                        answers: this.answers,
                        flagged: this.flagged
                    }));
                },

                nextQuestion() {
                    if (this.currentIndex < this.questions.length - 1) {
                        this.currentIndex++;
                    }
                },

                prevQuestion() {
                    if (this.currentIndex > 0) {
                        this.currentIndex--;
                    }
                },

                jumpTo(idx) {
                    this.currentIndex = idx;
                },

                getNavButtonClass(questionId, idx) {
                    const isCurrent = this.currentIndex === idx;
                    const isAnswered = this.answers[questionId] !== undefined && this.answers[questionId] !== null && this.answers[questionId] !== '';
                    const isFlagged = this.flagged[questionId];

                    if (isCurrent) {
                        return 'ring-2 ring-brand-primary bg-brand-primary text-white font-extrabold';
                    }
                    if (isFlagged) {
                        return 'bg-amber-500 text-white font-bold';
                    }
                    if (isAnswered) {
                        return 'bg-emerald-600 text-white font-bold';
                    }
                    return 'bg-white text-neutral-muted border border-neutral-border hover:bg-neutral-subtle font-medium';
                },

                async submitExam() {
                    if (this.isSubmitting) return;
                    this.isSubmitting = true;
                    clearInterval(this.timerInterval);

                    try {
                        const response = await fetch(this.submitUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken
                            },
                            body: JSON.stringify({
                                answers: this.answers
                            })
                        });

                        const result = await response.json();
                        if (response.ok && result.redirect) {
                            localStorage.removeItem('altura_cbt_' + '{{ $tryout->id }}');
                            window.location.href = result.redirect;
                        } else {
                            alert(result.message || 'Gagal menyimpan hasil ujian.');
                            this.isSubmitting = false;
                        }
                    } catch (e) {
                        console.error(e);
                        alert('Terjadi kesalahan koneksi saat mengumpulkan ujian.');
                        this.isSubmitting = false;
                    }
                }
            }));
        });
    </script>
</x-layouts.app>
