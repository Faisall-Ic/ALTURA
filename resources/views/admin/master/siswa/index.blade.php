<x-layouts.app>
    <x-slot:title>Master Data Siswa</x-slot:title>

    <div class="space-y-6">
        <!-- Header & Action Buttons -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-text flex items-center gap-2">
                    <span><i class="fa-solid fa-users text-brand-primary"></i> Master Data Siswa</span>
                </h1>
                <p class="text-xs text-neutral-muted mt-1">
                    Kelola data akademik siswa, alokasi mentor terkunci, guru BK, dan impor/ekspor data massal.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.siswa.export') }}"
                    class="inline-flex items-center justify-center px-3.5 py-2 rounded-xl text-xs font-semibold text-neutral-text bg-neutral-subtle hover:bg-neutral-border/60 border border-neutral-border transition-colors">
                    <i class="fa-solid fa-download mr-1.5 text-emerald-600"></i>
                    Ekspor CSV
                </a>

                <button type="button" onclick="document.getElementById('import-drawer').classList.toggle('hidden')"
                    class="inline-flex items-center justify-center px-3.5 py-2 rounded-xl text-xs font-semibold text-neutral-text bg-neutral-subtle hover:bg-neutral-border/60 border border-neutral-border transition-colors cursor-pointer">
                    <i class="fa-solid fa-upload mr-1.5 text-brand-primary"></i>
                    Impor CSV
                </button>

                <a href="{{ route('admin.siswa.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-all">
                    <i class="fa-solid fa-plus mr-1.5"></i>
                    Tambah Siswa
                </a>
            </div>
        </div>

        <!-- Collapsible Import Panel -->
        <div id="import-drawer" class="hidden bg-white border border-brand-primary/30 rounded-2xl p-6 shadow-sm space-y-4">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-sm font-bold text-neutral-text flex items-center gap-2">
                        <i class="fa-solid fa-file-csv text-brand-primary"></i>
                        Impor Data Siswa Secara Massal (Format CSV)
                    </h3>
                    <p class="text-xs text-neutral-muted mt-1">
                        Format kolom CSV wajib urut: <code class="px-1.5 py-0.5 rounded bg-neutral-subtle text-brand-primary font-mono text-[11px] border border-neutral-border">nama,email,nisn,kode_jurusan,kode_kelas,nip_mentor,email_bk,no_hp_ortu,password</code>
                    </p>
                </div>
                <button type="button" onclick="document.getElementById('import-drawer').classList.add('hidden')" class="text-neutral-muted hover:text-neutral-text">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-3">
                @csrf
                <input type="file" name="file" accept=".csv,text/csv" required
                    class="block w-full text-xs text-neutral-muted file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-soft file:text-brand-primary hover:file:bg-brand-soft/80 cursor-pointer">
                <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-brand-primary hover:bg-brand-navy text-white text-xs font-semibold rounded-xl transition-all shrink-0">
                    Mulai Impor Sekarang
                </button>
            </form>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white border border-neutral-border rounded-xl p-4 shadow-xs">
            <form action="{{ route('admin.siswa.index') }}" method="GET" class="flex flex-col lg:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text" name="q" value="{{ $search }}"
                        placeholder="Cari berdasarkan nama, NISN, email, atau nomor HP orang tua..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                    <i class="fa-solid fa-magnifying-glass text-neutral-muted absolute left-3.5 top-3 text-xs"></i>
                </div>

                <div class="flex flex-wrap sm:flex-nowrap gap-2">
                    <select name="major_id" class="px-3 py-2 rounded-lg bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                        <option value="">-- Semua Jurusan --</option>
                        @foreach ($majors as $m)
                            <option value="{{ $m->id }}" {{ (string)$majorId === (string)$m->id ? 'selected' : '' }}>
                                {{ $m->name }} ({{ $m->code }})
                            </option>
                        @endforeach
                    </select>

                    <select name="class_id" class="px-3 py-2 rounded-lg bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                        <option value="">-- Semua Kelas --</option>
                        @foreach ($classes as $c)
                            <option value="{{ $c->id }}" {{ (string)$classId === (string)$c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status" class="px-3 py-2 rounded-lg bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                        <option value="">-- Status --</option>
                        <option value="1" {{ $status === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ $status === '0' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>

                    <button type="submit" class="px-4 py-2 bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold rounded-lg transition-colors border border-neutral-border">
                        Filter
                    </button>
                    @if($search || $majorId || $classId || $status !== null)
                        <a href="{{ route('admin.siswa.index') }}" class="px-3 py-2 bg-neutral-subtle/60 hover:bg-neutral-subtle text-neutral-muted text-xs rounded-lg flex items-center border border-neutral-border">
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
                            <th class="px-5 py-3.5">Nama Siswa</th>
                            <th class="px-5 py-3.5">NISN / Token QR</th>
                            <th class="px-5 py-3.5">Jurusan & Rombel</th>
                            <th class="px-5 py-3.5">Mentor (Terkunci)</th>
                            <th class="px-5 py-3.5">Guru BK</th>
                            <th class="px-5 py-3.5">HP Orang Tua</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-border">
                        @forelse ($students as $student)
                            <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-neutral-text">{{ $student->user?->name }}</div>
                                    <div class="text-[11px] text-neutral-muted font-mono">{{ $student->user?->email }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-mono text-brand-primary font-bold">{{ $student->nisn }}</div>
                                    <div class="text-[10px] text-neutral-muted font-mono">{{ $student->qr_identity_token }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-medium text-neutral-text">{{ $student->academicClass?->name }}</div>
                                    <div class="text-[11px] text-neutral-muted">{{ $student->major?->name }}</div>
                                </td>
                                <td class="px-5 py-4 text-brand-primary font-medium">
                                    {{ $student->mentor?->user?->name }}
                                </td>
                                <td class="px-5 py-4 text-purple-700 font-medium">
                                    {{ $student->counselor?->name }}
                                </td>
                                <td class="px-5 py-4 font-mono text-[11px] text-neutral-muted">
                                    {{ $student->parent_phone_number }}
                                </td>
                                <td class="px-5 py-4">
                                    @if ($student->status_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-red-accent-soft text-red-accent-text border border-red-accent/30">
                                            Non-Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right space-x-1">
                                    <a href="{{ route('admin.siswa.edit', $student) }}"
                                        class="inline-block p-1.5 text-neutral-muted hover:text-brand-primary hover:bg-neutral-subtle rounded-lg transition-colors">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.siswa.destroy', $student) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data siswa ini?')">
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
                                <td colspan="8" class="px-5 py-8 text-center text-neutral-muted">
                                    Belum ada data siswa yang sesuai dengan kriteria filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($students->hasPages())
                <div class="p-4 border-t border-neutral-border">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
