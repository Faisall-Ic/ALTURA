<x-layouts.pdf>
    <x-slot:title>Laporan Rapor Perkembangan Akademik Siswa — {{ $student->user->name }}</x-slot:title>

    <div class="document-title">
        <h2>Rapor Perkembangan Akademik &amp; Kedisiplinan Siswa (Profil 360°)</h2>
        <div class="doc-num">NO. DOKUMEN: ALT/RAPOR-360/{{ now()->format('Ym') }}/{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}</div>
    </div>

    <!-- Student Identity -->
    <table class="info-grid">
        <tr>
            <td class="info-label">Nama Lengkap Siswa</td>
            <td class="info-colon">:</td>
            <td class="info-value">{{ $student->user->name }}</td>
            <td class="info-label">Rombongan Belajar</td>
            <td class="info-colon">:</td>
            <td class="info-value">{{ $student->academicClass?->name }}</td>
        </tr>
        <tr>
            <td class="info-label">Nomor Induk Siswa (NISN)</td>
            <td class="info-colon">:</td>
            <td class="info-value font-mono">{{ $student->nisn }}</td>
            <td class="info-label">Program Peminatan</td>
            <td class="info-colon">:</td>
            <td class="info-value">{{ $student->major?->name }} ({{ $student->major?->code }})</td>
        </tr>
        <tr>
            <td class="info-label">Guru Mentor Pembimbing</td>
            <td class="info-colon">:</td>
            <td class="info-value">{{ $student->mentor?->user?->name ?? 'Pak Budi Santoso, M.Pd' }}</td>
            <td class="info-label">Guru Konselor (BK)</td>
            <td class="info-colon">:</td>
            <td class="info-value">{{ $student->counselor?->name ?? 'Kak Nisa, S.Psi' }}</td>
        </tr>
    </table>

    <!-- 1. Presensi & Kedisiplinan -->
    <h3 style="font-size: 11pt; font-weight: 800; margin: 16px 0 6px 0; color: #0f172a; text-transform: uppercase;">
        I. Rekapitulasi Presensi &amp; Matriks Kedisiplinan
    </h3>
    <div class="kpi-container">
        <div class="kpi-card">
            <div class="kpi-label">Persentase Kehadiran</div>
            <div class="kpi-val" style="color: {{ $attendanceStats['color'] === 'green' ? '#16a34a' : ($attendanceStats['color'] === 'yellow' ? '#d97706' : '#dc2626') }};">
                {{ $attendanceStats['percentage'] }}%
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Total Sesi Diikuti</div>
            <div class="kpi-val">{{ $attendanceStats['total'] }} Sesi</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Klasifikasi Matriks BK</div>
            <div class="kpi-val" style="font-size: 13pt; color: {{ $attendanceStats['color'] === 'green' ? '#16a34a' : ($attendanceStats['color'] === 'yellow' ? '#d97706' : '#dc2626') }};">
                {{ $attendanceStats['status_label'] }}
            </div>
        </div>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center">Hadir Penuh</th>
                <th class="text-center">Terlambat</th>
                <th class="text-center">Izin Resmi</th>
                <th class="text-center">Sakit</th>
                <th class="text-center">Tanpa Keterangan (Alfa)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center font-bold" style="color: #16a34a;">{{ $attendanceStats['hadir'] }} Kali</td>
                <td class="text-center font-bold" style="color: #d97706;">{{ $attendanceStats['terlambat'] }} Kali</td>
                <td class="text-center">{{ $attendanceStats['izin'] }} Kali</td>
                <td class="text-center">{{ $attendanceStats['sakit'] }} Kali</td>
                <td class="text-center font-bold" style="color: #dc2626;">{{ $attendanceStats['alfa'] }} Kali</td>
            </tr>
        </tbody>
    </table>

    <!-- 2. Capaian Tryout SNBT -->
    <h3 style="font-size: 11pt; font-weight: 800; margin: 18px 0 6px 0; color: #0f172a; text-transform: uppercase;">
        II. Riwayat Hasil Tryout SNBT Terjadwal
    </h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Paket Tryout</th>
                <th class="text-center">TPS</th>
                <th class="text-center">Literasi Indo</th>
                <th class="text-center">Literasi Inggris</th>
                <th class="text-center">Matematika</th>
                <th class="text-center">Rata-Rata</th>
                <th class="text-center">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tryoutScores as $score)
                <tr>
                    <td><strong>{{ $score->tryout?->title ?? 'Tryout SNBT' }}</strong></td>
                    <td class="text-center font-mono">{{ $score->subtest_tps }}</td>
                    <td class="text-center font-mono">{{ $score->subtest_indo }}</td>
                    <td class="text-center font-mono">{{ $score->subtest_inggris }}</td>
                    <td class="text-center font-mono">{{ $score->subtest_matematika }}</td>
                    <td class="text-center font-mono font-bold" style="color: #4f46e5;">{{ $score->average_score }}</td>
                    <td class="text-center text-slate-500">{{ $score->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="color: #94a3b8;">Belum ada riwayat tryout.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- 3. English Mastery & Speaking Assessment -->
    <h3 style="font-size: 11pt; font-weight: 800; margin: 18px 0 6px 0; color: #0f172a; text-transform: uppercase;">
        III. Capaian Penguasaan Bahasa Inggris (Hybrid Assessment)
    </h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 50%;">Indikator Penilaian</th>
                <th class="text-center" style="width: 25%;">Target Standar</th>
                <th class="text-center" style="width: 25%;">Capaian Ananda</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Kelulusan Modul Vocab CBT (400 Kata)</strong></td>
                <td class="text-center">8 Modul Lengkap (≥ 75 pts)</td>
                <td class="text-center font-bold" style="color: #4f46e5;">{{ $passedVocabModules }} / 8 Modul Selesai</td>
            </tr>
            <tr>
                <td><strong>Ujian Praktik Speaking (Oral Rubric)</strong></td>
                <td class="text-center">Storytelling &amp; Wacana (≥ 75.0)</td>
                <td class="text-center font-bold" style="color: {{ $latestSpeaking?->grade >= 75 ? '#16a34a' : '#d97706' }};">
                    {{ $latestSpeaking ? $latestSpeaking->grade . ' pts (' . ucfirst($latestSpeaking->status) . ')' : 'Belum Dijadwalkan' }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Catatan Konseling BK -->
    <div style="margin: 18px 0; padding: 12px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 9pt;">
        <strong style="color: #0f172a; display: block; margin-bottom: 3px;">Catatan Perkembangan &amp; Karir Guru BK (Kak Nisa, S.Psi):</strong>
        <p style="margin: 0; color: #475569; line-height: 1.5;">
            Ananda memiliki motivasi belajar yang positif. Pertahankan kedisiplinan hadir tepat waktu pada setiap sesi bimbingan dan maksimalkan bimbingan tentoring tatap muka dengan Mentor untuk membedah paket soal berbobot tinggi.
        </p>
    </div>

    <!-- Signatures -->
    <div class="signature-section">
        <div class="sig-box">
            <span>Guru Mentor Pembimbing,</span>
            <div class="sig-space"></div>
            <div class="sig-name">{{ $student->mentor?->user?->name ?? 'Pak Budi Santoso, M.Pd' }}</div>
            <div class="sig-role">NIP: {{ $student->mentor?->nip ?? '198501152010011002' }}</div>
        </div>

        <div class="sig-box">
            <span>Guru Bimbingan Konseling (BK),</span>
            <div class="sig-space"></div>
            <div class="sig-name">{{ $student->counselor?->name ?? 'Kak Nisa, S.Psi' }}</div>
            <div class="sig-role">Konselor Akademik &amp; Karir Alto</div>
        </div>
    </div>
</x-layouts.pdf>
