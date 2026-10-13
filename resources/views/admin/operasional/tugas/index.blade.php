<x-layouts.app>
    <x-slot:title>Manajemen Tugas Rumah (PR)</x-slot:title>

    <div class="space-y-6">
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-text flex items-center gap-2">
                    <span><i class="fa-solid fa-book-open text-brand-primary"></i> Tugas Rumah & PR (Homework)</span>
                </h1>
                <p class="text-xs text-neutral-muted mt-1">
                    Kelola penugasan mandiri siswa, tenggat waktu pengumpulan, dan tinjau hasil penilaian tugas.
                </p>
            </div>
            <a href="{{ route('admin.tugas.create') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-all">
                <i class="fa-solid fa-plus mr-1.5"></i>
                Buat Tugas Baru
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white border border-neutral-border rounded-xl p-4 shadow-xs">
            <form action="{{ route('admin.tugas.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div class="relative">
                    <input type="text" name="q" value="{{ $search }}"
                        placeholder="Cari judul tugas..."
                        class="w-full pl-9 pr-3 py-2 rounded-lg bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                    <i class="fa-solid fa-magnifying-glass text-neutral-muted absolute left-3 top-2.5 text-xs"></i>
                </div>

                <select name="class_id" class="px-3 py-2 rounded-lg bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                    <option value="">-- Semua Kelas --</option>
                    @foreach ($classes as $c)
                        <option value="{{ $c->id }}" {{ (string)$classId === (string)$c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>

                <select name="subject_id" class="px-3 py-2 rounded-lg bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                    <option value="">-- Semua Mata Pelajaran --</option>
                    @foreach ($subjects as $s)
                        <option value="{{ $s->id }}" {{ (string)$subjectId === (string)$s->id ? 'selected' : '' }}>
                            {{ $s->name }} ({{ $s->code }})
                        </option>
                    @endforeach
                </select>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold rounded-lg transition-colors border border-neutral-border">
                        Filter
                    </button>
                    @if($classId || $subjectId || $search)
                        <a href="{{ route('admin.tugas.index') }}" class="px-3 py-2 bg-neutral-subtle/60 hover:bg-neutral-subtle text-neutral-muted text-xs rounded-lg flex items-center border border-neutral-border">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white border border-neutral-border rounded-2xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-neutral-text">
                    <thead class="bg-neutral-subtle/70 text-neutral-muted uppercase tracking-wider border-b border-neutral-border font-semibold">
                        <tr>
                            <th class="px-5 py-3.5">Judul Tugas</th>
                            <th class="px-5 py-3.5">Kelas & Mapel</th>
                            <th class="px-5 py-3.5">Guru Pembuat</th>
                            <th class="px-5 py-3.5">Tenggat Waktu (Due Date)</th>
                            <th class="px-5 py-3.5">Pengumpulan</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-border">
                        @forelse ($assignments as $task)
                            <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="font-bold text-neutral-text">{{ $task->title }}</div>
                                    <div class="text-[11px] text-neutral-muted line-clamp-1 mt-0.5">{{ $task->instructions }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-neutral-text">{{ $task->academicClass?->name }}</div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20 mt-1">
                                        {{ $task->subject?->name }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-neutral-text">
                                    {{ $task->teacher?->user?->name ?? 'Admin' }}
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-mono text-neutral-text font-medium">
                                        {{ $task->due_date?->format('d M Y, H:i') }}
                                    </div>
                                    @if ($task->due_date?->isPast())
                                        <span class="text-[10px] text-red-accent font-semibold">Berakhir</span>
                                    @else
                                        <span class="text-[10px] text-emerald-600 font-semibold">Aktif</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <a href="{{ route('admin.tugas.submissions', $task) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text border border-neutral-border transition-colors">
                                        <i class="fa-solid fa-inbox text-[11px] text-brand-primary"></i>
                                        <span>{{ $task->submissions_count }} Siswa</span>
                                    </a>
                                </td>
                                <td class="px-5 py-4 text-right space-x-1">
                                    <a href="{{ route('admin.tugas.edit', $task) }}"
                                        class="inline-block p-1.5 text-neutral-muted hover:text-brand-primary hover:bg-neutral-subtle rounded-lg transition-colors">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.tugas.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tugas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-neutral-muted hover:text-red-accent hover:bg-neutral-subtle rounded-lg transition-colors cursor-pointer">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-neutral-muted">
                                    Belum ada tugas rumah yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($assignments->hasPages())
                <div class="p-4 border-t border-neutral-border">
                    {{ $assignments->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
