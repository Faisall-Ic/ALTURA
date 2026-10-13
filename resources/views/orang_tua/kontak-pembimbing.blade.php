<x-layouts.app>
    <x-slot:title>Kontak Tim Pembimbing & Guru BK — Portal Orang Tua</x-slot:title>

    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-neutral-border shadow-xs p-6 sm:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20 mb-3">
                        <span class="w-2 h-2 rounded-full bg-brand-primary animate-pulse"></span>
                        Komunikasi Langsung & Responsif
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-neutral-text">Hubungi Tim Pembimbing Ananda <i class="fa-solid fa-phone ml-1 text-brand-primary"></i></h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Jalur komunikasi langsung via WhatsApp untuk berkonsultasi mengenai perkembangan putra-putri Anda.
                    </p>
                </div>
                <div>
                    <a href="{{ route('ortu.dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold border border-neutral-border transition-all">
                        <i class="fa-solid fa-arrow-left text-[11px]"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- 3 Contact Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- 1. Guru Mentor Card -->
            @php
                $mentorUser = $mentor?->user;
                $mentorName = $mentorUser?->name ?? 'Pak Budi Santoso, M.Pd';
                $mentorSubject = $mentor?->specialization_subject ?? 'Matematika & Penalaran Kuantitatif';
                $childName = $child?->user?->name ?? 'Ananda';
                $mentorWaText = rawurlencode("Halo {$mentorName}, saya orang tua dari {$childName} (Kelas {$child?->academicClass?->name}). Saya ingin menanyakan mengenai perkembangan belajar ananda.");
            @endphp
            <div class="bg-white border border-neutral-border rounded-2xl p-6 flex flex-col justify-between space-y-4 shadow-xs">
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-brand-soft text-brand-primary border border-brand-primary/20 flex items-center justify-center text-xl font-bold">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-brand-primary">Guru Mentor Akademik</span>
                        <h2 class="text-lg font-bold text-neutral-text mt-1">{{ $mentorName }}</h2>
                        <p class="text-xs text-neutral-muted mt-0.5">{{ $mentorSubject }}</p>
                    </div>
                    <div class="p-3 rounded-xl bg-neutral-subtle/50 border border-neutral-border text-xs text-neutral-muted space-y-1">
                        <div>Peran: <span class="text-neutral-text font-medium">Evaluasi Ujian Praktik Speaking & Setoran</span></div>
                        <div>Status: <span class="text-emerald-700 font-semibold">Aktif Membimbing</span></div>
                    </div>
                </div>

                <a href="https://wa.me/6281234567890?text={{ $mentorWaText }}" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center gap-2 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition-all">
                    <i class="fa-brands fa-whatsapp text-sm"></i> Chat WhatsApp Mentor
                </a>
            </div>

            <!-- 2. Guru BK (Kak Nisa) Card -->
            @php
                $counselorName = $counselor?->name ?? 'Kak Nisa, S.Psi';
                $bkWaText = rawurlencode("Halo {$counselorName}, saya orang tua dari {$childName}. Saya ingin berkonsultasi mengenai pemetaan jurusan dan motivasi belajar ananda.");
            @endphp
            <div class="bg-white border border-neutral-border rounded-2xl p-6 flex flex-col justify-between space-y-4 shadow-xs">
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 border border-purple-200 flex items-center justify-center text-xl font-bold">
                        <i class="fa-solid fa-brain"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-purple-600">Guru Bimbingan Konseling (BK)</span>
                        <h2 class="text-lg font-bold text-neutral-text mt-1">{{ $counselorName }}</h2>
                        <p class="text-xs text-neutral-muted mt-0.5">Psikologi Pendidikan & Penjurusan PTN</p>
                    </div>
                    <div class="p-3 rounded-xl bg-neutral-subtle/50 border border-neutral-border text-xs text-neutral-muted space-y-1">
                        <div>Peran: <span class="text-neutral-text font-medium">Monitoring Presensi & Konseling Karir</span></div>
                        <div>Status: <span class="text-emerald-700 font-semibold">Tersedia untuk Konsultasi</span></div>
                    </div>
                </div>

                <a href="https://wa.me/6281234567890?text={{ $bkWaText }}" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center gap-2 py-3 rounded-xl bg-brand-primary hover:bg-brand-navy text-white font-bold text-xs shadow-xs transition-all">
                    <i class="fa-brands fa-whatsapp text-sm"></i> Chat WhatsApp Guru BK
                </a>
            </div>

            <!-- 3. Hotline Bimbel Alto Card -->
            @php
                $hotlineWaText = rawurlencode("Halo Layanan Pelanggan Bimbel Alto, saya ingin menanyakan informasi administrasi / jadwal kelas.");
            @endphp
            <div class="bg-white border border-neutral-border rounded-2xl p-6 flex flex-col justify-between space-y-4 shadow-xs">
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-amber-accent-soft text-amber-accent-text border border-amber-accent/30 flex items-center justify-center text-xl font-bold">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-accent-text">Layanan Informasi Akademik Alto</span>
                        <h2 class="text-lg font-bold text-neutral-text mt-1">Hotline Admin ALTURA</h2>
                        <p class="text-xs text-neutral-muted mt-0.5">Operasional Senin - Sabtu (08.00 - 20.00 WIB)</p>
                    </div>
                    <div class="p-3 rounded-xl bg-neutral-subtle/50 border border-neutral-border text-xs text-neutral-muted space-y-1">
                        <div>Layanan: <span class="text-neutral-text font-medium">Administrasi, Presensi & Info Kelas</span></div>
                        <div>Nomor: <span class="font-mono text-neutral-text font-semibold">+62 812-3456-7890</span></div>
                    </div>
                </div>

                <a href="https://wa.me/6281234567890?text={{ $hotlineWaText }}" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center gap-2 py-3 rounded-xl bg-neutral-subtle hover:bg-neutral-border text-neutral-text font-bold text-xs border border-neutral-border transition-all">
                    <i class="fa-brands fa-whatsapp text-sm text-emerald-600"></i> WhatsApp Hotline Alto
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
