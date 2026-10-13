<x-layouts.app>
    <x-slot:title>Manajemen Orang Tua / Wali</x-slot:title>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-neutral-border">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-neutral-text flex items-center gap-2">
                    <i class="fa-solid fa-user-group text-brand-primary"></i>
                    <span>Master Orang Tua / Wali</span>
                </h1>
                <p class="text-xs text-neutral-muted mt-1">
                    Kelola akun portal wali murid, kontak WhatsApp aktif, dan pemetaan anak asuh (siswa).
                </p>
            </div>
            <div>
                <a href="{{ route('admin.orang-tua.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-colors">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    <span>Tambah Akun Orang Tua</span>
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
        <div class="bg-white border border-neutral-border rounded-2xl p-4 shadow-xs">
            <form method="GET" action="{{ route('admin.orang-tua.index') }}" class="flex items-center gap-2.5">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-neutral-muted text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau no handphone wali..."
                        class="w-full pl-9 pr-4 py-2 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl bg-neutral-subtle hover:bg-neutral-200 text-neutral-text text-xs font-semibold transition-colors">
                    Cari
                </button>
            </form>
        </div>

        <!-- Data Table -->
        <div class="bg-white border border-neutral-border rounded-2xl shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-neutral-text">
                    <thead class="bg-canvas border-b border-neutral-border text-neutral-muted uppercase tracking-wider font-semibold text-[11px]">
                        <tr>
                            <th class="px-5 py-3.5">Nama Wali</th>
                            <th class="px-5 py-3.5">Email &amp; No. WhatsApp</th>
                            <th class="px-5 py-3.5">Profesi / Alamat</th>
                            <th class="px-5 py-3.5">Siswa Terkait (Anak)</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-border">
                        @forelse ($parents as $p)
                            <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                <td class="px-5 py-3.5">
                                    <span class="font-bold text-neutral-text block">{{ $p->user?->name ?? '-' }}</span>
                                    <span class="text-[10px] text-neutral-muted block">Akun Portal Aktif</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="font-mono text-neutral-text block">{{ $p->user?->email ?? '-' }}</span>
                                    <span class="font-mono text-neutral-muted text-[11px] block mt-0.5">
                                        <i class="fa-brands fa-whatsapp text-emerald-600 mr-1"></i>{{ $p->phone_number ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="text-neutral-text block font-medium">{{ $p->occupation ?? '-' }}</span>
                                    <span class="text-[11px] text-neutral-muted block truncate max-w-xs">{{ $p->address ?? '-' }}</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="space-y-1">
                                        @forelse ($p->students as $std)
                                            <div class="flex items-center gap-1.5">
                                                <span class="px-2 py-0.5 rounded-md bg-brand-soft text-brand-primary font-bold text-[10px]">
                                                    {{ $std->pivot->relationship_type ?? 'Anak' }}
                                                </span>
                                                <span class="font-medium text-neutral-text">{{ $std->user?->name }}</span>
                                                <span class="text-neutral-muted text-[10px]">({{ $std->academicClass?->name }})</span>
                                            </div>
                                        @empty
                                            <span class="text-neutral-muted italic text-[11px]">Belum ditautkan ke siswa</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.orang-tua.edit', $p) }}"
                                            class="p-1.5 rounded-lg text-neutral-muted hover:text-brand-primary hover:bg-neutral-subtle transition-colors"
                                            title="Edit Data">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </a>
                                        <form action="{{ route('admin.orang-tua.destroy', $p) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun orang tua ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 rounded-lg text-neutral-muted hover:text-red-accent hover:bg-red-50 transition-colors"
                                                title="Hapus Akun">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-neutral-muted">
                                    <i class="fa-solid fa-user-slash text-3xl mb-2 block opacity-40"></i>
                                    <span>Belum ada data akun orang tua yang terdaftar.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($parents->hasPages())
                <div class="p-4 border-t border-neutral-border">
                    {{ $parents->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
