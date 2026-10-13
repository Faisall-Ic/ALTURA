<x-layouts.app>
    <x-slot:title>Pengumpulan Tugas: {{ $assignment->title }}</x-slot:title>

    <div class="space-y-6">
        <div>
            <a href="{{ route('admin.tugas.index') }}" class="inline-flex items-center gap-1.5 text-xs text-brand-primary hover:text-brand-navy font-semibold mb-2 transition-colors">
                <i class="fa-solid fa-arrow-left text-[11px]"></i> Kembali ke Daftar Tugas
            </a>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-neutral-text">Pengumpulan Tugas Siswa</h1>
                    <p class="text-xs text-neutral-muted mt-1">
                        {{ $assignment->title }} &bull; {{ $assignment->academicClass?->name }} &bull; {{ $assignment->subject?->name }}
                    </p>
                </div>
                <div class="text-xs text-neutral-muted font-mono">
                    Tenggat: <span class="font-bold text-neutral-text">{{ $assignment->due_date?->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Submissions Table -->
        <div class="bg-white border border-neutral-border rounded-2xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-neutral-text">
                    <thead class="bg-neutral-subtle/70 text-neutral-muted uppercase tracking-wider border-b border-neutral-border font-semibold">
                        <tr>
                            <th class="px-5 py-3.5">Nama Siswa & NISN</th>
                            <th class="px-5 py-3.5">Waktu Submit</th>
                            <th class="px-5 py-3.5">Catatan / Jawaban</th>
                            <th class="px-5 py-3.5">Nilai & Feedback</th>
                            <th class="px-5 py-3.5 text-right">Aksi Penilaian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-border">
                        @forelse ($submissions as $sub)
                            <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="font-bold text-neutral-text">{{ $sub->student?->user?->name ?? 'Siswa' }}</div>
                                    <div class="text-[11px] font-mono text-neutral-muted mt-0.5">NISN: {{ $sub->student?->nisn ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-mono text-neutral-text">{{ $sub->submitted_at?->format('d/m/Y H:i') ?? '-' }}</div>
                                    @if ($sub->submitted_at && $assignment->due_date && $sub->submitted_at->gt($assignment->due_date))
                                        <span class="text-[10px] text-red-accent font-semibold">Terlambat</span>
                                    @else
                                        <span class="text-[10px] text-emerald-600 font-semibold">Tepat Waktu</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 max-w-xs">
                                    @if ($sub->student_note)
                                        <p class="text-neutral-text text-[11px] line-clamp-2">{{ $sub->student_note }}</p>
                                    @endif
                                    @if ($sub->submission_file_path)
                                        <a href="{{ asset($sub->submission_file_path) }}" target="_blank"
                                            class="inline-flex items-center gap-1 text-[11px] text-brand-primary hover:underline font-semibold mt-1">
                                            <i class="fa-solid fa-paperclip text-[10px]"></i> Lihat Berkas Tugas
                                        </a>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @if ($sub->grade !== null)
                                        <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold font-mono text-xs">
                                            Nilai: {{ number_format($sub->grade, 1) }}
                                        </div>
                                        @if ($sub->feedback)
                                            <div class="text-[11px] text-neutral-muted mt-1 italic line-clamp-1">"{{ $sub->feedback }}"</div>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                            Belum Dinilai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <form action="{{ route('admin.tugas.grade', $sub->id) }}" method="POST" class="inline-flex items-center gap-2">
                                        @csrf
                                        <input type="number" step="0.1" min="0" max="100" name="grade" value="{{ old('grade', $sub->grade) }}" required
                                            placeholder="Skor"
                                            class="w-16 px-2 py-1 rounded-lg bg-surface border border-neutral-border text-neutral-text text-xs text-center font-mono focus:outline-none focus:border-brand-primary">
                                        <input type="text" name="feedback" value="{{ old('feedback', $sub->feedback) }}"
                                            placeholder="Catatan guru..."
                                            class="w-32 px-2 py-1 rounded-lg bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-brand-primary hover:bg-brand-navy text-white text-xs font-semibold shadow-xs transition-colors">
                                            Simpan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-neutral-muted">
                                    Belum ada siswa yang mengumpulkan tugas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($submissions->hasPages())
                <div class="p-4 border-t border-neutral-border">
                    {{ $submissions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
