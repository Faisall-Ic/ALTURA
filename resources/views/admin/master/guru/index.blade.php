<x-layouts.app>
    <x-slot:title>Master Data Guru & Mentor</x-slot:title>

    <div class="space-y-6">
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-text flex items-center gap-2">
                    <span><i class="fa-solid fa-chalkboard-user text-brand-primary"></i> Master Data Guru & Mentor</span>
                </h1>
                <p class="text-xs text-neutral-muted mt-1">
                    Kelola profil instruktur, pemetaan multi-subject, penugasan multi-class, dan mentor binaan.
                </p>
            </div>
            <a href="{{ route('admin.guru.create') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-all">
                <i class="fa-solid fa-plus mr-1.5"></i>
                Tambah Guru / Mentor
            </a>
        </div>

        <!-- Search Bar -->
        <div class="bg-white border border-neutral-border rounded-xl p-4 shadow-xs">
            <form action="{{ route('admin.guru.index') }}" method="GET" class="flex gap-3">
                <div class="relative flex-1">
                    <input type="text" name="q" value="{{ $search }}"
                        placeholder="Cari berdasarkan nama guru, email, NIP, no HP, atau mata pelajaran..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                    <i class="fa-solid fa-magnifying-glass text-neutral-muted absolute left-3.5 top-3 text-xs"></i>
                </div>
                <button type="submit" class="px-4 py-2 bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold rounded-lg transition-colors border border-neutral-border">
                    Cari
                </button>
                @if($search)
                    <a href="{{ route('admin.guru.index') }}" class="px-3 py-2 bg-neutral-subtle/60 hover:bg-neutral-subtle text-neutral-muted text-xs rounded-lg flex items-center border border-neutral-border">
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
                            <th class="px-5 py-3.5">Nama Guru / Mentor</th>
                            <th class="px-5 py-3.5">Kontak & NIP</th>
                            <th class="px-5 py-3.5">Mata Pelajaran Diampu</th>
                            <th class="px-5 py-3.5">Kelas Diampu</th>
                            <th class="px-5 py-3.5">Siswa Mentee</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-border">
                        @forelse ($teachers as $teacher)
                            <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-neutral-text">{{ $teacher->user?->name }}</div>
                                    <div class="text-[11px] text-neutral-muted font-mono mt-0.5">{{ $teacher->user?->email }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-mono text-brand-primary font-bold">{{ $teacher->nip ?? '-' }}</div>
                                    <div class="text-[11px] text-neutral-muted mt-0.5">{{ $teacher->phone_number ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @forelse ($teacher->subjects as $subj)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20">
                                                {{ $subj->code }}
                                            </span>
                                        @empty
                                            <span class="text-neutral-muted text-[11px]">{{ $teacher->specialization_subject ?? 'Belum ada mapel' }}</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @forelse ($teacher->academicClasses as $cls)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-neutral-subtle text-neutral-text border border-neutral-border">
                                                {{ $cls->name }}
                                            </span>
                                        @empty
                                            <span class="text-neutral-muted text-[11px]">-</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-5 py-4 font-bold text-neutral-text">
                                    {{ $teacher->mentees_count }} Siswa
                                </td>
                                <td class="px-5 py-4 text-right space-x-1">
                                    <a href="{{ route('admin.guru.edit', $teacher) }}"
                                        class="inline-block p-1.5 text-neutral-muted hover:text-brand-primary hover:bg-neutral-subtle rounded-lg transition-colors">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.guru.destroy', $teacher) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus guru ini?')">
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
                                    Belum ada data guru/mentor yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($teachers->hasPages())
                <div class="p-4 border-t border-neutral-border">
                    {{ $teachers->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
