<x-layouts.app>
    <x-slot:title>Master Data Kelas</x-slot:title>

    <div class="space-y-6">
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-text flex items-center gap-2">
                    <span><i class="fa-solid fa-school text-brand-primary"></i> Master Data Kelas (Rombel)</span>
                </h1>
                <p class="text-xs text-neutral-muted mt-1">
                    Kelola rombongan belajar dan pemetaan terhadap jurusan masing-masing.
                </p>
            </div>
            <a href="{{ route('admin.kelas.create') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-all">
                <i class="fa-solid fa-plus mr-1.5"></i>
                Tambah Kelas
            </a>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white border border-neutral-border rounded-xl p-4 shadow-xs">
            <form action="{{ route('admin.kelas.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text" name="q" value="{{ $search }}"
                        placeholder="Cari berdasarkan nama kelas atau kode..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                    <i class="fa-solid fa-magnifying-glass text-neutral-muted absolute left-3.5 top-3 text-xs"></i>
                </div>

                <select name="major_id" class="px-3 py-2 rounded-lg bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                    <option value="">-- Semua Jurusan --</option>
                    @foreach ($majors as $m)
                        <option value="{{ $m->id }}" {{ (string)$majorId === (string)$m->id ? 'selected' : '' }}>
                            {{ $m->name }} ({{ $m->code }})
                        </option>
                    @endforeach
                </select>

                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold rounded-lg transition-colors border border-neutral-border">
                        Filter
                    </button>
                    @if($search || $majorId)
                        <a href="{{ route('admin.kelas.index') }}" class="px-3 py-2 bg-neutral-subtle/60 hover:bg-neutral-subtle text-neutral-muted text-xs rounded-lg flex items-center border border-neutral-border">
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
                            <th class="px-5 py-3.5">Nama Kelas</th>
                            <th class="px-5 py-3.5">Kode Rombel</th>
                            <th class="px-5 py-3.5">Jurusan Induk</th>
                            <th class="px-5 py-3.5">Siswa Terdaftar</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-border">
                        @forelse ($classes as $class)
                            <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                <td class="px-5 py-4 font-semibold text-neutral-text">{{ $class->name }}</td>
                                <td class="px-5 py-4 font-mono text-brand-primary font-bold">{{ $class->code }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20">
                                        {{ $class->major?->name }} ({{ $class->major?->code }})
                                    </span>
                                </td>
                                <td class="px-5 py-4 font-bold text-neutral-text">{{ $class->students_count }} Siswa</td>
                                <td class="px-5 py-4 text-right space-x-1">
                                    <a href="{{ route('admin.kelas.edit', $class) }}"
                                        class="inline-block p-1.5 text-neutral-muted hover:text-brand-primary hover:bg-neutral-subtle rounded-lg transition-colors">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.kelas.destroy', $class) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
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
                                <td colspan="5" class="px-5 py-8 text-center text-neutral-muted">
                                    Belum ada rombongan belajar yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($classes->hasPages())
                <div class="p-4 border-t border-neutral-border">
                    {{ $classes->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
