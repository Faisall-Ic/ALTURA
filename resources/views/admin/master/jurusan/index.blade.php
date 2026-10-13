<x-layouts.app>
    <x-slot:title>Master Data Jurusan</x-slot:title>

    <div class="space-y-6">
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-text flex items-center gap-2">
                    <span><i class="fa-solid fa-graduation-cap text-brand-primary"></i> Master Data Jurusan</span>
                </h1>
                <p class="text-xs text-neutral-muted mt-1">
                    Kelola program peminatan akademik dan kebijakan kepesertaan SNBT.
                </p>
            </div>
            <a href="{{ route('admin.jurusan.create') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-all">
                <i class="fa-solid fa-plus mr-1.5"></i>
                Tambah Jurusan
            </a>
        </div>

        <!-- Search Bar -->
        <div class="bg-white border border-neutral-border rounded-xl p-4 shadow-xs">
            <form action="{{ route('admin.jurusan.index') }}" method="GET" class="flex gap-3">
                <div class="relative flex-1">
                    <input type="text" name="q" value="{{ $search }}"
                        placeholder="Cari berdasarkan nama atau kode jurusan..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                    <i class="fa-solid fa-magnifying-glass text-neutral-muted absolute left-3.5 top-3 text-xs"></i>
                </div>
                <button type="submit" class="px-4 py-2 bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold rounded-lg transition-colors border border-neutral-border">
                    Cari
                </button>
                @if($search)
                    <a href="{{ route('admin.jurusan.index') }}" class="px-3 py-2 bg-neutral-subtle/60 hover:bg-neutral-subtle text-neutral-muted text-xs rounded-lg flex items-center border border-neutral-border">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white border border-neutral-border rounded-2xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-neutral-text">
                    <thead class="bg-neutral-subtle/70 text-neutral-muted uppercase tracking-wider border-b border-neutral-border font-semibold">
                        <tr>
                            <th class="px-5 py-3.5">Nama Jurusan</th>
                            <th class="px-5 py-3.5">Kode</th>
                            <th class="px-5 py-3.5">Mandatori SNBT</th>
                            <th class="px-5 py-3.5">Total Kelas</th>
                            <th class="px-5 py-3.5">Total Siswa</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-border">
                        @forelse ($majors as $major)
                            <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                <td class="px-5 py-4 font-semibold text-neutral-text">{{ $major->name }}</td>
                                <td class="px-5 py-4 font-mono text-brand-primary font-bold">{{ $major->code }}</td>
                                <td class="px-5 py-4">
                                    @if ($major->is_snbt_mandatory)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Wajib SNBT
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-neutral-subtle text-neutral-muted border border-neutral-border">
                                            Opsional
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">{{ $major->academic_classes_count }} Rombel</td>
                                <td class="px-5 py-4 font-bold text-neutral-text">{{ $major->students_count }} Siswa</td>
                                <td class="px-5 py-4 text-right space-x-1">
                                    <a href="{{ route('admin.jurusan.edit', $major) }}"
                                        class="inline-block p-1.5 text-neutral-muted hover:text-brand-primary hover:bg-neutral-subtle rounded-lg transition-colors">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.jurusan.destroy', $major) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus jurusan ini?')">
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
                                    Belum ada data jurusan yang sesuai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($majors->hasPages())
                <div class="p-4 border-t border-neutral-border">
                    {{ $majors->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
