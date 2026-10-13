<x-layouts.app>
    <x-slot:title>Manajemen Sesi Konsultasi BK & Wali Murid</x-slot:title>

    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="rounded-2xl bg-white border border-neutral-border p-6 sm:p-8 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20 mb-3">
                        <span class="w-2 h-2 rounded-full bg-brand-primary"></span>
                        Layanan Bimbingan Konseling & Wali Murid
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-neutral-text flex items-center gap-2">
                        <i class="fa-solid fa-hand-holding-heart text-brand-primary"></i>
                        <span>Sesi Konsultasi Guru BK</span>
                    </h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Kelola Permohonan Konsultasi Wali Murid, Buka Slot Sesi Baru, dan Dokumentasikan Solusi Akademik
                    </p>
                </div>
                <div>
                    <a href="{{ route('bk.dashboard') }}" class="px-4 py-2 rounded-xl bg-white hover:bg-neutral-subtle text-neutral-text text-xs font-semibold border border-neutral-border transition-all shadow-sm">
                        <i class="fa-solid fa-arrow-left mr-1.5"></i> Dashboard Guru BK
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Bookings List (2 Cols) -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                        <h2 class="text-lg font-bold text-neutral-text">Daftar Permohonan Konsultasi Wali Murid</h2>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('bk.konsultasi.sessions') }}" class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ empty($status) ? 'bg-brand-primary text-white' : 'bg-neutral-subtle text-neutral-muted' }}">Semua</a>
                            <a href="{{ route('bk.konsultasi.sessions', ['status' => 'confirmed']) }}" class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $status === 'confirmed' ? 'bg-brand-primary text-white' : 'bg-neutral-subtle text-neutral-muted' }}">Terkonfirmasi</a>
                            <a href="{{ route('bk.konsultasi.sessions', ['status' => 'attended']) }}" class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $status === 'attended' ? 'bg-emerald-600 text-white' : 'bg-neutral-subtle text-neutral-muted' }}">Selesai</a>
                        </div>
                    </div>

                    @if ($bookings->isNotEmpty())
                        <div class="space-y-4">
                            @foreach ($bookings as $booking)
                                <div class="bg-neutral-subtle/50 border border-neutral-border rounded-xl p-4">
                                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-0.5 rounded text-[11px] font-bold {{ $booking->status === 'confirmed' ? 'bg-brand-soft text-brand-primary border border-brand-primary/20' : ($booking->status === 'attended' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-accent-soft text-red-accent-text border border-red-accent/20') }}">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                                <span class="text-xs text-neutral-muted font-medium">
                                                    <i class="fa-solid fa-calendar mr-1.5 text-neutral-muted"></i> {{ \Carbon\Carbon::parse($booking->slot?->date_time)->translatedFormat('l, d F Y - H:i') }} WIB
                                                </span>
                                            </div>

                                            <div class="text-base font-bold text-neutral-text mt-1">
                                                {{ $booking->student?->user?->name ?? 'Siswa' }}
                                                <span class="text-xs font-normal text-neutral-muted">({{ $booking->student?->academicClass?->name }})</span>
                                            </div>
                                            <div class="text-xs text-brand-primary">
                                                Wali Murid: <span class="font-semibold text-neutral-text">{{ $booking->parent?->name ?? 'Orang Tua' }}</span>
                                            </div>
                                            <div class="text-xs text-neutral-muted">
                                                Topik: <span class="text-neutral-text">{{ $booking->slot?->topic ?? 'Konsultasi BK' }}</span>
                                            </div>
                                            <div class="text-xs text-neutral-muted">
                                                Lokasi: <span class="text-neutral-text">{{ $booking->slot?->location_or_link ?? 'Ruang BK Alto' }}</span>
                                            </div>

                                            @if ($booking->notes)
                                                <div class="mt-2 p-2.5 rounded-lg bg-white border border-neutral-border text-xs text-neutral-text">
                                                    <span class="font-semibold text-neutral-muted block mb-0.5">Catatan Masalah / Harapan:</span>
                                                    "{{ $booking->notes }}"
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Update Status Form -->
                                        <div class="shrink-0 w-full sm:w-56 bg-white border border-neutral-border p-3 rounded-xl shadow-xs">
                                            <form method="POST" action="{{ route('bk.konsultasi.updateStatus', $booking->id) }}" class="space-y-2">
                                                @csrf
                                                @method('PATCH')
                                                <label class="block text-[11px] font-semibold text-neutral-muted">Perbarui Sesi</label>
                                                <select name="status" class="w-full bg-white border border-neutral-border rounded p-1.5 text-xs text-neutral-text focus:border-brand-primary">
                                                    <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Terkonfirmasi</option>
                                                    <option value="attended" {{ $booking->status === 'attended' ? 'selected' : '' }}>Selesai / Dihadiri</option>
                                                    <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                                </select>

                                                <textarea name="notes" rows="1" placeholder="Catatan hasil konsultasi..." class="w-full bg-white border border-neutral-border rounded p-1.5 text-xs text-neutral-text focus:border-brand-primary">{{ $booking->notes }}</textarea>

                                                <button type="submit" class="w-full py-1.5 rounded-lg bg-brand-primary hover:bg-brand-navy text-white text-xs font-semibold shadow-sm transition-all cursor-pointer">
                                                    Simpan Status
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="mt-4">
                                {{ $bookings->links() }}
                            </div>
                        </div>
                    @else
                        <div class="py-12 text-center text-neutral-muted">
                            <i class="fa-solid fa-hand-holding-heart text-3xl text-brand-primary block mb-2"></i>
                            <p class="text-sm font-semibold text-neutral-text">Belum ada booking konsultasi wali murid</p>
                            <p class="text-xs text-neutral-muted mt-1">Gunakan formulir di samping untuk membuka slot jadwal konsultasi baru.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Create New Slot Form (1 Col) -->
            <div class="space-y-6">
                <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-sm">
                    <h3 class="text-base font-bold text-neutral-text mb-1 flex items-center gap-2">
                        <i class="fa-solid fa-plus text-brand-primary"></i>
                        <span>Buka Jadwal Sesi BK Baru</span>
                    </h3>
                    <p class="text-xs text-neutral-muted mb-4">Slot ini dapat dipilih oleh orang tua murid melalui portal ortu.</p>

                    <form method="POST" action="{{ route('bk.konsultasi.store') }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-neutral-muted mb-1">Guru Konselor / Mentor Tertaut</label>
                            <select name="mentor_id" required class="w-full bg-white border border-neutral-border rounded-lg p-2 text-xs text-neutral-text focus:border-brand-primary">
                                @foreach ($teachers as $t)
                                    <option value="{{ $t->id }}">{{ $t->user->name }} ({{ $t->specialization_subject }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-muted mb-1">Topik Konsultasi</label>
                            <input type="text" name="topic" required placeholder="Contoh: Konsultasi Penjurusan PTN & Evaluasi Belajar" class="w-full bg-white border border-neutral-border rounded-lg p-2 text-xs text-neutral-text placeholder-neutral-muted focus:border-brand-primary">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-muted mb-1">Waktu & Tanggal</label>
                            <input type="datetime-local" name="date_time" required class="w-full bg-white border border-neutral-border rounded-lg p-2 text-xs text-neutral-text focus:border-brand-primary">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-muted mb-1">Maksimum Kuota</label>
                            <input type="number" name="max_quota" min="1" max="10" value="3" required class="w-full bg-white border border-neutral-border rounded-lg p-2 text-xs text-neutral-text focus:border-brand-primary">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-muted mb-1">Lokasi / Tautan Sesi</label>
                            <input type="text" name="location_or_link" value="Ruang Konseling Alto / Google Meet" required class="w-full bg-white border border-neutral-border rounded-lg p-2 text-xs text-neutral-text focus:border-brand-primary">
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl bg-brand-primary hover:bg-brand-navy text-white text-xs font-semibold shadow-sm transition-all cursor-pointer">
                            Buka Jadwal Sesi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
