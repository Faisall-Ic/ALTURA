<x-layouts.pdf>
    <x-slot:title>Laporan Hasil Tryout SNBT — {{ $student->user->name }}</x-slot:title>

    <div class="document-title">
        <h2>Laporan Resmi Hasil Ujian Tryout SNBT</h2>
        <div class="doc-num">NO. SERTIFIKAT: ALT/TO-SNBT/{{ $score->created_at->format('Ym') }}/{{ str_pad($score->id, 5, '0', STR_PAD_LEFT) }}</div>
    </div>

    <!-- Student Identity -->
    <table class="info-grid">
        <tr>
            <td class="info-label">Nama Lengkap Siswa</td>
            <td class="info-colon">:</td>
            <td class="info-value">{{ $student->user->name }}</td>
            <td class="info-label">Paket Ujian Tryout</td>
            <td class="info-colon">:</td>
            <td class="info-value">{{ $score->tryout->title }}</td>
        </tr>
        <tr>
            <td class="info-label">Nomor Induk Siswa (NISN)</td>
            <td class="info-colon">:</td>
            <td class="info-value font-mono">{{ $student->nisn }}</td>
            <td class="info-label">Tanggal Pelaksanaan</td>
            <td class="info-colon">:</td>
            <td class="info-value">{{ $score->created_at->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="info-label">Rombongan Belajar (Kelas)</td>
            <td class="info-colon">:</td>
            <td class="info-value">{{ $student->academicClass?->name }}</td>
            <td class="info-label">Program Peminatan</td>
            <td class="info-colon">:</td>
            <td class="info-value">{{ $student->major?->name }} ({{ $student->major?->code }})</td>
        </tr>
        <tr>
            <td class="info-label">Guru Mentor Pembimbing</td>
            <td class="info-colon">:</td>
            <td class="info-value">{{ $student->mentor?->user?->name ?? 'Tim Akademik Alto' }}</td>
            <td class="info-label">Status Partisipasi</td>
            <td class="info-colon">:</td>
            <td class="info-value" style="color: #16a34a;">Lulus Ujian Terverifikasi</td>
        </tr>
    </table>

    <!-- KPI Summary -->
    <div class="kpi-container">
        <div class="kpi-card">
            <div class="kpi-label">Skor Rata-Rata Total</div>
            <div class="kpi-val" style="color: #4f46e5;">{{ $score->average_score }}</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Subtes Tertinggi</div>
            @php
                $subtests = [
                    'TPS' => $score->subtest_tps,
                    'B. Indo' => $score->subtest_indo,
                    'B. Inggris' => $score->subtest_inggris,
                    'Matematika' => $score->subtest_matematika,
                ];
                arsort($subtests);
                $topSubtest = array_key_first($subtests);
                $topVal = reset($subtests);
            @endphp
            <div class="kpi-val" style="color: #16a34a;">{{ $topVal }} <span style="font-size: 10pt; font-weight: normal; color: #64748b;">({{ $topSubtest }})</span></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Prediksi Peluang PTN</div>
            <div class="kpi-val" style="color: {{ $score->average_score >= 650 ? '#16a34a' : ($score->average_score >= 550 ? '#d97706' : '#dc2626') }}; font-size: 13pt;">
                {{ $score->average_score >= 650 ? 'Sangat Kompetitif' : ($score->average_score >= 550 ? 'Kompetitif' : 'Perlu Penguatan') }}
            </div>
        </div>
    </div>

    <!-- Subtest Table -->
    <h3 style="font-size: 11pt; font-weight: 800; margin: 18px 0 8px 0; color: #0f172a; text-transform: uppercase;">
        Rincian Perolehan Nilai Subtes SNBT
    </h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 47%;">Materi Subtes SNBT</th>
                <th class="text-center" style="width: 15%;">Bobot Maks</th>
                <th class="text-center" style="width: 15%;">Nilai Capaian</th>
                <th class="text-center" style="width: 15%;">Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td><strong>Tes Potensi Skolastik (TPS)</strong><br><small style="color: #64748b;">Penalaran Umum, Pengetahuan Kuantitatif &amp; Pemahaman Umum</small></td>
                <td class="text-center font-mono">1000.00</td>
                <td class="text-center font-mono font-bold" style="color: #4f46e5;">{{ number_format($score->subtest_tps, 2) }}</td>
                <td class="text-center font-bold" style="color: {{ $score->subtest_tps >= 600 ? '#16a34a' : '#d97706' }};">
                    {{ $score->subtest_tps >= 600 ? 'Memuaskan' : 'Cukup' }}
                </td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td><strong>Literasi dalam Bahasa Indonesia</strong><br><small style="color: #64748b;">Pemahaman Wacana Teks &amp; Struktur Bacaan Kritis</small></td>
                <td class="text-center font-mono">1000.00</td>
                <td class="text-center font-mono font-bold" style="color: #4f46e5;">{{ number_format($score->subtest_indo, 2) }}</td>
                <td class="text-center font-bold" style="color: {{ $score->subtest_indo >= 600 ? '#16a34a' : '#d97706' }};">
                    {{ $score->subtest_indo >= 600 ? 'Memuaskan' : 'Cukup' }}
                </td>
            </tr>
            <tr>
                <td class="text-center">3</td>
                <td><strong>Literasi dalam Bahasa Inggris</strong><br><small style="color: #64748b;">Academic Contextual Vocabulary &amp; Text Inference</small></td>
                <td class="text-center font-mono">1000.00</td>
                <td class="text-center font-mono font-bold" style="color: #4f46e5;">{{ number_format($score->subtest_inggris, 2) }}</td>
                <td class="text-center font-bold" style="color: {{ $score->subtest_inggris >= 600 ? '#16a34a' : '#d97706' }};">
                    {{ $score->subtest_inggris >= 600 ? 'Memuaskan' : 'Cukup' }}
                </td>
            </tr>
            <tr>
                <td class="text-center">4</td>
                <td><strong>Penalaran Matematika</strong><br><small style="color: #64748b;">Aplikasi Konsep Matematika Terapan dalam Pemecahan Masalah</small></td>
                <td class="text-center font-mono">1000.00</td>
                <td class="text-center font-mono font-bold" style="color: #4f46e5;">{{ number_format($score->subtest_matematika, 2) }}</td>
                <td class="text-center font-bold" style="color: {{ $score->subtest_matematika >= 600 ? '#16a34a' : '#d97706' }};">
                    {{ $score->subtest_matematika >= 600 ? 'Memuaskan' : 'Cukup' }}
                </td>
            </tr>
            <tr style="background-color: #f1f5f9; font-weight: 800;">
                <td colspan="3" class="text-right" style="padding-right: 14px; text-transform: uppercase;">Rata-Rata Kumulatif Total</td>
                <td class="text-center font-mono font-bold" style="font-size: 11pt; color: #4f46e5;">{{ number_format($score->average_score, 2) }}</td>
                <td class="text-center" style="color: #16a34a;">RESMI</td>
            </tr>
        </tbody>
    </table>

    <!-- Analysis & Recommendation Box -->
    <div style="margin: 20px 0; padding: 14px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 9pt;">
        <strong style="color: #0f172a; display: block; margin-bottom: 4px;">Analisis &amp; Rekomendasi Tim Akademik Alto:</strong>
        <p style="margin: 0; color: #475569; line-height: 1.5;">
            Ananda <strong>{{ $student->user->name }}</strong> menunjukkan potensi yang sangat baik, terutama pada subtes <strong>{{ $topSubtest }}</strong>.
            Untuk mengoptimalkan target passing grade program studi pilihan di PTN Impian, disarankan memperdalam latihan soal analitis pada subtes penalaran serta mempertahankan konsistensi belajar melalui modul ALTER LMS.
        </p>
    </div>

    <!-- Signatures Section -->
    <div class="signature-section">
        <div class="sig-box">
            <span>Mengetahui,<br>Guru Mentor Pembimbing</span>
            <div class="sig-space"></div>
            <div class="sig-name">{{ $student->mentor?->user?->name ?? 'Pak Budi Santoso, M.Pd' }}</div>
            <div class="sig-role">NIP: {{ $student->mentor?->nip ?? '198501152010011002' }}</div>
        </div>

        <div class="sig-box">
            <span>Ditetapkan di Yogyakarta,<br>Direktur Akademik Bimbel Alto</span>
            <div class="sig-space"></div>
            <div class="sig-name">Dr. Alto Wijaya, M.Sc</div>
            <div class="sig-role">Direktur Kurikulum &amp; Evaluasi</div>
        </div>
    </div>
</x-layouts.pdf>
