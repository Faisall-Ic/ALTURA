<x-layouts.app>
    <x-slot:title>Rekapitulasi Target Bulanan Siswa</x-slot:title>

    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-neutral-border shadow-xs p-6 sm:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20 mb-3">
                        <span class="w-2 h-2 rounded-full bg-brand-primary animate-pulse"></span>
                        Capaian Target Siswa Terpadu
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-neutral-text">Rekapitulasi Target Bulanan Siswa <i class="fa-solid fa-bullseye ml-1 text-amber-accent"></i></h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Monitoring Pencapaian Modul Vocab, Ujian Praktik Speaking, Skor Tryout SNBT, dan Kehadiran
                    </p>
                </div>
                <div>
                    <a href="{{ route('bk.dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold border border-neutral-border transition-all">
                        <i class="fa-solid fa-arrow-left text-[11px]"></i> Dashboard Guru BK
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter by Class -->
        <div class="bg-white border border-neutral-border rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
            <div class="text-xs font-medium text-neutral-muted">Menampilkan pencapaian siswa per kelas:</div>
            <form method="GET" action="{{ route('bk.rekap.target') }}" class="flex items-center gap-2">
                <select name="class_id" onchange="this.form.submit()" class="bg-surface border border-neutral-border rounded-lg px-3 py-1.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                    <option value="">Semua Kelas</option>
                    @foreach ($classes as $c)
                        <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Table of Student Targets -->
        <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-neutral-text">
                    <thead class="text-xs uppercase bg-neutral-subtle/70 text-neutral-muted border-b border-neutral-border font-semibold">
                        <tr>
                            <th class="px-4 py-3">Nama Siswa</th>
                            <th class="px-4 py-3">Kelas & Jurusan</th>
                            <th class="px-4 py-3">Kehadiran (%)</th>
                            <th class="px-4 py-3">Vocab Mastery</th>
                            <th class="px-4 py-3">Ujian Speaking</th>
                            <th class="px-4 py-3">Rerata Tryout SNBT</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-border">
                        @forelse ($studentRecaps as $item)
                            @php
                                $s = $item['student'];
                            @endphp
                            <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                <td class="px-4 py-3.5">
                                    <div class="font-semibold text-neutral-text">{{ $s->user->name }}</div>
                                    <div class="font-mono text-xs text-neutral-muted">NISN: {{ $s->nisn }}</div>
                                </td>
                                <td class="px-4 py-3.5 text-xs text-neutral-text">
                                    {{ $s->academicClass?->name }}
                                    <span class="block text-[11px] text-neutral-muted">{{ $s->major?->name }}</span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-xs text-neutral-text">{{ $item['attendance_percentage'] }}%</span>
                                        @if ($item['attendance_color'] === 'green')
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        @elseif ($item['attendance_color'] === 'yellow')
                                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                        @else
                                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="font-semibold text-xs text-brand-primary">
                                        {{ $item['vocab_passed_modules'] }} / 8 Modul Lulus
                                    </div>
                                    <div class="text-[11px] text-neutral-muted">Rata-rata: {{ $item['vocab_average'] }} pts</div>
                                </td>
                                <td class="px-4 py-3.5">
                                    @if ($item['speaking_status'] === 'completed')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="fa-solid fa-check text-[11px]"></i> Dinilai ({{ $item['speaking_grade'] }})
                                        </span>
                                    @elseif ($item['speaking_status'] === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-accent-soft text-amber-accent-text border border-amber-accent/30">
                                            <i class="fa-solid fa-hourglass-half text-[11px] text-amber-accent"></i> Menunggu Sesi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-neutral-subtle text-neutral-muted border border-neutral-border">
                                            Belum Booking
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5">
                                    @if ($item['tryout_average'] > 0)
                                        <div class="font-extrabold text-sm text-neutral-text">{{ $item['tryout_average'] }}</div>
                                        <span class="text-[10px] text-emerald-600 font-semibold">Kompetitif PTN</span>
                                    @else
                                        <span class="text-xs text-neutral-muted">Belum ada skor</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-right">
                                    <a href="{{ route('guru.bimbingan.student.profile', $s->id) }}" class="inline-flex items-center gap-1 text-xs text-brand-primary hover:text-brand-navy font-semibold transition-colors">
                                        Rincian <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-neutral-muted">
                                    Tidak ada data rekapitulasi target.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
