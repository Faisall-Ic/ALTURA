<x-layouts.app>
    <x-slot:title>Dashboard Guru &amp; Mentor</x-slot:title>

    <div class="space-y-6">
        <!-- Hero Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-neutral-border/80 p-6 sm:p-8 shadow-sm">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-brand-blue via-accent-gold to-brand-blue"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-brand-blue border border-blue-200 mb-3">
                        <span class="w-2 h-2 rounded-full bg-brand-blue animate-pulse"></span>
                        <i class="fa-solid fa-chalkboard-user text-[10px]"></i>
                        Portal Instruktur &amp; Pembimbing
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-neutral-dark tracking-tight">Selamat Datang, {{ $user->name }}!</h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Bidang: <span class="text-brand-blue font-bold">{{ $teacher?->specialization_subject ?? 'Umum' }}</span> • NIP: <span class="font-mono text-neutral-dark font-medium">{{ $teacher?->nip ?? '-' }}</span>
                    </p>
                </div>

                <div class="bg-[#FFFAF3] border border-neutral-border rounded-xl p-4 text-xs space-y-1.5 min-w-[240px] shadow-2xs">
                    <div class="text-neutral-muted">Total Binaan (Mentee): <span class="text-neutral-dark font-bold text-sm">{{ $teacher?->mentees?->count() ?? 0 }} Siswa</span></div>
                    <div class="text-neutral-muted">Tugas: <span class="text-brand-blue font-bold">Presensi QR, Oral Rubric, Tentoring</span></div>
                </div>
            </div>
        </div>

        <!-- Mentee Group Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-border/60 p-6">
            <h2 class="text-lg font-bold text-neutral-dark mb-4 flex items-center gap-2.5">
                <i class="fa-solid fa-users text-brand-blue"></i>
                Daftar Siswa Bimbingan (Mentees)
            </h2>

            @if ($teacher && $teacher->mentees->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-neutral-dark">
                        <thead class="text-xs uppercase bg-[#FFFAF3] text-neutral-muted border-b border-neutral-border">
                            <tr>
                                <th class="px-4 py-3 font-bold">Nama Siswa</th>
                                <th class="px-4 py-3 font-bold">NISN</th>
                                <th class="px-4 py-3 font-bold">Kelas</th>
                                <th class="px-4 py-3 font-bold">No. HP Orang Tua</th>
                                <th class="px-4 py-3 font-bold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-border">
                            @foreach ($teacher->mentees as $mentee)
                                <tr class="hover:bg-[#FFFAF3]/60 transition-colors">
                                    <td class="px-4 py-3 font-bold text-brand-blue">{{ $mentee->user?->name }}</td>
                                    <td class="px-4 py-3 font-mono text-xs">{{ $mentee->nisn }}</td>
                                    <td class="px-4 py-3 font-medium">{{ $mentee->academicClass?->name }}</td>
                                    <td class="px-4 py-3 text-neutral-muted font-mono text-xs">{{ $mentee->parent_phone_number }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="fa-solid fa-circle-check text-[10px]"></i> Aktif
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-neutral-muted text-sm">Belum ada siswa binaan yang dialokasikan.</p>
            @endif
        </div>
    </div>
</x-layouts.app>
