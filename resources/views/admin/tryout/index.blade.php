<x-layouts.app>
    <x-slot:title>Manajemen Paket Tryout SNBT — Admin</x-slot:title>

    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-neutral-border shadow-xs p-6 sm:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20 mb-3">
                        <span class="w-2 h-2 rounded-full bg-brand-primary animate-pulse"></span>
                        Simulasi UTBK &amp; Subtes SNBT
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-neutral-text">Manajemen Paket Tryout SNBT <i class="fa-solid fa-bullseye ml-1 text-amber-500"></i></h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Kelola paket tryout akbar, subtes ujian (TPS, Literasi Indo/Inggris, PM), dan jadwal pengerjaan siswa.
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold border border-neutral-border transition-all">
                        <i class="fa-solid fa-arrow-left text-[11px]"></i> Dashboard Admin
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Table of Tryouts (2 Cols) -->
            <div class="lg:col-span-2 bg-white border border-neutral-border rounded-2xl p-6 shadow-xs">
                <h2 class="text-lg font-bold text-neutral-text mb-4">Daftar Paket Tryout & Subtes</h2>

                @if ($tryouts->isNotEmpty())
                    <div class="space-y-4">
                        @foreach ($tryouts as $to)
                            <div class="p-4 rounded-xl border border-neutral-border bg-surface hover:border-brand-primary/40 transition-all">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2 mb-2">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-brand-soft text-brand-primary border border-brand-primary/20 font-mono">
                                                {{ $to->batch_name ?? 'Batch' }}
                                            </span>
                                            <h3 class="font-bold text-neutral-text text-sm">{{ $to->title }}</h3>
                                        </div>
                                        <p class="text-xs text-neutral-muted mt-1 font-mono">
                                            <i class="fa-regular fa-calendar text-[11px] mr-1"></i>{{ $to->date_start->format('d M Y') }} — {{ $to->date_end->format('d M Y') }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        @if ($to->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-neutral-subtle text-neutral-muted border border-neutral-border">
                                                Nonaktif
                                            </span>
                                        @endif

                                        <form method="POST" action="{{ route('admin.tryout.destroy', $to->id) }}" onsubmit="return confirm('Hapus paket tryout ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-neutral-muted hover:text-red-accent rounded-lg transition-colors cursor-pointer">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Subtests display -->
                                <div class="mt-3 pt-3 border-t border-neutral-border/60">
                                    <div class="text-[11px] font-semibold text-neutral-muted uppercase mb-1.5">
                                        Subtes Terdaftar ({{ $to->subtests->count() }}):
                                    </div>
                                    <div class="flex flex-wrap gap-1.5">
                                        @forelse ($to->subtests as $subtest)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-medium bg-neutral-subtle text-neutral-text border border-neutral-border">
                                                <span>{{ $subtest->name }}</span>
                                                <span class="text-[10px] text-neutral-muted">({{ $subtest->question_count }} soal &bull; {{ $subtest->duration_minutes }}m)</span>
                                            </span>
                                        @empty
                                            <span class="text-[11px] text-neutral-muted italic">Belum ada subtes</span>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="mt-3 pt-3 border-t border-neutral-border/60 flex flex-wrap items-center justify-between gap-2 text-xs">
                                    <div class="flex items-center gap-2">
                                        <span class="text-neutral-muted font-mono">Peserta: <strong class="text-brand-primary">{{ $to->tryout_scores_count }} Siswa</strong></span>
                                        <span class="text-neutral-muted">&bull;</span>
                                        <span class="text-neutral-muted font-mono">Soal: <strong class="text-neutral-text">{{ $to->questions_count }} Butir</strong></span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.tryouts.questions.index', $to->id) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-brand-soft hover:bg-brand-primary hover:text-white text-brand-primary text-xs font-bold border border-brand-primary/20 shadow-2xs transition-all">
                                            <i class="fa-solid fa-list-check text-xs"></i>
                                            <span>Kelola Soal</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $tryouts->links() }}
                    </div>
                @else
                    <p class="text-xs text-neutral-muted py-8 text-center">Belum ada paket tryout terdaftar.</p>
                @endif
            </div>

            <!-- Create Form (1 Col) -->
            <div class="space-y-6">
                <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs">
                    <h3 class="text-base font-bold text-neutral-text mb-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-plus text-brand-primary"></i> Buat Paket Tryout Baru
                    </h3>
                    <p class="text-xs text-neutral-muted mb-4">Secara otomatis menyertakan 4 subtes standar UTBK.</p>

                    <form method="POST" action="{{ route('admin.tryout.store') }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-neutral-text mb-1">Judul Paket Ujian <span class="text-red-accent">*</span></label>
                            <input type="text" name="title" required placeholder="Contoh: Tryout Akbar UTBK-SNBT Seri 1" class="w-full bg-surface border border-neutral-border rounded-lg p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-text mb-1">Nama Batch / Gelombang</label>
                            <input type="text" name="batch_name" placeholder="Contoh: Batch SNBT 2026 Gelombang 1" class="w-full bg-surface border border-neutral-border rounded-lg p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-text mb-1">Tanggal Mulai <span class="text-red-accent">*</span></label>
                            <input type="date" name="date_start" required value="{{ now()->toDateString() }}" class="w-full bg-surface border border-neutral-border rounded-lg p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-text mb-1">Tanggal Berakhir <span class="text-red-accent">*</span></label>
                            <input type="date" name="date_end" required value="{{ now()->addDays(7)->toDateString() }}" class="w-full bg-surface border border-neutral-border rounded-lg p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            <input type="checkbox" name="is_active" id="is_active" value="1" checked class="rounded border-neutral-border text-brand-primary focus:ring-brand-primary">
                            <label for="is_active" class="text-xs font-medium text-neutral-text">Aktifkan untuk Siswa</label>
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl bg-brand-primary hover:bg-brand-navy text-white font-bold text-xs shadow-xs transition-all mt-2">
                            Simpan Paket Tryout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
