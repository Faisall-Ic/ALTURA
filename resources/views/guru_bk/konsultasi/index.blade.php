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

        <!-- Grid Layout Atas (Tabel Jadwal & Form dibuat h-full & items-stretch agar tinggi sama) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
            <!-- 1. Jadwal Sesi BK yang Dibuka (Table Style di Kiri - 2 Cols) -->
            <div class="lg:col-span-2">
                <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs h-full flex flex-col justify-between">
                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 flex-shrink-0">
                            <h3 class="text-base font-bold text-neutral-text flex items-center gap-2">
                                <i class="fa-solid fa-calendar-check text-brand-primary"></i>
                                <span>Jadwal Sesi BK yang Dibuka</span>
                            </h3>
                            <span class="text-xs text-neutral-muted font-medium">{{ $slots->count() }} jadwal aktif</span>
                        </div>

                        @if ($slots->isNotEmpty())
                            <!-- Container Scroll Vertikal & Horizontal untuk Tabel -->
                            <div class="overflow-x-auto overflow-y-auto max-h-[400px] border border-neutral-border/60 rounded-xl">
                                <table class="w-full text-left text-sm text-neutral-text min-w-[600px]">
                                    <thead class="text-xs uppercase bg-neutral-subtle/70 text-neutral-muted border-b border-neutral-border font-semibold sticky top-0 z-10">
                                        <tr>
                                            <th class="px-4 py-3">Topik Konsultasi</th>
                                            <th class="px-4 py-3">Lokasi / Tautan</th>
                                            <th class="px-4 py-3">Waktu & Tanggal</th>
                                            <th class="px-4 py-3">Konselor</th>
                                            <th class="px-4 py-3">Kuota</th>
                                            <th class="px-4 py-3 text-right">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-border bg-white">
                                        @foreach ($slots as $slot)
                                            <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                                <td class="px-4 py-3.5 font-semibold text-neutral-text text-xs whitespace-nowrap">
                                                    {{ $slot->topic }}
                                                </td>
                                                <td class="px-4 py-3.5 text-xs text-neutral-muted whitespace-nowrap">
                                                    <div class="flex items-center gap-1.5">
                                                        <i class="fa-solid fa-location-dot text-brand-primary/70"></i>
                                                        <span>{{ $slot->location_or_link ?? '-' }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3.5 text-xs text-neutral-text whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($slot->date_time)->format('d M Y, H:i') }} WIB
                                                </td>
                                                <td class="px-4 py-3.5 text-xs text-neutral-text whitespace-nowrap">
                                                    {{ $slot->mentor?->user?->name ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3.5 text-xs font-bold text-neutral-text whitespace-nowrap">
                                                    {{ $slot->remaining_quota }} / {{ $slot->max_quota }}
                                                </td>
                                                <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                                    <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold {{ $slot->remaining_quota > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-accent-soft text-red-accent-text border border-red-accent/20' }}">
                                                        {{ $slot->remaining_quota > 0 ? 'Tersedia' : 'Penuh' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="py-12 text-center text-neutral-muted">
                                <i class="fa-solid fa-calendar text-3xl text-brand-primary block mb-3"></i>
                                <p class="text-sm font-semibold text-neutral-text">Belum ada jadwal sesi BK yang dibuka</p>
                                <p class="text-xs text-neutral-muted mt-1">Gunakan formulir di sebelah kanan untuk membuka jadwal baru.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 2. Form Buka Jadwal Sesi BK Baru (1 Lapis di Kanan - 1 Col) -->
            <div class="lg:col-span-1">
                <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs h-full flex flex-col justify-between">
                    <div>
                        <h3 class="text-base font-bold text-neutral-text mb-1 flex items-center gap-2 flex-shrink-0">
                            <i class="fa-solid fa-plus text-brand-primary"></i>
                            <span>Buka Jadwal Sesi BK Baru</span>
                        </h3>
                        <p class="text-xs text-neutral-muted mb-4 flex-shrink-0">Slot ini dapat dipilih oleh orang tua murid melalui portal ortu.</p>

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
                                <input type="text" name="topic" required placeholder="Contoh: Konsultasi Penjurusan PTN & Eval" class="w-full bg-white border border-neutral-border rounded-lg p-2 text-xs text-neutral-text placeholder-neutral-muted focus:border-brand-primary">
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

                            <button type="submit" class="w-full py-2.5 rounded-xl bg-brand-primary hover:bg-brand-navy text-white text-xs font-semibold shadow-sm transition-all cursor-pointer flex-shrink-0 mt-2">
                                Buka Jadwal Sesi
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- 3. Daftar Permohonan Konsultasi Wali Murid (Full Width Bottom) -->
            <div class="lg:col-span-3">
                <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                        <h2 class="text-base font-bold text-neutral-text flex items-center gap-2">
                            <i class="fa-solid fa-users text-brand-primary"></i>
                            <span>Daftar Permohonan Konsultasi Wali Murid</span>
                        </h2>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('bk.konsultasi.sessions') }}" class="px-3 py-1 rounded-lg text-xs font-semibold {{ empty($status) ? 'bg-brand-primary text-white' : 'bg-neutral-subtle text-neutral-muted' }}">Semua</a>
                            <a href="{{ route('bk.konsultasi.sessions', ['status' => 'confirmed']) }}" class="px-3 py-1 rounded-lg text-xs font-semibold {{ $status === 'confirmed' ? 'bg-brand-primary text-white' : 'bg-neutral-subtle text-neutral-muted' }}">Terkonfirmasi</a>
                            <a href="{{ route('bk.konsultasi.sessions', ['status' => 'attended']) }}" class="px-3 py-1 rounded-lg text-xs font-semibold {{ $status === 'attended' ? 'bg-emerald-600 text-white' : 'bg-neutral-subtle text-neutral-muted' }}">Selesai</a>
                        </div>
                    </div>

                    @if ($bookings->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($bookings as $booking)
                                <div class="bg-white border border-neutral-border rounded-xl p-4 shadow-xs flex flex-col justify-between hover:border-brand-primary/40 transition-all">
                                    <div class="space-y-3">
                                        <!-- Top Row: Badge & Date -->
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $booking->status === 'confirmed' ? 'bg-brand-soft text-brand-primary border border-brand-primary/20' : ($booking->status === 'attended' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-accent-soft text-red-accent-text border border-red-accent/20') }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                            <span class="text-xs text-neutral-muted font-medium flex items-center gap-1">
                                                <i class="fa-solid fa-calendar text-[11px]"></i> {{ \Carbon\Carbon::parse($booking->slot?->date_time)->translatedFormat('d M Y - H:i') }} WIB
                                            </span>
                                        </div>

                                        <!-- Student & Parent Details -->
                                        <div>
                                            <div class="text-sm font-bold text-neutral-text">
                                                {{ $booking->student?->user?->name ?? 'Siswa' }}
                                                <span class="text-xs font-normal text-neutral-muted">({{ $booking->student?->academicClass?->name }})</span>
                                            </div>
                                            <div class="text-xs text-neutral-muted mt-0.5">
                                                Wali Murid: <span class="font-semibold text-neutral-text">{{ $booking->parent?->name ?? 'Orang Tua' }}</span>
                                            </div>
                                            <div class="text-xs text-neutral-muted mt-0.5">
                                                Topik: <span class="text-neutral-text font-medium">{{ $booking->slot?->topic ?? 'Konsultasi BK' }}</span>
                                            </div>
                                            <div class="text-xs text-neutral-muted mt-0.5">
                                                Lokasi: <span class="text-neutral-text">{{ $booking->slot?->location_or_link ?? 'Ruang BK Alto' }}</span>
                                            </div>
                                        </div>

                                        <!-- Notes Box if available -->
                                        @if ($booking->notes)
                                            <div class="p-2.5 rounded-lg bg-neutral-subtle/50 border border-neutral-border text-xs text-neutral-text">
                                                <span class="font-semibold text-neutral-muted block mb-0.5">Catatan Masalah / Harapan:</span>
                                                "{{ $booking->notes }}"
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Update Status Form (Inline di dalam card ortu) -->
                                    <div class="mt-4 pt-3 border-t border-neutral-border">
                                        <form method="POST" action="{{ route('bk.konsultasi.updateStatus', $booking->id) }}" class="space-y-2">
                                            @csrf
                                            @method('PATCH')
                                            <div class="flex items-center justify-between gap-2">
                                                <label class="text-[11px] font-semibold text-neutral-muted">Perbarui Sesi Konsultasi</label>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                <select name="status" class="w-full bg-white border border-neutral-border rounded-lg p-1.5 text-xs text-neutral-text focus:border-brand-primary">
                                                    <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Terkonfirmasi</option>
                                                    <option value="attended" {{ $booking->status === 'attended' ? 'selected' : '' }}>Selesai / Dihadiri</option>
                                                    <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                                </select>
                                                <button type="submit" class="w-full py-1.5 rounded-lg bg-brand-primary hover:bg-brand-navy text-white text-xs font-semibold shadow-sm transition-all cursor-pointer">
                                                    Simpan Status
                                                </button>
                                            </div>
                                            <textarea name="notes" rows="1" placeholder="Catatan hasil konsultasi..." class="w-full bg-white border border-neutral-border rounded-lg p-1.5 text-xs text-neutral-text focus:border-brand-primary">{{ $booking->notes }}</textarea>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $bookings->links() }}
                        </div>
                    @else
                        <div class="py-12 text-center text-neutral-muted">
                            <i class="fa-solid fa-hand-holding-heart text-3xl text-brand-primary block mb-2"></i>
                            <p class="text-sm font-semibold text-neutral-text">Belum ada booking konsultasi wali murid</p>
                            <p class="text-xs text-neutral-muted mt-1">Buka jadwal sesi BK di sebelah kiri agar orang tua dapat memesan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>