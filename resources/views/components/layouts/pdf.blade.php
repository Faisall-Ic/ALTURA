<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Laporan Akademik' }} — ALTURA (Bimbel Alto)</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 15mm 15mm;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #0f172a;
            background: #ffffff;
            margin: 0;
            padding: 0;
            font-size: 11pt;
            line-height: 1.5;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .no-print {
            background: #0f172a;
            color: #ffffff;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        .btn-print {
            background: #4f46e5;
            color: #ffffff;
            border: none;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-print:hover {
            background: #4338ca;
        }

        .btn-back {
            color: #94a3b8;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-back:hover {
            color: #ffffff;
        }

        .page-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 24px 32px;
            background: #ffffff;
        }

        /* Letterhead / Kop Surat */
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px double #0f172a;
            padding-bottom: 14px;
            margin-bottom: 20px;
        }

        .kop-brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .logo-box {
            width: 52px;
            height: 52px;
            background: #0f172a;
            color: #f59e0b;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 22px;
            letter-spacing: -1px;
            border: 2px solid #f59e0b;
        }

        .kop-title h1 {
            margin: 0;
            font-size: 18pt;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .kop-title p {
            margin: 2px 0 0 0;
            font-size: 8.5pt;
            color: #475569;
        }

        .document-title {
            text-align: center;
            margin: 20px 0 22px 0;
        }

        .document-title h2 {
            margin: 0;
            font-size: 15pt;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .document-title .doc-num {
            margin: 4px 0 0 0;
            font-size: 9pt;
            color: #64748b;
            font-family: monospace;
        }

        /* Identity Table */
        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9.5pt;
        }

        .info-grid td {
            padding: 5px 8px;
            vertical-align: top;
        }

        .info-label {
            width: 25%;
            color: #475569;
            font-weight: 600;
        }

        .info-colon {
            width: 2%;
            text-align: center;
            color: #94a3b8;
        }

        .info-value {
            width: 73%;
            color: #0f172a;
            font-weight: 600;
        }

        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
            font-size: 9pt;
        }

        .data-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 8pt;
            letter-spacing: 0.5px;
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            text-align: left;
        }

        .data-table td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            color: #1e293b;
        }

        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        .font-mono { font-family: monospace; }
        .font-bold { font-weight: 700; }

        /* KPI Box */
        .kpi-container {
            display: flex;
            gap: 12px;
            margin: 16px 0 20px 0;
        }

        .kpi-card {
            flex: 1;
            padding: 12px 14px;
            border-radius: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            text-align: center;
        }

        .kpi-card .kpi-label {
            font-size: 8pt;
            text-transform: uppercase;
            font-weight: 700;
            color: #64748b;
        }

        .kpi-card .kpi-val {
            font-size: 16pt;
            font-weight: 800;
            color: #0f172a;
            margin-top: 4px;
        }

        /* Signatures */
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .sig-box {
            width: 40%;
            text-align: center;
            font-size: 9.5pt;
        }

        .sig-space {
            height: 60px;
        }

        .sig-name {
            font-weight: 700;
            text-decoration: underline;
            color: #0f172a;
        }

        .sig-role {
            font-size: 8.5pt;
            color: #64748b;
            margin-top: 2px;
        }

        /* Footer Stamp */
        .doc-footer {
            margin-top: 35px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 8pt;
            color: #94a3b8;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            .page-container {
                padding: 0 !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <a href="javascript:history.back()" class="btn-back">&larr; Kembali ke Portal</a>
        <div style="display: flex; align-items: center; gap: 12px;">
            <span style="font-size: 12px; color: #94a3b8;">Format: Cetak / Simpan PDF (A4)</span>
            <button onclick="window.print()" class="btn-print">
                <i class="fa-solid fa-print mr-1.5"></i> Cetak / Unduh PDF
            </button>
        </div>
    </div>

    <div class="page-container">
        <!-- Kop Surat Resmi Bimbel Alto -->
        <div class="kop-surat">
            <div class="kop-brand">
                <div class="logo-box">A</div>
                <div class="kop-title">
                    <h1>BIMBEL ALTO (ALTURA SYSTEM)</h1>
                    <p>Bimbingan Belajar SNBT, Kedinasan &amp; Penguasaan Bahasa Asing Terpadu</p>
                    <p>Gedung Pusat Akademik Alto, Jl. Pendidikan No. 42 • Hotline: +62 812-3456-7890 • www.altura.test</p>
                </div>
            </div>
            <div style="text-align: right; font-size: 8pt; color: #64748b;">
                <strong>ISO 9001:2015</strong><br>
                Official Certified
            </div>
        </div>

        {{ $slot }}

        <!-- Footer Audit -->
        <div class="doc-footer">
            <span>Dokumen ini diterbitkan secara sah dan otomatis oleh ALTURA Sistem Akademik Bimbel Alto.</span>
            <span>Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB</span>
        </div>
    </div>
</body>
</html>
