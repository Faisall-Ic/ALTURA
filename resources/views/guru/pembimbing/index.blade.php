<x-layouts.app>
    <x-slot:title>Peran Pembimbing Akademik: Monitoring Mentee 360°</x-slot:title>

    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="bg-white border border-neutral-border rounded-2xl p-6 sm:p-8 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20 mb-2">
                        <i class="fa-solid fa-graduation-cap text-xs"></i>
                        <span>Peran Akademik: Pembimbing Akademik (Mentor)</span>
                    </div>
                    <h1 class="text-2xl font-bold text-neutral-text">Dashboard Monitoring Mentee 360°</h1>
                    <p class="text-xs text-neutral-muted mt-1">
                        Kawal perkembangan holistik siswa bimbingan Anda: Vocab CBT, Storytelling, Wacana, ALTER, dan Presensi.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <form method="POST" action="{{ route('guru.pembimbing.alerts.trigger') }}">
                        @csrf
                        <input type="hidden" name="month" value="{{ $currentMonth }}">
                        <input type="hidden" name="year" value="{{ $currentYear }}">
                        <button type="submit" class="px-4 py-2.5 rounded-xl bg-amber-accent hover:bg-amber-600 text-white text-xs font-bold shadow-xs transition-all flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-bell"></i>
                            <span>Evaluasi Tanggal 20 (Cek Alert)</span>
                        </button>
                    </form>
                    <a href="{{ route('guru.mapel.index') }}" class="px-4 py-2.5 rounded-xl border border-neutral-border bg-white hover:bg-neutral-subtle text-neutral-text text-xs font-bold shadow-2xs transition-all flex items-center gap-2">
                        <i class="fa-solid fa-book text-brand-primary"></i>
                        <span>Beralih ke Menu Mapel</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Monthly Theme & Alert Highlights -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Active Storytelling Theme -->
            <div class="md:col-span-2 bg-white border border-neutral-border rounded-2xl p-6 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-neutral-border mb-3">
                        <span class="text-xs font-bold text-brand-primary uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-solid fa-bullhorn text-xs"></i>
                            <span>Tema Storytelling Bulan {{ date('F Y') }}</span>
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-soft text-brand-primary border border-brand-primary/20">
                            Wajib Bulan Ini
                        </span>
                    </div>
                    @if ($activeTheme)
                        <h2 class="text-base font-bold text-neutral-text">{{ $activeTheme->theme_name }}</h2>
                        <p class="text-xs text-neutral-muted mt-2 leading-relaxed">{{ $activeTheme->description }}</p>
                    @else
                        <p class="text-xs text-neutral-muted">Tema bulanan belum ditetapkan oleh Tim Akademik Pusat.</p>
                    @endif
                </div>

                <div class="mt-4 pt-3 border-t border-neutral-border flex items-center justify-between text-xs text-neutral-muted">
                    <span>Target Setoran Mentee: Minimal 1 naskah disetujui &amp; diujikan lisan</span>
                    <a href="{{ route('guru.bimbingan.vocab.monitor') }}" class="text-brand-primary font-bold hover:underline">
                        Lihat Rekap Setoran &rarr;
                    </a>
                </div>
            </div>

            <!-- EWS Submission Alert Box -->
            <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-neutral-border mb-3">
                        <span class="text-xs font-bold text-red-accent uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                            <span>Alert Tanggal 20</span>
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-accent-soft text-red-accent">
                            {{ $alerts->count() }} Belum Tuntas
                        </span>
                    </div>

                    @if ($alerts->isNotEmpty())
                        <div class="space-y-2.5 max-h-48 overflow-y-auto pr-1">
                            @foreach ($alerts as $al)
                                <div class="p-2.5 rounded-xl border border-red-200 bg-red-accent-soft/40 flex items-start justify-between gap-2 text-xs">
                                    <div>
                                        <p class="font-bold text-neutral-text">{{ $al->student?->user?->name ?? 'Siswa' }}</p>
                                        <p class="text-[10px] text-red-accent font-medium mt-0.5">
                                            Kurang: {{ implode(', ', (array) $al->missing_tasks) }}
                                        </p>
                                    </div>
                                    <form method="POST" action="{{ route('guru.pembimbing.alerts.resolve', $al->id) }}">
                                        @csrf
                                        <button type="submit" title="Tandai Selesai" class="p-1 rounded-lg hover:bg-red-200 text-red-accent transition-colors cursor-pointer">
                                            <i class="fa-solid fa-check text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 text-neutral-muted">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-2xl mb-1 block"></i>
                            <p class="font-bold text-xs text-neutral-text">Semua Mentee Tuntas</p>
                            <p class="text-[11px] text-neutral-muted mt-0.5">Tidak ada penugasan tertunda per tanggal 20 bulan ini.</p>
                        </div>
                    @endif
                </div>

                <p class="text-[10px] text-neutral-muted mt-3 pt-3 border-t border-neutral-border">
                    Auto-alert otomatis menyalakan alarm pada tgl 20 untuk memicu bimbingan intensif.
                </p>
            </div>
        </div>

        <!-- Mentees Holistic Table -->
        <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-neutral-border gap-2">
                <div>
                    <h2 class="text-base font-bold text-neutral-text flex items-center gap-2">
                        <i class="fa-solid fa-users text-brand-primary"></i>
                        <span>Daftar Siswa Bimbingan (Mentees)</span>
                    </h2>
                    <p class="text-xs text-neutral-muted mt-0.5">
                        Pemantauan 360 derajat untuk {{ $mentees->count() }} siswa yang dipercayakan kepada Anda.
                    </p>
                </div>
            </div>

            @if ($mentees->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-neutral-text">
                        <thead class="bg-neutral-subtle uppercase text-[10px] font-bold text-neutral-muted border-b border-neutral-border">
                            <tr>
                                <th class="px-4 py-3">Nama Mentee</th>
                                <th class="px-4 py-3">Kelas &amp; Jurusan</th>
                                <th class="px-4 py-3 text-center">Presensi</th>
                                <th class="px-4 py-3 text-center">Vocab CBT</th>
                                <th class="px-4 py-3 text-center">Storytelling</th>
                                <th class="px-4 py-3 text-center">Wacana</th>
                                <th class="px-4 py-3 text-center">Setoran Lisan</th>
                                <th class="px-4 py-3 text-center">Progres Bulan Ini</th>
                                <th class="px-4 py-3 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-border">
                            @foreach ($mentees as $mentee)
                                @php
                                    $st = $menteeStats[$mentee->id] ?? [];
                                @endphp
                                <tr class="hover:bg-neutral-subtle/40 transition-colors">
                                    <td class="px-4 py-3.5 font-bold text-neutral-text">
                                        {{ $mentee->user?->name }}
                                        <span class="block text-[10px] font-mono font-normal text-neutral-muted">NISN: {{ $mentee->nisn ?? 'Belum ada' }}</span>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <span class="font-semibold text-neutral-text block">{{ $mentee->academicClass?->name ?? '-' }}</span>
                                        <span class="text-[10px] text-neutral-muted">{{ $mentee->major?->name ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="inline-block px-2.5 py-0.5 rounded-full font-bold text-[10px] font-mono {{ ($st['attendance_color'] ?? '') === 'green' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : (($st['attendance_color'] ?? '') === 'yellow' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-red-50 text-red-700 border border-red-200') }}">
                                            {{ $st['attendance_pct'] ?? 0 }}%
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        @if ($st['has_vocab'] ?? false)
                                            <i class="fa-solid fa-circle-check text-emerald-600 text-sm" title="Sudah Lulus"></i>
                                        @else
                                            <i class="fa-solid fa-circle-xmark text-slate-300 text-sm" title="Belum Mengerjakan"></i>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        @if ($st['has_storytelling'] ?? false)
                                            <i class="fa-solid fa-circle-check text-emerald-600 text-sm" title="Naskah Diserahkan"></i>
                                        @else
                                            <i class="fa-solid fa-circle-xmark text-slate-300 text-sm" title="Belum Menyerahkan"></i>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        @if (! ($st['wacana_applies'] ?? false))
                                            <span class="text-[10px] text-neutral-muted font-medium">Belum Wajib</span>
                                        @elseif ($st['has_wacana'] ?? false)
                                            <i class="fa-solid fa-circle-check text-emerald-600 text-sm" title="Selesai"></i>
                                        @else
                                            <i class="fa-solid fa-circle-xmark text-slate-300 text-sm" title="Belum"></i>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        @if ($st['has_oral'] ?? false)
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Sudah Booking
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                                Belum
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <div class="inline-flex items-center gap-2">
                                            <div class="w-16 bg-neutral-subtle h-2 rounded-full overflow-hidden border border-neutral-border">
                                                <div class="bg-brand-primary h-full" style="width: {{ $st['progress_pct'] ?? 0 }}%"></div>
                                            </div>
                                            <span class="font-mono text-[11px] font-bold text-neutral-text">{{ $st['progress_pct'] ?? 0 }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 text-right space-x-1.5">
                                        <a href="{{ route('guru.bimbingan.student.profile', $mentee->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-brand-primary hover:bg-brand-navy text-white text-xs font-bold transition-all shadow-2xs">
                                            <i class="fa-solid fa-id-card-clip"></i> Profil 360°
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12 text-neutral-muted">
                    <i class="fa-solid fa-user-xmark text-3xl mb-2 text-neutral-muted/40 block"></i>
                    <p class="font-bold text-neutral-text text-sm">Belum ada mentee yang ditugaskan ke akun Anda.</p>
                    <p class="text-xs text-neutral-muted mt-1">Admin ALTO dapat mengalokasikan siswa bimbingan melalui menu Master Data Siswa.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
