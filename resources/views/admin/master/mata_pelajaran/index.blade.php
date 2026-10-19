<x-layouts.app>
    <x-slot:title>Manajemen Mata Pelajaran</x-slot:title>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-neutral-border">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-neutral-text flex items-center gap-2">
                    <i class="fa-solid fa-book-bookmark text-brand-primary"></i>
                    <span>Master Mata Pelajaran</span>
                </h1>
                <p class="text-xs text-neutral-muted mt-1">
                    Kelola daftar kurikulum mata pelajaran (Subtes Inti SNBT vs TKA Akademik) dan pemetaan jurusan.
                </p>
            </div>
            <div>
                <a href="{{ route('admin.mata-pelajaran.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-colors">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Mata Pelajaran</span>
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filter & Search Bar -->
        <div class="bg-white border border-neutral-border rounded-2xl p-4 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3">
            <form method="GET" action="{{ route('admin.mata-pelajaran.index') }}" class="flex flex-wrap items-center gap-2.5 w-full">
                <div class="relative flex-1 min-w-[200px]">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-neutral-muted text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau kode mapel..."
                        class="w-full pl-9 pr-4 py-2 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                </div>

                <select name="type" class="px-3 py-2 rounded-xl bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                    <option value="">-- Semua Kategori --</option>
                    <option value="snbt" {{ request('type') === 'snbt' ? 'selected' : '' }}>SNBT Core (Non-TKA)</option>
                    <option value="tka" {{ request('type') === 'tka' ? 'selected' : '' }}>TKA Saintek / Soshum</option>
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl bg-neutral-subtle hover:bg-neutral-200 text-neutral-text text-xs font-semibold transition-colors">
                    Filter
                </button>

                @if (request()->hasAny(['search', 'type']))
                    <a href="{{ route('admin.mata-pelajaran.index') }}" class="text-xs text-neutral-muted hover:text-neutral-text underline">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Data Table -->
        <div class="bg-white border border-neutral-border rounded-2xl shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-neutral-text">
                    <thead class="bg-canvas border-b border-neutral-border text-neutral-muted uppercase tracking-wider font-semibold text-[11px]">
                        <tr>
                            <th class="px-5 py-3.5">Kode</th>
                            <th class="px-5 py-3.5">Nama Mata Pelajaran</th>
                            <th class="px-5 py-3.5">Kategori / Sifat</th>
                            <th class="px-5 py-3.5">Jurusan Terkait</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-border">
                        @forelse ($subjects as $s)
                            <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                <td class="px-5 py-3.5 font-mono font-bold text-brand-primary">
                                    {{ $s->code }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="font-bold text-neutral-text block">{{ $s->name }}</span>
                                    @if ($s->description)
                                        <span class="text-[11px] text-neutral-muted block truncate max-w-xs">{{ $s->description }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    @if ($s->is_tka)
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                            TKA (Peminatan)
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-brand-soft text-brand-primary border border-blue-200">
                                            SNBT Core (Wajib)
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse ($s->majors as $m)
                                            <span class="px-2 py-0.5 rounded-md bg-neutral-subtle border border-neutral-border text-[10px] font-semibold text-neutral-text">
                                                {{ $m->code }}
                                            </span>
                                        @empty
                                            <span class="text-neutral-muted italic text-[11px]">Belum ditautkan</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.mata-pelajaran.edit', $s) }}"
                                            class="p-1.5 rounded-lg text-neutral-muted hover:text-brand-primary hover:bg-neutral-subtle transition-colors"
                                            title="Edit Mapel">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </a>
                                        <form action="{{ route('admin.mata-pelajaran.destroy', $s) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 rounded-lg text-neutral-muted hover:text-red-accent hover:bg-red-50 transition-colors"
                                                title="Hapus Mapel">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-neutral-muted">
                                    <i class="fa-solid fa-inbox text-3xl mb-2 block opacity-40"></i>
                                    <span>Belum ada data mata pelajaran yang sesuai.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($subjects->hasPages())
                <div class="p-4 border-t border-neutral-border">
                    {{ $subjects->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
