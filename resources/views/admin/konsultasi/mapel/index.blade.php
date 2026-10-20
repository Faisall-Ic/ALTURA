<x-layouts.app>
    <x-slot:title>Konsultasi & Tentoring Mata Pelajaran — Admin</x-slot:title>

    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-neutral-border shadow-xs p-6 sm:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20 mb-3">
                        <span class="w-2 h-2 rounded-full bg-brand-primary animate-pulse"></span>
                        Modul Tentoring Akademik Guru Mata Pelajaran
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-neutral-text">Konsultasi Mata Pelajaran (Tentoring)</h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Kelola slot sesi tentoring mapel bersama guru pengajar/mentor (terpisah secara modular dari Bimbingan Konseling BK).
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Slots List (2 Cols) -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white border border-neutral-border rounded-xl p-4 shadow-xs">
                    <form action="{{ route('admin.konsultasi-mapel.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                        <select name="subject_id" class="flex-1 px-3 py-2 rounded-lg bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                            <option value="">-- Semua Mata Pelajaran --</option>
                            @foreach ($subjects as $s)
                                <option value="{{ $s->id }}" {{ (string)$subjectId === (string)$s->id ? 'selected' : '' }}>
                                    {{ $s->name }} ({{ $s->code }})
                                </option>
                            @endforeach
                        </select>

                        <select name="teacher_id" class="flex-1 px-3 py-2 rounded-lg bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                            <option value="">-- Semua Guru Pengampu --</option>
                            @foreach ($teachers as $t)
                                <option value="{{ $t->id }}" {{ (string)$teacherId === (string)$t->id ? 'selected' : '' }}>
                                    {{ $t->user?->name }}
                                </option>
                            @endforeach
                        </select>

                        <div class="flex gap-2">
                            <button type="submit" class="px-4 py-2 bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold rounded-lg border border-neutral-border transition-colors">
                                Filter
                            </button>
                            @if($subjectId || $teacherId)
                                <a href="{{ route('admin.konsultasi-mapel.index') }}" class="px-3 py-2 bg-neutral-subtle text-neutral-muted text-xs rounded-lg flex items-center border border-neutral-border">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="space-y-4">
                    @forelse ($slots as $slot)
                        <div class="bg-white border border-neutral-border rounded-2xl p-5 shadow-xs space-y-3">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-brand-soft text-brand-primary border border-brand-primary/20">
                                            {{ $slot->subject?->name }} ({{ $slot->subject?->code }})
                                        </span>
                                        <span class="text-xs text-neutral-muted font-mono">
                                            <i class="fa-regular fa-clock mr-1"></i>{{ $slot->date_time?->format('d M Y, H:i') }} WIB
                                        </span>
                                    </div>
                                    <h3 class="text-sm font-bold text-neutral-text mt-1.5">{{ $slot->topic }}</h3>
                                    <div class="text-xs text-neutral-muted flex items-center gap-2 mt-1">
                                        <span><i class="fa-solid fa-user-tie mr-1 text-brand-primary"></i>{{ $slot->teacher?->user?->name ?? 'Tentor' }}</span>
                                        <span>&bull;</span>
                                        <span><i class="fa-solid fa-location-dot mr-1 text-neutral-muted"></i>{{ $slot->room_or_link }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-mono font-bold bg-neutral-subtle text-neutral-text border border-neutral-border">
                                        Sisa: {{ $slot->remaining_quota }} / {{ $slot->max_quota }} Kuota
                                    </span>
                                    <form action="{{ route('admin.konsultasi-mapel.destroy', $slot) }}" method="POST" onsubmit="return confirm('Hapus slot sesi tentoring ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-neutral-muted hover:text-red-accent rounded-lg transition-colors cursor-pointer">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Bookings List in Slot -->
                            @if ($slot->bookings->count() > 0)
                                <div class="pt-2 border-t border-neutral-border/60">
                                    <div class="text-[11px] font-semibold text-neutral-muted uppercase mb-2">
                                        Siswa Terdaftar ({{ $slot->bookings->count() }}):
                                    </div>
                                    <div class="space-y-1.5">
                                        @foreach ($slot->bookings as $b)
                                            <div class="flex items-center justify-between p-2 rounded-lg bg-surface border border-neutral-border text-xs">
                                                <div>
                                                    <span class="font-semibold text-neutral-text">{{ $b->student?->user?->name ?? 'Siswa' }}</span>
                                                    <span class="text-[11px] text-neutral-muted font-mono ml-1.5">(NISN: {{ $b->student?->nisn ?? '-' }})</span>
                                                </div>
                                                <form action="{{ route('admin.konsultasi-mapel.booking-status', $b->id) }}" method="POST" class="flex items-center gap-2">
                                                    @csrf
                                                    <select name="status" onchange="this.form.submit()"
                                                        class="px-2 py-0.5 rounded text-[11px] font-semibold bg-white border border-neutral-border text-neutral-text focus:outline-none focus:border-brand-primary">
                                                        <option value="booked" {{ $b->status == 'booked' ? 'selected' : '' }}>Booked</option>
                                                        <option value="approved" {{ $b->status == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                                        <option value="completed" {{ $b->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                                        <option value="cancelled" {{ $b->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                                    </select>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="bg-white border border-neutral-border rounded-2xl p-8 text-center text-xs text-neutral-muted">
                            Belum ada slot tentoring mata pelajaran yang dibuka.
                        </div>
                    @endforelse
                </div>

                @if ($slots->hasPages())
                    <div class="bg-white border border-neutral-border rounded-xl p-4 shadow-xs">
                        {{ $slots->links() }}
                    </div>
                @endif
            </div>

            <!-- Create Form (1 Col) -->
            <div class="space-y-6">
                <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs">
                    <h3 class="text-base font-bold text-neutral-text mb-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-plus text-brand-primary"></i> Buka Slot Tentoring Mapel
                    </h3>
                    <p class="text-xs text-neutral-muted mb-4">Buat sesi tentoring akademik baru per mata pelajaran.</p>

                    <form method="POST" action="{{ route('admin.konsultasi-mapel.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-neutral-text mb-1">Mata Pelajaran <span class="text-red-accent">*</span></label>
                            <select name="subject_id" required class="w-full bg-surface border border-neutral-border rounded-lg p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                                <option value="">-- Pilih Mapel --</option>
                                @foreach ($subjects as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-text mb-1">Guru Pengajar / Tentor <span class="text-red-accent">*</span></label>
                            <select name="teacher_id" required class="w-full bg-surface border border-neutral-border rounded-lg p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                                <option value="">-- Pilih Guru --</option>
                                @foreach ($teachers as $t)
                                    <option value="{{ $t->id }}">{{ $t->user?->name }} ({{ $t->specialization_subject }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-text mb-1">Topik Bahasan Sesi <span class="text-red-accent">*</span></label>
                            <input type="text" name="topic" required placeholder="Contoh: Pembahasan Soal Penalaran Kuantitatif Bab 2" class="w-full bg-surface border border-neutral-border rounded-lg p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-text mb-1">Waktu & Tanggal Sesi <span class="text-red-accent">*</span></label>
                            <input type="datetime-local" name="date_time" required value="{{ now()->addDays(1)->format('Y-m-d\TH:i') }}" class="w-full bg-surface border border-neutral-border rounded-lg p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-text mb-1">Ruang Kelas / Link Online <span class="text-red-accent">*</span></label>
                            <input type="text" name="room_or_link" required placeholder="Contoh: Ruang Belajar 3 atau Google Meet Link" class="w-full bg-surface border border-neutral-border rounded-lg p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-text mb-1">Maksimal Kuota Siswa <span class="text-red-accent">*</span></label>
                            <input type="number" name="max_quota" required min="1" max="20" value="5" class="w-full bg-surface border border-neutral-border rounded-lg p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl bg-brand-primary hover:bg-brand-navy text-white font-bold text-xs shadow-xs transition-all mt-2">
                            Simpan Slot Tentoring
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
