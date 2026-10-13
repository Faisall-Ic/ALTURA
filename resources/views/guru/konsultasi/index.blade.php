<x-layouts.app>
    <x-slot:title>Kelola Jadwal Bimbingan Tentoring</x-slot:title>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-text flex items-center gap-2">
                    <i class="fa-solid fa-comments text-brand-primary"></i>
                    <span>Jadwal Bimbingan & Tentoring Mentor</span>
                </h1>
                <p class="text-xs text-neutral-muted mt-1">
                    Buka slot konsultasi mapel intensif bagi siswa bimbingan Anda dan pantau daftar siswa yang terdaftar.
                </p>
            </div>
            <button type="button" onclick="document.getElementById('create-slot-modal').classList.remove('hidden')"
                class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-sm transition-all cursor-pointer">
                <i class="fa-solid fa-plus mr-2 text-xs"></i>
                <span>Buka Slot Bimbingan Baru</span>
            </button>
        </div>

        <!-- Slot List -->
        <div class="space-y-4">
            @forelse ($slots as $slot)
                <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-neutral-border">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold text-neutral-text">{{ $slot->topic }}</h3>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $slot->remaining_quota > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-accent-soft text-red-accent-text border border-red-accent/20' }}">
                                    {{ $slot->remaining_quota > 0 ? 'Tersedia' : 'Penuh' }}
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-4 text-xs text-neutral-muted mt-2">
                                <div class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-calendar text-xs text-neutral-muted"></i>
                                    <span>{{ $slot->date_time->format('l, d M Y — H:i') }} WIB</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-location-dot text-xs text-neutral-muted"></i>
                                    <span>{{ $slot->location_or_link }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-brand-primary font-semibold font-mono">
                                        Sisa Kuota: {{ $slot->remaining_quota }} dari {{ $slot->max_quota }} Siswa
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <form action="{{ route('guru.konsultasi.destroy', $slot->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus slot bimbingan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-1.5 bg-red-accent-soft hover:bg-red-accent-soft/80 text-red-accent-text text-xs font-semibold rounded-xl border border-red-accent/20 transition-all cursor-pointer">
                                    Hapus Slot
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Booked Students List -->
                    <div>
                        <h4 class="text-xs font-bold uppercase text-neutral-muted tracking-wider mb-2">
                            Daftar Siswa Terdaftar ({{ $slot->bookings->count() }})
                        </h4>
                        @if ($slot->bookings->isNotEmpty())
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5">
                                @foreach ($slot->bookings as $booking)
                                    <div class="bg-neutral-subtle/50 border border-neutral-border rounded-xl p-3 text-xs flex items-center justify-between">
                                        <div>
                                            <a href="{{ route('guru.bimbingan.student.profile', $booking->student_id) }}"
                                                class="font-bold text-neutral-text hover:text-brand-primary transition-colors block">
                                                {{ $booking->student?->user?->name }}
                                            </a>
                                            <span class="text-[11px] text-neutral-muted">
                                                {{ $booking->student?->academicClass?->name }} • NISN: {{ $booking->student?->nisn }}
                                            </span>
                                            @if ($booking->notes)
                                                <span class="block text-[10px] text-neutral-muted italic mt-0.5 truncate max-w-[180px]">
                                                    "{{ $booking->notes }}"
                                                </span>
                                            @endif
                                        </div>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $booking->status === 'confirmed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-neutral-subtle text-neutral-muted border border-neutral-border' }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-neutral-muted italic">Belum ada siswa yang memesan slot ini.</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white border border-neutral-border rounded-2xl p-12 text-center text-neutral-muted shadow-sm">
                    <p class="text-sm">Belum ada jadwal bimbingan tentoring yang dibuat.</p>
                    <p class="text-xs mt-1">Klik tombol <strong>"Buka Slot Bimbingan Baru"</strong> di atas untuk membuka sesi.</p>
                </div>
            @endforelse

            @if ($slots->hasPages())
                <div class="pt-4">
                    {{ $slots->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Form: Buka Slot Baru -->
    <div id="create-slot-modal" class="fixed inset-0 z-50 bg-brand-dark/40 backdrop-blur-xs hidden flex items-center justify-center p-4">
        <div class="bg-white border border-neutral-border rounded-2xl max-w-lg w-full p-6 shadow-2xl relative">
            <div class="flex items-center justify-between pb-4 border-b border-neutral-border">
                <h3 class="text-base font-bold text-neutral-text">Buka Slot Bimbingan Baru</h3>
                <button type="button" onclick="document.getElementById('create-slot-modal').classList.add('hidden')"
                    class="text-neutral-muted hover:text-neutral-text text-lg font-bold">
                    &times;
                </button>
            </div>

            <form action="{{ route('guru.konsultasi.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-neutral-muted uppercase tracking-wider mb-1">
                        Topik Bimbingan / Mapel <span class="text-red-accent">*</span>
                    </label>
                    <input type="text" name="topic" required
                        placeholder="Contoh: Bedah Soal TPS Penalaran Matematika"
                        class="w-full px-4 py-2.5 rounded-xl bg-white border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary focus:ring-1 focus:ring-brand-primary">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-neutral-muted uppercase tracking-wider mb-1">
                            Tanggal & Waktu <span class="text-red-accent">*</span>
                        </label>
                        <input type="datetime-local" name="date_time" required
                            class="w-full px-4 py-2.5 rounded-xl bg-white border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary focus:ring-1 focus:ring-brand-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-neutral-muted uppercase tracking-wider mb-1">
                            Kapasitas Kuota Siswa <span class="text-red-accent">*</span>
                        </label>
                        <input type="number" name="max_quota" min="1" max="20" value="5" required
                            class="w-full px-4 py-2.5 rounded-xl bg-white border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary focus:ring-1 focus:ring-brand-primary">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-neutral-muted uppercase tracking-wider mb-1">
                        Lokasi / Link Pertemuan
                    </label>
                    <input type="text" name="location_or_link"
                        placeholder="Contoh: Ruang Alto B2 atau link Google Meet"
                        class="w-full px-4 py-2.5 rounded-xl bg-white border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary focus:ring-1 focus:ring-brand-primary">
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-border">
                    <button type="button" onclick="document.getElementById('create-slot-modal').classList.add('hidden')"
                        class="px-4 py-2 bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold rounded-xl border border-neutral-border transition-all cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-brand-primary hover:bg-brand-navy text-white text-xs font-semibold rounded-xl shadow-sm transition-all cursor-pointer">
                        Simpan & Buka Slot
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
