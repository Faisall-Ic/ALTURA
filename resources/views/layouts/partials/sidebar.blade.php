<!-- ALTURA Apple-Inspired Clean White Sidebar -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white text-neutral-text flex flex-col transition-transform duration-300 ease-in-out border-r border-neutral-border shadow-xs">
    
    <!-- Brand Header -->
    <div class="h-16 flex items-center justify-between px-5 border-b border-neutral-border bg-white">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-xl bg-brand-primary text-white p-0.5 shadow-sm flex items-center justify-center shrink-0">
                <span class="font-black text-xl tracking-tighter">A</span>
            </div>
            <div class="min-w-0">
                <span class="text-lg font-extrabold tracking-tight text-brand-dark block leading-tight">ALTURA</span>
                <span class="text-[10px] text-neutral-muted tracking-wider uppercase font-semibold block truncate">Alumni Tutorial</span>
            </div>
        </a>
        <button @click="sidebarOpen = false" class="lg:hidden text-neutral-muted hover:text-neutral-text p-1.5 rounded-lg hover:bg-neutral-subtle transition-colors">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <!-- Navigation Blocks per Role (Scrollable) -->
    <div class="flex-1 overflow-y-auto px-3.5 py-4 space-y-5">
        @role('admin')
            <!-- Admin Navigation Blocks -->
            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Navigasi Utama</p>
                <nav class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-gauge-high w-5 text-center mr-2.5 {{ request()->routeIs('admin.dashboard') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Dashboard</span>
                    </a>
                </nav>
            </div>

            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Master Data</p>
                <nav class="space-y-1">
                    <a href="{{ route('admin.jurusan.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.jurusan.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-graduation-cap w-5 text-center mr-2.5 {{ request()->routeIs('admin.jurusan.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Jurusan Peminatan</span>
                    </a>
                    <a href="{{ route('admin.kelas.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.kelas.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-school w-5 text-center mr-2.5 {{ request()->routeIs('admin.kelas.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Kelas / Rombel</span>
                    </a>
                    <a href="{{ route('admin.mata-pelajaran.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.mata-pelajaran.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-book-bookmark w-5 text-center mr-2.5 {{ request()->routeIs('admin.mata-pelajaran.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Mata Pelajaran (Mapel)</span>
                    </a>
                    <a href="{{ route('admin.guru.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.guru.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-chalkboard-user w-5 text-center mr-2.5 {{ request()->routeIs('admin.guru.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Guru &amp; Mentor</span>
                    </a>
                    <a href="{{ route('admin.orang-tua.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.orang-tua.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-hands-holding-child w-5 text-center mr-2.5 {{ request()->routeIs('admin.orang-tua.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Orang Tua / Wali</span>
                    </a>
                    <a href="{{ route('admin.siswa.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.siswa.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-users w-5 text-center mr-2.5 {{ request()->routeIs('admin.siswa.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Data Siswa</span>
                    </a>
                </nav>
            </div>

            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Operasional Akademik</p>
                <nav class="space-y-1">
                    <a href="{{ route('admin.jadwal.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.jadwal.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-calendar-days w-5 text-center mr-2.5 {{ request()->routeIs('admin.jadwal.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Jadwal Pelajaran</span>
                    </a>
                    <a href="{{ route('admin.presensi.scanner') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.presensi.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-qrcode w-5 text-center mr-2.5 {{ request()->routeIs('admin.presensi.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Scanner Meja Depan</span>
                    </a>
                </nav>
            </div>

            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Konten Pembelajaran</p>
                <nav class="space-y-1">
                    <a href="{{ route('admin.alter.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.alter.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-play-circle w-5 text-center mr-2.5 {{ request()->routeIs('admin.alter.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>ALTER LMS Video &amp; Soal</span>
                    </a>
                    <a href="{{ route('admin.english.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.english.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-language w-5 text-center mr-2.5 {{ request()->routeIs('admin.english.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>English Mastery Bank</span>
                    </a>
                    <a href="{{ route('admin.tryout.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.tryout.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-file-pen w-5 text-center mr-2.5 {{ request()->routeIs('admin.tryout.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Paket Tryout &amp; Subtes</span>
                    </a>
                </nav>
            </div>

            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Layanan Konsultasi</p>
                <nav class="space-y-1">
                    <a href="{{ route('admin.konsultasi-mapel.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.konsultasi-mapel.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-comments w-5 text-center mr-2.5 {{ request()->routeIs('admin.konsultasi-mapel.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Tentoring Mapel Guru</span>
                    </a>
                    <a href="{{ route('admin.konseling-bk.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.konseling-bk.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-heart-pulse w-5 text-center mr-2.5 {{ request()->routeIs('admin.konseling-bk.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Konseling BK (Kak Nisa)</span>
                    </a>
                </nav>
            </div>

            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Komunikasi &amp; Gateway</p>
                <nav class="space-y-1">
                    <a href="{{ route('admin.whatsapp.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.whatsapp.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-brands fa-whatsapp w-5 text-center mr-2.5 {{ request()->routeIs('admin.whatsapp.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>WhatsApp Gateway</span>
                    </a>
                </nav>
            </div>
        @endrole

        @role('front_desk')
            <!-- Front Desk Navigation Blocks -->
            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Meja Depan ALTO</p>
                <nav class="space-y-1">
                    <a href="{{ route('admin.presensi.scanner') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.presensi.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-qrcode w-5 text-center mr-2.5 {{ request()->routeIs('admin.presensi.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Scanner Presensi Masuk</span>
                    </a>
                </nav>
            </div>
        @endrole

        @role('guru')
            <!-- Guru Navigation Blocks -->
            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Navigasi Utama</p>
                <nav class="space-y-1">
                    <a href="{{ route('guru.dashboard') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('guru.dashboard') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-gauge-high w-5 text-center mr-2.5 {{ request()->routeIs('guru.dashboard') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Dashboard</span>
                    </a>
                </nav>
            </div>

            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Dual-Role Guru &amp; Mentor</p>
                <nav class="space-y-1">
                    <a href="{{ route('guru.mapel.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('guru.mapel.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-chalkboard-user w-5 text-center mr-2.5 {{ request()->routeIs('guru.mapel.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Guru Mata Pelajaran</span>
                    </a>
                    <a href="{{ route('guru.pembimbing.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('guru.pembimbing.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-graduation-cap w-5 text-center mr-2.5 {{ request()->routeIs('guru.pembimbing.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Pembimbing Akademik (360°)</span>
                    </a>
                </nav>
            </div>

            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Presensi &amp; Kehadiran</p>
                <nav class="space-y-1">
                    <a href="{{ route('guru.presensi.scanner') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('guru.presensi.scanner') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-qrcode w-5 text-center mr-2.5 {{ request()->routeIs('guru.presensi.scanner') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Scanner Meja (QR)</span>
                    </a>
                    <a href="{{ route('guru.presensi.session.log') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('guru.presensi.session.log') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-clipboard-user w-5 text-center mr-2.5 {{ request()->routeIs('guru.presensi.session.log') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Log Sesi Presensi</span>
                    </a>
                </nav>
            </div>

            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Bimbingan &amp; Mentoring</p>
                <nav class="space-y-1">
                    <a href="{{ route('guru.bimbingan.vocab.monitor') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('guru.bimbingan.vocab.*') || request()->routeIs('guru.bimbingan.rubric.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-book-bookmark w-5 text-center mr-2.5 {{ request()->routeIs('guru.bimbingan.vocab.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Monitoring English</span>
                    </a>
                    <a href="{{ route('guru.konsultasi.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('guru.konsultasi.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-comments w-5 text-center mr-2.5 {{ request()->routeIs('guru.konsultasi.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Bimbingan Tentoring</span>
                    </a>
                </nav>
            </div>
        @endrole

        @role('siswa')
            <!-- Siswa Navigation Blocks -->
            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Navigasi Utama</p>
                <nav class="space-y-1">
                    <a href="{{ route('siswa.dashboard') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('siswa.dashboard') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-gauge-high w-5 text-center mr-2.5 {{ request()->routeIs('siswa.dashboard') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Dashboard</span>
                    </a>
                </nav>
            </div>

            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Presensi Siswa</p>
                <nav class="space-y-1">
                    <a href="{{ route('siswa.presensi.qr') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('siswa.presensi.qr') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-id-card w-5 text-center mr-2.5 {{ request()->routeIs('siswa.presensi.qr') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>QR Identitas Siswa</span>
                    </a>
                    <a href="{{ route('siswa.presensi.riwayat') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('siswa.presensi.riwayat') || request()->routeIs('siswa.riwayat.presensi') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-calendar-check w-5 text-center mr-2.5 {{ request()->routeIs('siswa.presensi.riwayat') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Riwayat Kehadiran</span>
                    </a>
                </nav>
            </div>

            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Penugasan &amp; Modul</p>
                <nav class="space-y-1">
                    <a href="{{ route('siswa.alter.player') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('siswa.alter.player') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-laptop-code w-5 text-center mr-2.5 {{ request()->routeIs('siswa.alter.player') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>ALTER LMS Player</span>
                    </a>
                    <a href="{{ route('siswa.alter.kurikulum') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('siswa.alter.kurikulum') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-book-open w-5 text-center mr-2.5 {{ request()->routeIs('siswa.alter.kurikulum') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Silabus Kurikulum</span>
                    </a>
                    <a href="{{ route('siswa.english.vocab.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('siswa.english.vocab.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-spell-check w-5 text-center mr-2.5 {{ request()->routeIs('siswa.english.vocab.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Vocab CBT (400 Kata)</span>
                    </a>
                    <a href="{{ route('siswa.english.speaking.booking') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('siswa.english.speaking.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-microphone-lines w-5 text-center mr-2.5 {{ request()->routeIs('siswa.english.speaking.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Setoran Wacana / Oral</span>
                    </a>
                    <a href="{{ route('siswa.english.tasks.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('siswa.english.tasks.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-file-lines w-5 text-center mr-2.5 {{ request()->routeIs('siswa.english.tasks.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Task Cards Storytelling</span>
                    </a>
                </nav>
            </div>

            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Perekapan &amp; Evaluasi</p>
                <nav class="space-y-1">
                    <a href="{{ route('siswa.tryout.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('siswa.tryout.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-file-signature w-5 text-center mr-2.5 {{ request()->routeIs('siswa.tryout.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Evaluasi Tryout SNBT</span>
                    </a>
                    <a href="{{ route('siswa.konsultasi.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('siswa.konsultasi.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-comments w-5 text-center mr-2.5 {{ request()->routeIs('siswa.konsultasi.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Konsultasi Mapel</span>
                    </a>
                    <a href="{{ route('siswa.profil') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('siswa.profil') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-user-graduate w-5 text-center mr-2.5 {{ request()->routeIs('siswa.profil') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Profil 360° Siswa</span>
                    </a>
                </nav>
            </div>
        @endrole

        @role('guru_bk')
            <!-- Guru BK Navigation Blocks -->
            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Navigasi Utama</p>
                <nav class="space-y-1">
                    <a href="{{ route('bk.dashboard') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('bk.dashboard') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-gauge-high w-5 text-center mr-2.5 {{ request()->routeIs('bk.dashboard') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Dashboard &amp; Matriks</span>
                    </a>
                </nav>
            </div>

            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Early Warning System</p>
                <nav class="space-y-1">
                    <a href="{{ route('bk.flagging.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('bk.flagging.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-triangle-exclamation w-5 text-center mr-2.5 {{ request()->routeIs('bk.flagging.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Kotak Flagging EWS</span>
                    </a>
                </nav>
            </div>

            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Laporan &amp; Monitoring</p>
                <nav class="space-y-1">
                    <a href="{{ route('bk.rekap.presensi') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('bk.rekap.presensi') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-clipboard-check w-5 text-center mr-2.5 {{ request()->routeIs('bk.rekap.presensi') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Rekapitulasi Presensi</span>
                    </a>
                    <a href="{{ route('bk.rekap.target') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('bk.rekap.target') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-bullseye w-5 text-center mr-2.5 {{ request()->routeIs('bk.rekap.target') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Target Bulanan Siswa</span>
                    </a>
                </nav>
            </div>

            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Bimbingan &amp; Konseling</p>
                <nav class="space-y-1">
                    <a href="{{ route('bk.konsultasi.sessions') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('bk.konsultasi.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-hand-holding-heart w-5 text-center mr-2.5 {{ request()->routeIs('bk.konsultasi.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Sesi Konsultasi BK</span>
                    </a>
                </nav>
            </div>
        @endrole

        @role('orang_tua')
            <!-- Orang Tua Navigation Blocks -->
            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Navigasi Utama</p>
                <nav class="space-y-1">
                    <a href="{{ route('ortu.dashboard') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('ortu.dashboard') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-gauge-high w-5 text-center mr-2.5 {{ request()->routeIs('ortu.dashboard') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Dashboard Ananda</span>
                    </a>
                </nav>
            </div>

            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Perekapan &amp; Evaluasi</p>
                <nav class="space-y-1">
                    <a href="{{ route('ortu.rekap.presensi') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('ortu.rekap.presensi') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-calendar-check w-5 text-center mr-2.5 {{ request()->routeIs('ortu.rekap.presensi') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Riwayat Presensi</span>
                    </a>
                    <a href="{{ route('ortu.rekap.hasil') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('ortu.rekap.hasil') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-chart-pie w-5 text-center mr-2.5 {{ request()->routeIs('ortu.rekap.hasil') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Hasil Belajar &amp; TO</span>
                    </a>
                </nav>
            </div>

            <div>
                <p class="text-[11px] font-bold text-neutral-muted uppercase tracking-wider px-3 mb-2">Bimbingan &amp; Kontak</p>
                <nav class="space-y-1">
                    <a href="{{ route('ortu.konsultasi.booking') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('ortu.konsultasi.*') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-calendar-plus w-5 text-center mr-2.5 {{ request()->routeIs('ortu.konsultasi.*') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Booking Konsultasi BK</span>
                    </a>
                    <a href="{{ route('ortu.kontak') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('ortu.kontak') ? 'bg-brand-soft text-brand-primary font-bold border-l-4 border-brand-primary shadow-2xs' : 'text-neutral-muted hover:bg-neutral-subtle hover:text-neutral-text font-medium' }}">
                        <i class="fa-solid fa-address-book w-5 text-center mr-2.5 {{ request()->routeIs('ortu.kontak') ? 'text-brand-primary' : 'text-neutral-muted' }}"></i>
                        <span>Kontak Tim Pembimbing</span>
                    </a>
                </nav>
            </div>
        @endrole
    </div>

    <!-- Sidebar Footer -->
    <div class="p-3.5 border-t border-neutral-border bg-neutral-subtle/50">
        <div class="flex items-center gap-3 px-2 py-1.5">
            <div class="w-8 h-8 rounded-full bg-brand-primary text-white font-bold flex items-center justify-center text-xs shadow-2xs shrink-0">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold text-neutral-text truncate">{{ auth()->user()->name ?? 'User' }}</p>
                <p class="text-[10px] text-neutral-muted truncate">{{ auth()->user()->email ?? '' }}</p>
            </div>
        </div>
    </div>
</aside>
