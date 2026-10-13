<x-layouts.app>
    <x-slot:title>Konsultasi & Tentoring</x-slot:title>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-text flex items-center gap-2">
                    <i class="fa-solid fa-comments text-brand-primary"></i>
                    <span>Konsultasi Mapel & Tentoring</span>
                </h1>
                <p class="text-xs text-neutral-muted mt-1">
                    Pesan sesi bimbingan tatap muka atau tentoring intensif bersama guru mentor Anda.
                </p>
            </div>
        </div>

        <!-- Section 1: My Bookings -->
        <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-sm">
            <h2 class="text-sm font-bold text-neutral-text mb-4 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span>Jadwal Bimbingan Saya (Terdaftar)</span>
            </h2>

            @if ($myBookings->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($myBookings as $booking)
                        <div class="bg-neutral-subtle/50 border border-neutral-border rounded-xl p-4 space-y-3 relative overflow-hidden">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span class="text-xs font-semibold text-brand-primary block">{{ $booking->slot?->mentor?->user?->name }}</span>
                                    <h3 class="text-sm font-bold text-neutral-text mt-0.5">{{ $booking->slot?->topic }}</h3>
                                </div>
                                @if ($booking->status === 'confirmed')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Terkonfirmasi
                                    </span>
                                @elseif ($booking->status === 'cancelled')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-neutral-subtle text-neutral-muted border border-neutral-border">
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-brand-soft text-brand-primary border border-brand-primary/20">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                @endif
                            </div>

                            <div class="text-xs text-neutral-muted space-y-1">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-calendar text-xs text-neutral-muted w-4"></i>
                                    <span>{{ $booking->slot?->date_time?->format('d M Y, H:i') }} WIB</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-location-dot text-xs text-neutral-muted w-4"></i>
                                    <span>{{ $booking->slot?->location_or_link }}</span>
                                </div>
                            </div>

                            @if ($booking->status === 'confirmed')
                                <div class="pt-2 border-t border-neutral-border flex justify-end">
                                    <form action="{{ route('siswa.konsultasi.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[11px] font-semibold text-red-accent hover:text-red-700 transition-colors">
                                            Batalkan Sesi
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-neutral-muted">Anda belum memiliki jadwal bimbingan yang aktif.</p>
            @endif
        </div>

        <!-- Section 2: Open Slots -->
        <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-sm">
            <h2 class="text-sm font-bold text-neutral-text mb-4 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-brand-primary"></span>
                <span>Slot Bimbingan Terbuka (Siap Dipesan)</span>
            </h2>

            @if ($openSlots->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($openSlots as $slot)
                        <div class="bg-neutral-subtle/50 border border-neutral-border rounded-xl p-5 space-y-4 hover:border-brand-primary/40 transition-colors flex flex-col justify-between">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-semibold text-brand-primary">{{ $slot->mentor?->user?->name }}</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-brand-soft text-brand-primary border border-brand-primary/20">
                                        Sisa: {{ $slot->remaining_quota }} / {{ $slot->max_quota }}
                                    </span>
                                </div>

                                <h3 class="text-sm font-bold text-neutral-text">{{ $slot->topic }}</h3>

                                <div class="text-xs text-neutral-muted space-y-1 pt-1">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-clock text-xs text-neutral-muted w-4"></i>
                                        <span>{{ $slot->date_time->format('d M Y, H:i') }} WIB</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-location-dot text-xs text-neutral-muted w-4"></i>
                                        <span>{{ $slot->location_or_link }}</span>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('siswa.konsultasi.book', $slot->id) }}" method="POST" class="pt-3 border-t border-neutral-border">
                                @csrf
                                <button type="submit"
                                    class="w-full py-2 bg-brand-primary hover:bg-brand-navy text-white text-xs font-semibold rounded-lg shadow-sm transition-all cursor-pointer">
                                    Pesan Slot Ini
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-neutral-muted">Saat ini belum ada slot bimbingan tentoring baru yang dibuka oleh guru.</p>
            @endif
        </div>
    </div>
</x-layouts.app>
