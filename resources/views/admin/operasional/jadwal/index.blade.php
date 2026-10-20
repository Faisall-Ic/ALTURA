<x-layouts.app>
    <x-slot:title>Jadwal Pelajaran & Sesi Bimbel</x-slot:title>

    <div class="space-y-6">
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-text flex items-center gap-2">
                    <span><i class="fa-solid fa-calendar-days text-brand-primary"></i> Jadwal Pelajaran (Lesson Schedules)</span>
                </h1>
                <p class="text-xs text-neutral-muted mt-1">
                    Kelola alokasi waktu sesi bimbel reguler per rombel, ruang kelas, dan guru pengampu.
                </p>
            </div>
            <a href="{{ route('admin.jadwal.create') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-all">
                <i class="fa-solid fa-plus mr-1.5"></i>
                Tambah Jadwal Pelajaran
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white border border-neutral-border rounded-xl p-4 shadow-xs">
            <form action="{{ route('admin.jadwal.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <select name="class_id" class="px-3 py-2 rounded-lg bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                    <option value="">-- Semua Kelas --</option>
                    @foreach ($classes as $c)
                        <option value="{{ $c->id }}" {{ (string)$classId === (string)$c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>

                <select name="day_of_week" class="px-3 py-2 rounded-lg bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                    <option value="">-- Semua Hari --</option>
                    @foreach ($days as $d)
                        <option value="{{ $d }}" {{ $dayOfWeek === $d ? 'selected' : '' }}>
                            {{ $d }}
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
                    @if($classId || $dayOfWeek || $subjectId)
                        <a href="{{ route('admin.jadwal.index') }}" class="px-3 py-2 bg-neutral-subtle/60 hover:bg-neutral-subtle text-neutral-muted text-xs rounded-lg flex items-center border border-neutral-border">
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
                            <th class="px-5 py-3.5">Hari & Jam</th>
                            <th class="px-5 py-3.5">Kelas</th>
                            <th class="px-5 py-3.5">Mata Pelajaran</th>
                            <th class="px-5 py-3.5">Guru Pengajar</th>
                            <th class="px-5 py-3.5">Ruang Kelas</th>
                            <th class="px-5 py-3.5">Catatan</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-border">
                        @forelse ($schedules as $sched)
                            <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="font-bold text-neutral-text">{{ $sched->day_of_week }}</div>
                                    <div class="text-[11px] font-mono text-brand-primary mt-0.5">
                                        <i class="fa-regular fa-clock text-[10px]"></i> {{ \Illuminate\Support\Str::substr($sched->time_start, 0, 5) }} - {{ \Illuminate\Support\Str::substr($sched->time_end, 0, 5) }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 font-semibold text-neutral-text">
                                    {{ $sched->academicClass?->name }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20">
                                        {{ $sched->subject?->name }} ({{ $sched->subject?->code }})
                                    </span>
                                </td>
                                <td class="px-5 py-4 font-medium text-neutral-text">
                                    {{ $sched->teacher?->user?->name ?? 'Belum Ditentukan' }}
                                </td>
                                <td class="px-5 py-4 font-mono font-medium text-neutral-text">
                                    <i class="fa-solid fa-location-dot text-neutral-muted text-[10px] mr-1"></i>{{ $sched->room }}
                                </td>
                                <td class="px-5 py-4 text-neutral-muted text-[11px] max-w-xs truncate">
                                    {{ $sched->note ?? '-' }}
                                </td>
                                <td class="px-5 py-4 text-right space-x-1">
                                    <a href="{{ route('admin.jadwal.edit', $sched) }}"
                                        class="inline-block p-1.5 text-neutral-muted hover:text-brand-primary hover:bg-neutral-subtle rounded-lg transition-colors">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.jadwal.destroy', $sched) }}" method="POST" class="inline" onsubmit="return confirm('Hapus jadwal pelajaran ini?')">
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
                                <td colspan="7" class="px-5 py-8 text-center text-neutral-muted">
                                    Belum ada jadwal pelajaran yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($schedules->hasPages())
                <div class="p-4 border-t border-neutral-border">
                    {{ $schedules->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
