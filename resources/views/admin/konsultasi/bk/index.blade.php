<x-layouts.app>
    <x-slot:title>Bimbingan Konseling (BK Kak Nisa) — Admin</x-slot:title>

    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-neutral-border shadow-xs p-6 sm:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 mb-3">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Modul Konseling Pribadi & Karir (Guru BK - Kak Nisa)
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-neutral-text">Bimbingan & Konseling (BK)</h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Kelola slot sesi curhat, motivasi belajar, dan peminatan jurusan universitas untuk Siswa dan Orang Tua bersama Guru BK.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Slots List (2 Cols) -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white border border-neutral-border rounded-xl p-4 shadow-xs">
                    <form action="{{ route('admin.konseling-bk.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                        <select name="counselor_id" class="flex-1 px-3 py-2 rounded-lg bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                            <option value="">-- Semua Guru BK / Konselor --</option>
                            @foreach ($counselors as $c)
                                <option value="{{ $c->id }}" {{ (string)$counselorId === (string)$c->id ? 'selected' : '' }}>
                                    {{ $c->name }} ({{ $c->email }})
                                </option>
                            @endforeach
                        </select>

                        <div class="flex gap-2">
                            <button type="submit" class="px-4 py-2 bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold rounded-lg border border-neutral-border transition-colors">
                                Filter
                            </button>
                            @if($counselorId)
                                <a href="{{ route('admin.konseling-bk.index') }}" class="px-3 py-2 bg-neutral-subtle text-neutral-muted text-xs rounded-lg flex items-center border border-neutral-border">
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
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Konselor: {{ $slot->counselor?->name ?? 'Kak Nisa' }}
                                        </span>
                                        <span class="text-xs text-neutral-muted font-mono">
                                            <i class="fa-regular fa-clock mr-1"></i>{{ $slot->date_time?->format('d M Y, H:i') }} WIB
                                        </span>
                                    </div>
                                    <div class="text-xs text-neutral-muted flex items-center gap-2 mt-2">
                                        <span><i class="fa-solid fa-location-dot mr-1 text-neutral-muted"></i>{{ $slot->room_or_link }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-mono font-bold bg-neutral-subtle text-neutral-text border border-neutral-border">
                                        Sisa: {{ $slot->remaining_quota }} / {{ $slot->max_quota }} Kuota
                                    </span>
                                    <form action="{{ route('admin.konseling-bk.destroy', $slot) }}" method="POST" onsubmit="return confirm('Hapus slot sesi bimbingan konseling ini?')">
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
                                        Reservasi Konseling ({{ $slot->bookings->count() }}):
                                    </div>
                                    <div class="space-y-1.5">
                                        @foreach ($slot->bookings as $b)
                                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-surface border border-neutral-border text-xs">
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-bold text-neutral-text">
                                                            {{ $b->booking_type === 'orang_tua' ? ($b->parent?->user?->name ?? 'Orang Tua') : ($b->student?->user?->name ?? 'Siswa') }}
                                                        </span>
                                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold {{ $b->booking_type === 'orang_tua' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                                            {{ $b->booking_type === 'orang_tua' ? 'Wali Murid' : 'Siswa' }}
                                                        </span>
                                                    </div>
                                                    @if ($b->notes)
                                                        <p class="text-[11px] text-neutral-muted mt-1 italic">"{{ $b->notes }}"</p>
                                                    @endif
                                                </div>

                                                <form action="{{ route('admin.konseling-bk.booking-status', $b->id) }}" method="POST" class="flex items-center gap-2">
                                                    @csrf
                                                    <select name="status" onchange="this.form.submit()"
                                                        class="px-2 py-1 rounded-lg text-[11px] font-semibold bg-white border border-neutral-border text-neutral-text focus:outline-none focus:border-brand-primary">
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
                            Belum ada slot konseling BK yang dibuka.
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
                        <i class="fa-solid fa-plus text-emerald-600"></i> Buka Slot Konseling BK
                    </h3>
                    <p class="text-xs text-neutral-muted mb-4">Buat jadwal bimbingan pribadi siswa atau konsultasi wali murid bersama Guru BK.</p>

                    <form method="POST" action="{{ route('admin.konseling-bk.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-neutral-text mb-1">Guru BK / Konselor <span class="text-red-accent">*</span></label>
                            <select name="counselor_user_id" required class="w-full bg-surface border border-neutral-border rounded-lg p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                                <option value="">-- Pilih Guru BK --</option>
                                @foreach ($counselors as $c)
                                    <option value="{{ $c->id }}" {{ old('counselor_user_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }} ({{ $c->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-text mb-1">Waktu & Tanggal Konseling <span class="text-red-accent">*</span></label>
                            <input type="datetime-local" name="date_time" required value="{{ now()->addDays(1)->setHour(14)->setMinute(0)->format('Y-m-d\TH:i') }}" class="w-full bg-surface border border-neutral-border rounded-lg p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-text mb-1">Ruang Konseling / Link Meet <span class="text-red-accent">*</span></label>
                            <input type="text" name="room_or_link" required placeholder="Contoh: Ruang Konseling BK atau Google Meet" class="w-full bg-surface border border-neutral-border rounded-lg p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-text mb-1">Maksimal Kuota Sesi <span class="text-red-accent">*</span></label>
                            <input type="number" name="max_quota" required min="1" max="5" value="1" class="w-full bg-surface border border-neutral-border rounded-lg p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                            <span class="text-[10px] text-neutral-muted mt-1 block">Default 1 orang untuk sesi privat 1-on-1.</span>
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition-all mt-2">
                            Simpan Slot Konseling BK
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
