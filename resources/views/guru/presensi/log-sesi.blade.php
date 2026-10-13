<x-layouts.app>
    <x-slot:title>Log Presensi Sesi Kelas</x-slot:title>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-text flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-list text-brand-primary"></i>
                    <span>Log Sesi & Presensi Kelas</span>
                </h1>
                <p class="text-xs text-neutral-muted mt-1">
                    Daftar rekapitulasi kehadiran siswa tatap muka dan penyesuaian manual status absensi.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('guru.presensi.scanner') }}"
                    class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-sm transition-all">
                    <i class="fa-solid fa-camera mr-2 text-xs"></i>
                    <span>Buka Scanner Meja</span>
                </a>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white border border-neutral-border rounded-2xl p-5 shadow-sm">
            <form method="GET" action="{{ route('guru.presensi.session.log') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-[11px] font-semibold uppercase text-neutral-muted mb-1.5">Tanggal Sesi</label>
                    <input type="date" name="date" value="{{ $date }}"
                        class="w-full px-3.5 py-2 rounded-xl bg-white border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary focus:ring-1 focus:ring-brand-primary">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold uppercase text-neutral-muted mb-1.5">Kelas / Rombel</label>
                    <select name="class_id"
                        class="w-full px-3.5 py-2 rounded-xl bg-white border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary focus:ring-1 focus:ring-brand-primary">
                        <option value="">Semua Kelas</option>
                        @foreach ($classes as $c)
                            <option value="{{ $c->id }}" {{ (string)$classId === (string)$c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold uppercase text-neutral-muted mb-1.5">Status Presensi</label>
                    <select name="status"
                        class="w-full px-3.5 py-2 rounded-xl bg-white border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary focus:ring-1 focus:ring-brand-primary">
                        <option value="">Semua Status</option>
                        <option value="hadir" {{ $status === 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="terlambat" {{ $status === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        <option value="sakit" {{ $status === 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="izin" {{ $status === 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="alfa" {{ $status === 'alfa' ? 'selected' : '' }}>Alfa</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="w-full py-2 bg-brand-primary hover:bg-brand-navy text-white text-xs font-semibold rounded-xl shadow-sm transition-all cursor-pointer">
                        Filter Log
                    </button>
                    <a href="{{ route('guru.presensi.session.log') }}"
                        class="px-3 py-2 bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold rounded-xl border border-neutral-border transition-all">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Attendance Table -->
        <div class="bg-white border border-neutral-border rounded-2xl overflow-hidden shadow-sm">
            <div class="p-4 border-b border-neutral-border flex items-center justify-between">
                <h2 class="text-sm font-bold text-neutral-text flex items-center gap-2">
                    <span>Log Kehadiran: {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}</span>
                </h2>
                <span class="text-xs text-neutral-muted font-mono">
                    Total: {{ $attendances->total() }} Catatan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-neutral-text">
                    <thead class="bg-neutral-subtle/80 text-neutral-muted uppercase tracking-wider border-b border-neutral-border">
                        <tr>
                            <th class="px-5 py-3.5">Waktu</th>
                            <th class="px-5 py-3.5">Nama Siswa</th>
                            <th class="px-5 py-3.5">Kelas / Jurusan</th>
                            <th class="px-5 py-3.5">Status Presensi</th>
                            <th class="px-5 py-3.5">Dipindai Oleh</th>
                            <th class="px-5 py-3.5">Keterangan</th>
                            <th class="px-5 py-3.5 text-right">Penyesuaian Manual</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-border">
                        @forelse ($attendances as $row)
                            <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                <td class="px-5 py-4 font-mono text-neutral-muted">
                                    {{ substr($row->session_time, 0, 5) }} WIB
                                </td>
                                <td class="px-5 py-4 font-semibold text-neutral-text">
                                    <a href="{{ route('guru.bimbingan.student.profile', $row->student_id) }}"
                                        class="hover:text-brand-primary transition-colors flex items-center gap-1.5 group">
                                        <span>{{ $row->student?->user?->name ?? 'Siswa' }}</span>
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-neutral-muted group-hover:text-brand-primary"></i>
                                    </a>
                                    <span class="text-[11px] text-neutral-muted font-mono block">NISN: {{ $row->student?->nisn }}</span>
                                </td>
                                <td class="px-5 py-4 text-neutral-text">
                                    <span class="font-medium">{{ $row->student?->academicClass?->name ?? '-' }}</span>
                                    <span class="text-[11px] text-neutral-muted block">{{ $row->student?->major?->name ?? '-' }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $badgeClasses = [
                                            'hadir' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'terlambat' => 'bg-amber-accent-soft text-amber-accent-text border-amber-accent/30',
                                            'sakit' => 'bg-brand-soft text-brand-primary border-brand-primary/20',
                                            'izin' => 'bg-brand-soft text-brand-navy border-brand-navy/20',
                                            'alfa' => 'bg-red-accent-soft text-red-accent-text border-red-accent/30',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border {{ $badgeClasses[$row->status] ?? 'bg-neutral-subtle text-neutral-muted border-neutral-border' }}">
                                        {{ ucfirst($row->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-brand-primary">
                                    {{ $row->scannedBy?->user?->name ?? 'Sistem' }}
                                </td>
                                <td class="px-5 py-4 text-neutral-muted italic">
                                    {{ $row->remarks ?: '-' }}
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <!-- Inline Quick Status Adjustment Form -->
                                    <form action="{{ route('guru.presensi.update', $row->id) }}" method="POST" class="inline-flex items-center gap-1.5 justify-end">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="px-2 py-1 bg-white border border-neutral-border rounded-lg text-neutral-text text-xs focus:border-brand-primary">
                                            <option value="hadir" {{ $row->status === 'hadir' ? 'selected' : '' }}>Hadir</option>
                                            <option value="terlambat" {{ $row->status === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                                            <option value="sakit" {{ $row->status === 'sakit' ? 'selected' : '' }}>Sakit</option>
                                            <option value="izin" {{ $row->status === 'izin' ? 'selected' : '' }}>Izin</option>
                                            <option value="alfa" {{ $row->status === 'alfa' ? 'selected' : '' }}>Alfa</option>
                                        </select>
                                        <input type="text" name="remarks" value="{{ $row->remarks }}" placeholder="Catatan..."
                                            class="w-24 px-2 py-1 bg-white border border-neutral-border rounded-lg text-neutral-text text-xs focus:border-brand-primary">
                                        <button type="submit"
                                            class="px-2.5 py-1 bg-neutral-subtle hover:bg-neutral-border/60 text-brand-primary font-semibold rounded-lg text-xs border border-neutral-border transition-colors cursor-pointer"
                                            title="Simpan Perubahan">
                                            Ubah
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-neutral-muted">
                                    Tidak ada data presensi pada tanggal dan filter yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($attendances->hasPages())
                <div class="p-4 border-t border-neutral-border">
                    {{ $attendances->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
