<x-layouts.app>
    <x-slot:title>Monitoring Kosakata & Ujian Lisan Mentee</x-slot:title>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-text flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-brand-primary"></i>
                    <span>Monitoring English Mastery Siswa Mentee</span>
                </h1>
                <p class="text-xs text-neutral-muted mt-1">
                    Pantau progres kelulusan 8 modul kosakata CBT dan kelayakan setoran ujian lisan siswa bimbingan Anda.
                </p>
            </div>
        </div>

        <!-- Mentee Table -->
        <div class="bg-white border border-neutral-border rounded-2xl overflow-hidden shadow-sm">
            <div class="p-4 border-b border-neutral-border flex items-center justify-between">
                <h2 class="text-sm font-bold text-neutral-text">Daftar Siswa Mentee Bimbingan Anda</h2>
                <span class="text-xs font-mono text-neutral-muted">Total: {{ $mentees->count() }} Siswa</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-neutral-text">
                    <thead class="bg-neutral-subtle/80 text-neutral-muted uppercase tracking-wider border-b border-neutral-border">
                        <tr>
                            <th class="px-5 py-3.5">Nama Siswa</th>
                            <th class="px-5 py-3.5">Kelas / Jurusan</th>
                            <th class="px-5 py-3.5">CBT Vocab (400 Kata)</th>
                            <th class="px-5 py-3.5">Status Ujian Lisan</th>
                            <th class="px-5 py-3.5 text-right">Aksi Mentor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-border">
                        @forelse ($mentees as $mentee)
                            @php
                                $passedVocabCount = $mentee->vocabQuizResults->where('passed', true)->unique('module_number')->count();
                                $pendingBooking = $mentee->speakingBookings->where('status', 'pending')->first();
                                $completedBooking = $mentee->speakingBookings->where('status', 'completed')->sortByDesc('graded_at')->first();
                            @endphp
                            <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                <td class="px-5 py-4">
                                    <a href="{{ route('guru.bimbingan.student.profile', $mentee->id) }}"
                                        class="font-bold text-neutral-text hover:text-brand-primary transition-colors block">
                                        {{ $mentee->user?->name }}
                                    </a>
                                    <span class="text-[11px] font-mono text-neutral-muted">NISN: {{ $mentee->nisn }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="font-medium text-neutral-text">{{ $mentee->academicClass?->name }}</span>
                                    <span class="text-[11px] text-neutral-muted block">{{ $mentee->major?->name }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold {{ $passedVocabCount >= 8 ? 'text-emerald-700' : 'text-amber-accent-text' }}">
                                                {{ $passedVocabCount }} / 8 Modul
                                            </span>
                                            <span class="text-[10px] text-neutral-muted">
                                                ({{ round(($passedVocabCount / 8) * 100) }}%)
                                            </span>
                                        </div>
                                        <div class="w-32 bg-neutral-subtle rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-brand-primary h-full rounded-full"
                                                style="width: {{ round(($passedVocabCount / 8) * 100) }}%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    @if ($pendingBooking)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-accent-soft text-amber-accent-text border border-amber-accent/30">
                                            <span class="inline-block w-2 h-2 rounded-full bg-amber-accent mr-1.5 align-middle"></span> Menunggu Evaluasi ({{ $pendingBooking->booking_date->format('d/m') }})
                                        </span>
                                    @elseif ($completedBooking)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1.5 align-middle"></span> Lulus Skor: {{ $completedBooking->grade }}
                                        </span>
                                    @else
                                        <span class="text-neutral-muted italic text-[11px]">Belum Mendaftar</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right space-x-2">
                                    @if ($pendingBooking)
                                        <a href="{{ route('guru.bimbingan.rubric.edit', $pendingBooking->id) }}"
                                            class="inline-block px-3 py-1 bg-brand-primary hover:bg-brand-navy text-white font-bold rounded-lg text-xs transition-colors shadow-sm">
                                            Nilai Rubrik
                                        </a>
                                    @endif
                                    <a href="{{ route('guru.bimbingan.student.profile', $mentee->id) }}"
                                        class="inline-block px-2.5 py-1 bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text font-semibold rounded-lg text-xs border border-neutral-border transition-colors">
                                        Profil 360°
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-neutral-muted">
                                    Belum ada siswa mentee yang terhubung ke akun Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
