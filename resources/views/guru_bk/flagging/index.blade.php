<x-layouts.app>
    <x-slot:title>Pelanggaran Siswa Otomatis & Alert Konseling</x-slot:title>

    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="rounded-2xl bg-white border border-neutral-border p-6 sm:p-8 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20 mb-3">
                        <span class="w-2 h-2 rounded-full bg-brand-primary"></span>
                        Sistem Peringatan Siswa
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-neutral-text flex items-center gap-2">
                        <span>Pelanggaran Siswa Berisiko</span>
                        <i class="fa-solid fa-triangle-exclamation text-red-accent text-xl"></i>
                    </h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Daftar indikator keterlambatan berturut-turut, ketidakhadiran kritis, dan ketertinggalan modul untuk tindak lanjut Guru BK.
                    </p>
                </div>
            </div>
        </div>

        <!-- Filter & KPI Counts -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-neutral-border p-4 rounded-2xl shadow-sm">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('bk.flagging.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ empty($status) ? 'bg-brand-primary text-white shadow-sm' : 'bg-neutral-subtle text-neutral-muted hover:text-neutral-text' }}">
                    Semua ({{ $openCount + $inProgressCount + $resolvedCount }})
                </a>
                <a href="{{ route('bk.flagging.index', ['status' => 'open']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $status === 'open' ? 'bg-red-accent text-white shadow-sm' : 'bg-neutral-subtle text-neutral-muted hover:text-neutral-text' }}">
                    <i class="fa-solid fa-triangle-exclamation mr-1.5 text-xs"></i> Belum Ditangani ({{ $openCount }})
                </a>
                <a href="{{ route('bk.flagging.index', ['status' => 'in_progress']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $status === 'in_progress' ? 'bg-amber-accent text-white shadow-sm' : 'bg-neutral-subtle text-neutral-muted hover:text-neutral-text' }}">
                    <i class="fa-solid fa-hourglass-half mr-1.5 text-xs"></i> Dalam Penanganan ({{ $inProgressCount }})
                </a>
                <a href="{{ route('bk.flagging.index', ['status' => 'resolved']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $status === 'resolved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-neutral-subtle text-neutral-muted hover:text-neutral-text' }}">
                    <i class="fa-solid fa-check mr-1.5 text-xs"></i> Selesai ({{ $resolvedCount }})
                </a>
            </div>

            <!-- Type Selector -->
            <form method="GET" action="{{ route('bk.flagging.index') }}" class="flex items-center gap-2">
                @if ($status)
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif
                <select name="flag_type" onchange="this.form.submit()" class="bg-white border border-neutral-border rounded-lg px-3 py-1.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                    <option value="">Semua Tipe Indikator</option>
                    <option value="late_series" {{ $flagType === 'late_series' ? 'selected' : '' }}>Terlambat Seri (≥ 3x)</option>
                    <option value="absence_critical" {{ $flagType === 'absence_critical' ? 'selected' : '' }}>Ketidakhadiran Kritis</option>
                    <option value="vocab_behind" {{ $flagType === 'vocab_behind' ? 'selected' : '' }}>Vocab Tertinggal</option>
                </select>
            </form>
        </div>

        <!-- Flagging Items List -->
        @if ($flags->isNotEmpty())
            <div class="space-y-4">
                @foreach ($flags as $flag)
                    <div class="bg-white border border-neutral-border rounded-2xl p-6 transition-all hover:shadow-md shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                            <div class="space-y-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    @if ($flag->flag_type === 'late_series')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-accent-soft text-amber-accent-text border border-amber-accent/30">
                                            <i class="fa-solid fa-stopwatch mr-1.5"></i> Terlambat Berturut-turut
                                        </span>
                                    @elseif ($flag->flag_type === 'absence_critical')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-accent-soft text-red-accent-text border border-red-accent/30">
                                            <span class="inline-block w-2 h-2 rounded-full bg-red-accent mr-1.5 align-middle"></span> Ketidakhadiran Kritis
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-brand-soft text-brand-primary border border-brand-primary/20">
                                            <i class="fa-solid fa-book-open mr-1.5 text-brand-primary"></i> Modul Vocab Tertinggal
                                        </span>
                                    @endif

                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold {{ $flag->status === 'open' ? 'bg-red-accent-soft text-red-accent-text border border-red-accent/20' : ($flag->status === 'in_progress' ? 'bg-amber-accent-soft text-amber-accent-text border border-amber-accent/20' : 'bg-emerald-50 text-emerald-700 border border-emerald-200') }}">
                                        {{ $flag->status === 'open' ? 'Terbuka (Perlu Tindakan)' : ($flag->status === 'in_progress' ? 'Sedang Ditangani' : 'Terselesaikan') }}
                                    </span>
                                    <span class="text-xs text-neutral-muted">• {{ $flag->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                                </div>

                                <h3 class="text-lg font-bold text-neutral-text">{{ $flag->student->user->name }}</h3>
                                <p class="text-sm text-neutral-muted font-medium">{{ $flag->reason }}</p>

                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs text-neutral-muted pt-2">
                                    <div>NISN: <span class="font-mono text-neutral-text">{{ $flag->student->nisn }}</span></div>
                                    <div>Kelas: <span class="text-neutral-text">{{ $flag->student->academicClass?->name }}</span></div>
                                    <div>Mentor: <span class="text-brand-primary font-medium">{{ $flag->student->mentor?->user?->name }}</span></div>
                                    <div>WA Ortu: <span class="font-mono text-emerald-700">{{ $flag->student->parent_phone_number }}</span></div>
                                </div>

                                @if ($flag->status === 'resolved' && $flag->resolution_notes)
                                    <div class="mt-3 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs">
                                        <div class="font-semibold text-emerald-800 flex items-center gap-1.5 mb-1">
                                            <i class="fa-solid fa-check"></i>
                                            <span>Diselesaikan oleh {{ $flag->resolvedBy?->name ?? 'Guru BK' }}</span>
                                            <span class="text-neutral-muted font-normal">({{ $flag->resolved_at?->translatedFormat('d M Y, H:i') }} WIB)</span>
                                        </div>
                                        <p class="text-emerald-900 italic">"{{ $flag->resolution_notes }}"</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Resolution Form / Action Box -->
                            <div class="shrink-0 w-full md:w-80 bg-neutral-subtle/60 border border-neutral-border p-4 rounded-xl">
                                <h4 class="text-xs font-bold text-neutral-muted uppercase tracking-wider mb-2">Tindakan Konseling Guru BK</h4>
                                <form method="POST" action="{{ route('bk.flagging.resolve', $flag->id) }}" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="block text-[11px] font-semibold text-neutral-muted mb-1">Perbarui Status</label>
                                        <select name="status" class="w-full bg-white border border-neutral-border rounded-lg px-2.5 py-1.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                                            <option value="in_progress" {{ $flag->status === 'in_progress' ? 'selected' : '' }}>Tandai Dalam Penanganan</option>
                                            <option value="resolved" {{ $flag->status === 'resolved' ? 'selected' : '' }}>Selesaikan Kasus</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-semibold text-neutral-muted mb-1">Catatan Intervensi / Solusi</label>
                                        <textarea name="resolution_notes" rows="2" required placeholder="Contoh: Sudah ditelepon wali murid, ananda berjanji masuk tepat waktu..." class="w-full bg-white border border-neutral-border rounded-lg p-2 text-xs text-neutral-text placeholder-neutral-muted focus:outline-none focus:border-brand-primary">{{ $flag->resolution_notes }}</textarea>
                                    </div>

                                    <button type="submit" class="w-full py-2 rounded-lg bg-brand-primary hover:bg-brand-navy text-white text-xs font-semibold shadow-sm transition-all cursor-pointer">
                                        Simpan Tindakan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="mt-4">
                    {{ $flags->links() }}
                </div>
            </div>
        @else
            <div class="py-16 text-center text-neutral-muted bg-white rounded-2xl border border-neutral-border shadow-sm">
                <i class="fa-solid fa-circle-check text-4xl text-emerald-600 block mb-2"></i>
                <h3 class="text-base font-bold text-neutral-text">Tidak Ada Data Flagging</h3>
                <p class="text-xs text-neutral-muted mt-1">Sesuai dengan filter yang Anda pilih saat ini.</p>
            </div>
        @endif
    </div>
</x-layouts.app>
