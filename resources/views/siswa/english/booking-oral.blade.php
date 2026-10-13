<x-layouts.app>
    <x-slot:title>Pendaftaran Ujian Setoran Lisan (Speaking)</x-slot:title>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-text flex items-center gap-2">
                    <i class="fa-solid fa-calendar-days text-brand-primary"></i>
                    <span>Pendaftaran Ujian Setoran Lisan (Oral Exam)</span>
                </h1>
                <p class="text-xs text-neutral-muted mt-1">
                    Ujian lisan bahasa Inggris dinilai langsung secara tatap muka oleh Guru Mentor resmi Anda.
                </p>
            </div>
            <a href="{{ route('siswa.english.tasks.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-neutral-text bg-white border border-neutral-border hover:bg-neutral-subtle transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Lihat Naskah Tugas</span>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 1 Col: Booking Form (Locked to Mentor) -->
            <div class="space-y-4">
                <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-sm space-y-4">
                    <!-- Locked Mentor Banner -->
                    <div class="p-3.5 bg-brand-soft border border-brand-primary/20 rounded-xl text-xs space-y-1">
                        <span class="text-[10px] uppercase font-bold text-brand-primary block tracking-wider">
                            <i class="fa-solid fa-lock mr-1.5 text-brand-primary"></i> Guru Mentor Resmi Anda:
                        </span>
                        <div class="text-sm font-bold text-neutral-text">{{ $mentor?->user?->name ?? 'Belum Ditugaskan' }}</div>
                        <p class="text-[11px] text-neutral-muted">
                            Sesuai aturan Alto, ujian lisan dinilai secara konsisten oleh mentor resmi yang sama.
                        </p>
                    </div>

                    <form action="{{ route('siswa.english.speaking.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-neutral-muted uppercase tracking-wider mb-1">
                                Pilih Naskah Setoran <span class="text-red-accent">*</span>
                            </label>
                            <select name="speaking_task_id" required
                                class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary focus:ring-1 focus:ring-brand-primary">
                                <option value="">-- Pilih Naskah --</option>
                                @foreach ($tasks as $t)
                                    <option value="{{ $t->id }}">
                                        [{{ strtoupper($t->type) }}] {{ $t->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-muted uppercase tracking-wider mb-1">
                                Tanggal Ujian <span class="text-red-accent">*</span>
                            </label>
                            <input type="date" name="booking_date" min="{{ date('Y-m-d') }}" required
                                class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary focus:ring-1 focus:ring-brand-primary">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-muted uppercase tracking-wider mb-1">
                                Slot Waktu Sesi <span class="text-red-accent">*</span>
                            </label>
                            <select name="time_slot" required
                                class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary focus:ring-1 focus:ring-brand-primary">
                                <option value="15:30 - 16:00">15:30 - 16:00 WIB</option>
                                <option value="16:00 - 16:30">16:00 - 16:30 WIB</option>
                                <option value="16:30 - 17:00">16:30 - 17:00 WIB</option>
                                <option value="17:00 - 17:30">17:00 - 17:30 WIB</option>
                            </select>
                        </div>

                        <button type="submit"
                            class="w-full py-2.5 bg-brand-primary hover:bg-brand-navy text-white text-xs font-bold rounded-xl shadow-sm transition-all cursor-pointer">
                            Daftarkan Jadwal Ujian
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right 2 Cols: My Speaking Bookings & Scorecard -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-neutral-border pb-3">
                        <h2 class="text-sm font-bold text-neutral-text flex items-center gap-2">
                            <span>Riwayat & Jadwal Setoran Lisan Saya</span>
                        </h2>
                        <span class="text-xs font-mono text-neutral-muted">{{ $myBookings->count() }} Pendaftaran</span>
                    </div>

                    <div class="space-y-3">
                        @forelse ($myBookings as $b)
                            <div class="bg-neutral-subtle/50 border border-neutral-border rounded-xl p-4 space-y-3">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $b->speakingTask?->type === 'storytelling' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-brand-soft text-brand-primary border border-brand-primary/20' }}">
                                                {{ $b->speakingTask?->type }}
                                            </span>
                                            <h3 class="text-sm font-bold text-neutral-text">{{ $b->speakingTask?->title }}</h3>
                                        </div>
                                        <div class="text-xs text-neutral-muted mt-1">
                                            Mentor: <strong class="text-brand-primary">{{ $b->mentor?->user?->name }}</strong> • 
                                            Jadwal: <span class="text-neutral-text font-mono">{{ $b->booking_date->format('d M Y') }} ({{ $b->time_slot }})</span>
                                        </div>
                                    </div>

                                    <div>
                                        @if ($b->status === 'completed')
                                            <div class="text-right">
                                                <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    SKOR: {{ $b->grade }} / 100
                                                </span>
                                            </div>
                                        @elseif ($b->status === 'pending')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-accent-soft text-amber-accent-text border border-amber-accent/20">
                                                <span class="inline-block w-2 h-2 rounded-full bg-amber-accent mr-1.5 align-middle"></span> Menunggu Ujian
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-neutral-subtle text-neutral-muted border border-neutral-border">
                                                Dibatalkan
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Completed Rubric Breakdown -->
                                @if ($b->status === 'completed')
                                    <div class="pt-3 border-t border-neutral-border grid grid-cols-2 sm:grid-cols-4 gap-2 text-center text-xs">
                                        <div class="bg-white p-2 rounded-xl border border-neutral-border">
                                            <span class="text-[10px] text-neutral-muted block uppercase">Pronunciation</span>
                                            <strong class="text-neutral-text">{{ $b->rubric_pronunciation }} / 25</strong>
                                        </div>
                                        <div class="bg-white p-2 rounded-xl border border-neutral-border">
                                            <span class="text-[10px] text-neutral-muted block uppercase">Fluency</span>
                                            <strong class="text-neutral-text">{{ $b->rubric_fluency }} / 25</strong>
                                        </div>
                                        <div class="bg-white p-2 rounded-xl border border-neutral-border">
                                            <span class="text-[10px] text-neutral-muted block uppercase">Vocabulary</span>
                                            <strong class="text-neutral-text">{{ $b->rubric_vocabulary }} / 25</strong>
                                        </div>
                                        <div class="bg-white p-2 rounded-xl border border-neutral-border">
                                            <span class="text-[10px] text-neutral-muted block uppercase">Comprehension</span>
                                            <strong class="text-neutral-text">{{ $b->rubric_comprehension }} / 25</strong>
                                        </div>
                                    </div>
                                    @if ($b->rubric_notes)
                                        <p class="text-xs text-neutral-muted italic bg-white p-2.5 rounded-xl border border-neutral-border">
                                            Catatan Mentor: "{{ $b->rubric_notes }}"
                                        </p>
                                    @endif
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8 text-neutral-muted text-xs">
                                Anda belum pernah mendaftarkan jadwal ujian lisan.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
