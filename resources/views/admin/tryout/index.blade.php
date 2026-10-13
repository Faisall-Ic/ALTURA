<x-layouts.app>
    <x-slot:title>Manajemen Paket Tryout SNBT — Admin</x-slot:title>

    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-neutral-border shadow-xs p-6 sm:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20 mb-3">
                        <span class="w-2 h-2 rounded-full bg-brand-primary animate-pulse"></span>
                        Pengaturan Ujian &amp; Auto-Enrollment
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-neutral-text">Manajemen Paket Tryout SNBT <i class="fa-solid fa-bullseye ml-1 text-amber-accent"></i></h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Kelola jadwal tryout akbar, aktivasikan paket ujian, dan pantau partisipasi peserta siswa.
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold border border-neutral-border transition-all">
                        <i class="fa-solid fa-arrow-left text-[11px]"></i> Dashboard Admin
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Table of Tryouts (2 Cols) -->
            <div class="lg:col-span-2 bg-white border border-neutral-border rounded-2xl p-6 shadow-xs">
                <h2 class="text-lg font-bold text-neutral-text mb-4">Daftar Paket Tryout</h2>

                @if ($tryouts->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-neutral-text">
                            <thead class="text-xs uppercase bg-neutral-subtle/70 text-neutral-muted border-b border-neutral-border font-semibold">
                                <tr>
                                    <th class="px-4 py-3">Judul Paket</th>
                                    <th class="px-4 py-3">Periode Pelaksanaan</th>
                                    <th class="px-4 py-3 text-center">Peserta</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-border">
                                @foreach ($tryouts as $to)
                                    <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                        <td class="px-4 py-3.5 font-bold text-neutral-text">
                                            {{ $to->title }}
                                        </td>
                                        <td class="px-4 py-3.5 text-xs text-neutral-muted">
                                            {{ $to->date_start->format('d M Y') }} — {{ $to->date_end->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-3.5 text-center font-bold text-brand-primary">
                                            {{ $to->tryout_scores_count }} Siswa
                                        </td>
                                        <td class="px-4 py-3.5">
                                            @if ($to->is_active)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-neutral-subtle text-neutral-muted border border-neutral-border">
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 text-right">
                                            <form method="POST" action="{{ route('admin.tryout.destroy', $to->id) }}" onsubmit="return confirm('Hapus paket tryout ini?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs text-red-accent hover:text-red-accent-text font-semibold transition-colors">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $tryouts->links() }}
                    </div>
                @else
                    <p class="text-xs text-neutral-muted py-8 text-center">Belum ada paket tryout terdaftar.</p>
                @endif
            </div>

            <!-- Create Form (1 Col) -->
            <div class="space-y-6">
                <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs">
                    <h3 class="text-base font-bold text-neutral-text mb-1 flex items-center gap-1.5"><i class="fa-solid fa-plus text-brand-primary"></i> Buat Paket Tryout Baru</h3>
                    <p class="text-xs text-neutral-muted mb-4">Semua siswa aktif akan otomatis terdaftar via auto-enrollment.</p>

                    <form method="POST" action="{{ route('admin.tryout.store') }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-neutral-text mb-1">Judul Paket Ujian</label>
                            <input type="text" name="title" required placeholder="Contoh: Tryout Akbar UTBK-SNBT Seri 3" class="w-full bg-surface border border-neutral-border rounded-lg p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-text mb-1">Tanggal Mulai</label>
                            <input type="date" name="date_start" required value="{{ now()->toDateString() }}" class="w-full bg-surface border border-neutral-border rounded-lg p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-text mb-1">Tanggal Berakhir</label>
                            <input type="date" name="date_end" required value="{{ now()->addDays(7)->toDateString() }}" class="w-full bg-surface border border-neutral-border rounded-lg p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            <input type="checkbox" name="is_active" id="is_active" value="1" checked class="rounded border-neutral-border text-brand-primary focus:ring-brand-primary">
                            <label for="is_active" class="text-xs font-medium text-neutral-text">Aktifkan untuk Siswa</label>
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl bg-brand-primary hover:bg-brand-navy text-white font-bold text-xs shadow-xs transition-all mt-2">
                            Simpan Paket Tryout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
