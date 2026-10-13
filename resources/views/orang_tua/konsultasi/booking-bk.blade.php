<x-layouts.app>
    <x-slot:title>Booking Konsultasi Guru BK — Portal Orang Tua</x-slot:title>

    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-neutral-border shadow-xs p-6 sm:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20 mb-3">
                        <span class="w-2 h-2 rounded-full bg-brand-primary animate-pulse"></span>
                        Layanan Pendampingan Siswa & Wali Murid
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-neutral-text">Booking Konsultasi dengan Guru BK <i class="fa-solid fa-comments ml-1 text-brand-primary"></i></h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Diskusikan perkembangan akademik, motivasi belajar, dan pemetaan jurusan SNBP/SNBT bersama Kak Nisa, S.Psi
                    </p>
                </div>
                <div>
                    <a href="{{ route('ortu.dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold border border-neutral-border transition-all">
                        <i class="fa-solid fa-arrow-left text-[11px]"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Available Slots List & Booking Form (2 Cols) -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs">
                    <h2 class="text-lg font-bold text-neutral-text mb-1">Pilih Jadwal Konsultasi Terbuka</h2>
                    <p class="text-xs text-neutral-muted mb-4">Pilih salah satu jadwal di bawah ini yang paling sesuai dengan waktu luang Anda.</p>

                    @if ($availableSlots->isNotEmpty())
                        <form method="POST" action="{{ route('ortu.konsultasi.store') }}" class="space-y-5">
                            @csrf

                            @if ($children->count() > 1)
                                <div>
                                    <label class="block text-xs font-semibold text-neutral-text mb-1">Pilih Putra/Putri Anda</label>
                                    <select name="student_id" class="w-full bg-surface border border-neutral-border rounded-lg p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                                        @foreach ($children as $c)
                                            <option value="{{ $c->id }}">{{ $c->user->name }} ({{ $c->academicClass?->name }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="space-y-2.5">
                                @foreach ($availableSlots as $slot)
                                    <label class="relative flex items-start gap-3 p-4 rounded-xl border border-neutral-border bg-neutral-subtle/30 hover:bg-brand-soft/40 hover:border-brand-primary/40 cursor-pointer transition-all">
                                        <input type="radio" name="slot_id" value="{{ $slot->id }}" required class="mt-1 text-brand-primary focus:ring-brand-primary">
                                        <div class="flex-1 space-y-1">
                                            <div class="flex flex-wrap items-center justify-between gap-2">
                                                <span class="font-bold text-sm text-neutral-text">{{ $slot->topic }}</span>
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-accent-soft text-amber-accent-text border border-amber-accent/30">
                                                    Sisa Kuota: {{ $slot->remaining_quota }} dari {{ $slot->max_quota }}
                                                </span>
                                            </div>
                                            <div class="text-xs text-brand-primary font-semibold">
                                                <i class="fa-solid fa-calendar-days mr-1.5"></i> {{ \Carbon\Carbon::parse($slot->date_time)->translatedFormat('l, d F Y — H:i') }} WIB
                                            </div>
                                            <div class="text-xs text-neutral-muted">
                                                Lokasi: <span class="text-neutral-text font-medium">{{ $slot->location_or_link }}</span>
                                            </div>
                                            <div class="text-[11px] text-neutral-muted">
                                                Pembimbing: {{ $slot->mentor?->user?->name ?? 'Kak Nisa (Guru BK)' }}
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-neutral-text mb-1">Harapan atau Masalah yang Ingin Didiskusikan *</label>
                                <textarea name="notes" rows="3" required placeholder="Contoh: Ingin konsultasi mengenai target nilai tryout matematika ananda dan pilihan jurusan kedokteran..." class="w-full bg-surface border border-neutral-border rounded-xl p-3 text-xs text-neutral-text placeholder-neutral-muted focus:outline-none focus:border-brand-primary"></textarea>
                            </div>

                            <button type="submit" class="w-full py-3 rounded-xl bg-brand-primary hover:bg-brand-navy text-white font-bold text-xs shadow-xs transition-all">
                                Ajukan Jadwal Konsultasi Sekarang
                            </button>
                        </form>
                    @else
                        <div class="py-12 text-center text-neutral-muted">
                            <span class="text-3xl block mb-2 text-brand-primary"><i class="fa-solid fa-hand-holding-heart"></i></span>
                            <p class="text-sm font-semibold text-neutral-text">Belum ada slot sesi konsultasi terbuka</p>
                            <p class="text-xs text-neutral-muted mt-1">Silakan hubungi Guru BK secara langsung melalui menu Kontak Pembimbing.</p>
                            <a href="{{ route('ortu.kontak') }}" class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-brand-soft hover:bg-brand-primary hover:text-white text-xs font-semibold text-brand-primary transition-all">
                                Kontak Guru BK Langsung <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Previous Bookings History (1 Col) -->
            <div class="space-y-4">
                <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs">
                    <h3 class="text-base font-bold text-neutral-text mb-3"><i class="fa-solid fa-calendar-days mr-1.5 text-brand-primary"></i> Riwayat Konsultasi Anda</h3>

                    @if ($myBookings->isNotEmpty())
                        <div class="space-y-3">
                            @foreach ($myBookings as $bk)
                                <div class="p-3.5 rounded-xl bg-neutral-subtle/50 border border-neutral-border text-xs space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-neutral-text">{{ $bk->slot?->topic ?? 'Konsultasi BK' }}</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $bk->status === 'confirmed' ? 'bg-brand-soft text-brand-primary border border-brand-primary/20' : ($bk->status === 'attended' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-neutral-subtle text-neutral-muted border border-neutral-border') }}">
                                            {{ $bk->status }}
                                        </span>
                                    </div>
                                    <div class="text-brand-primary font-medium">
                                        {{ \Carbon\Carbon::parse($bk->slot?->date_time)->translatedFormat('d M Y, H:i') }} WIB
                                    </div>
                                    <div class="text-neutral-muted">
                                        Tempat: {{ $bk->slot?->location_or_link }}
                                    </div>
                                    @if ($bk->notes)
                                        <div class="mt-1 pt-1 border-t border-neutral-border text-neutral-muted italic text-[11px]">
                                            "{{ $bk->notes }}"
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-neutral-muted py-6 text-center">Anda belum pernah mengajukan sesi konsultasi.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
