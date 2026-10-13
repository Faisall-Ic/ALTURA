<x-layouts.app>
    <x-slot:title>Scanner Presensi Meja</x-slot:title>

    <div class="space-y-6">
        <!-- Top Nav & Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-text flex items-center gap-2">
                    <i class="fa-solid fa-camera text-brand-primary"></i>
                    <span>Desk-side QR Scanner Presensi</span>
                </h1>
                <p class="text-xs text-neutral-muted mt-1">
                    Arahkan kamera ke layar QR siswa meja-demi-meja untuk mencatat kehadiran tatap muka secara instan.
                </p>
            </div>
            <a href="{{ route('guru.presensi.session.log') }}"
                class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-xs font-semibold text-neutral-text bg-white hover:bg-neutral-subtle border border-neutral-border shadow-sm transition-colors">
                <i class="fa-solid fa-list-check mr-2 text-xs"></i>
                <span>Lihat Log Presensi Hari Ini</span>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Camera Scanner & Mode Controls -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white border border-neutral-border rounded-2xl p-5 sm:p-6 relative overflow-hidden shadow-sm">
                    <!-- Scan Status Mode Selector -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold text-neutral-muted uppercase tracking-wider">Status Input:</span>
                        <div class="inline-flex rounded-xl bg-neutral-subtle p-1 border border-neutral-border">
                            <label class="cursor-pointer">
                                <input type="radio" name="scan_status" value="hadir" checked class="sr-only peer">
                                <span class="px-3 py-1.5 rounded-lg text-xs font-semibold text-neutral-muted peer-checked:bg-white peer-checked:text-emerald-700 peer-checked:shadow-sm transition-all inline-block">
                                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1.5 align-middle"></span> Hadir Tepat
                                </span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="scan_status" value="terlambat" class="sr-only peer">
                                <span class="px-3 py-1.5 rounded-lg text-xs font-semibold text-neutral-muted peer-checked:bg-white peer-checked:text-amber-accent-text peer-checked:shadow-sm transition-all inline-block">
                                    <span class="inline-block w-2 h-2 rounded-full bg-amber-accent mr-1.5 align-middle"></span> Terlambat
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Viewfinder Container -->
                    <div class="relative bg-neutral-subtle rounded-2xl overflow-hidden border-2 border-dashed border-neutral-border min-h-[320px] flex items-center justify-center">
                        <div id="qr-reader" class="w-full h-full"></div>
                        <div id="scanner-placeholder" class="text-center p-6 space-y-3">
                            <div class="w-16 h-16 rounded-2xl bg-brand-soft border border-brand-primary/20 flex items-center justify-center mx-auto text-brand-primary">
                                <i class="fa-solid fa-camera text-2xl"></i>
                            </div>
                            <p class="text-xs text-neutral-muted">Klik tombol di bawah untuk mengaktifkan kamera perangkat.</p>
                            <button type="button" id="start-scan-btn" onclick="startScanner()"
                                class="px-4 py-2 bg-brand-primary hover:bg-brand-navy text-white text-xs font-semibold rounded-xl shadow-sm transition-all cursor-pointer">
                                Mulai Kamera Pemindai
                            </button>
                        </div>
                    </div>

                    <!-- Scan Feedback Banner -->
                    <div id="scan-feedback" class="mt-4 hidden p-4 rounded-xl text-xs font-semibold transition-all"></div>

                    <!-- Quick Manual Input for Testing / Backup -->
                    <div class="mt-6 pt-5 border-t border-neutral-border">
                        <label class="block text-[11px] font-semibold uppercase text-neutral-muted mb-2">
                            Input Manual (Token QR atau NISN Siswa)
                        </label>
                        <form id="manual-scan-form" onsubmit="handleManualScan(event)" class="flex gap-2">
                            <input type="text" id="manual-token" required
                                placeholder="Contoh: ALTURA-STD-0067829101 atau 0067829101"
                                class="flex-1 px-4 py-2 rounded-xl bg-white border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs font-mono focus:outline-none focus:border-brand-primary focus:ring-1 focus:ring-brand-primary">
                            <button type="submit"
                                class="px-4 py-2 bg-brand-primary hover:bg-brand-navy text-white text-xs font-semibold rounded-xl transition-colors cursor-pointer shadow-sm">
                                Check-in
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right 1 Col: Live Scan Stream Feed -->
            <div class="space-y-4">
                <div class="bg-white border border-neutral-border rounded-2xl p-5 sm:p-6 shadow-sm">
                    <div class="flex items-center justify-between pb-4 border-b border-neutral-border">
                        <h2 class="text-sm font-bold text-neutral-text flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Feed Presensi Sesi Ini
                        </h2>
                        <span id="scan-counter" class="text-xs font-mono text-brand-primary font-bold bg-brand-soft px-2.5 py-0.5 rounded-full border border-brand-primary/20">
                            {{ $todayScansCount }} Siswa
                        </span>
                    </div>

                    <div id="scanned-feed-list" class="mt-4 space-y-3 max-h-[460px] overflow-y-auto pr-1">
                        <p id="empty-feed-notice" class="text-xs text-neutral-muted text-center py-6">
                            Belum ada pemindaian baru pada sesi aktif ini.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Html5-QRCode Library -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        let html5QrCode = null;
        let isProcessing = false;
        let audioCtx = null;

        function playBeep(type = 'success') {
            try {
                if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                
                if (type === 'success') {
                    osc.frequency.setValueAtTime(880, audioCtx.currentTime); // A5
                    gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.15);
                } else {
                    osc.frequency.setValueAtTime(220, audioCtx.currentTime); // A3
                    gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.3);
                }
            } catch (e) {
                console.log('Audio feedback not permitted');
            }
        }

        function showFeedback(message, isSuccess = true) {
            const el = document.getElementById('scan-feedback');
            el.className = isSuccess 
                ? 'mt-4 p-4 rounded-xl text-xs font-semibold bg-emerald-50 border border-emerald-200 text-emerald-800 block'
                : 'mt-4 p-4 rounded-xl text-xs font-semibold bg-red-accent-soft border border-red-accent/30 text-red-accent-text block';
            el.innerHTML = message;
        }

        function prependFeed(item) {
            const list = document.getElementById('scanned-feed-list');
            const notice = document.getElementById('empty-feed-notice');
            if (notice) notice.remove();

            const card = document.createElement('div');
            card.className = 'bg-neutral-subtle/60 border border-neutral-border rounded-xl p-3 flex items-center justify-between text-xs transition-all';
            card.innerHTML = `
                <div>
                    <span class="font-bold text-neutral-text block">${item.student_name}</span>
                    <span class="text-[11px] text-neutral-muted">${item.class} • NISN: ${item.nisn}</span>
                </div>
                <div class="text-right">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold ${item.status === 'hadir' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-accent-soft text-amber-accent-text border border-amber-accent/30'}">
                        ${item.status.toUpperCase()}
                    </span>
                    <span class="block text-[10px] font-mono text-neutral-muted mt-0.5">${item.session_time} WIB</span>
                </div>
            `;
            list.prepend(card);
        }

        async function submitScanToken(token) {
            if (isProcessing) return;
            isProcessing = true;

            const status = document.querySelector('input[name="scan_status"]:checked').value;

            try {
                const response = await fetch("{{ route('guru.presensi.scan.checkin') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ token: token, status: status })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    playBeep('success');
                    showFeedback(`<i class="fa-solid fa-circle-check mr-1.5 text-emerald-600"></i> ${data.message}`, true);
                    prependFeed(data.attendance);
                } else {
                    playBeep('error');
                    showFeedback(`<i class="fa-solid fa-triangle-exclamation mr-1.5 text-amber-500"></i> ${data.message}`, false);
                }
            } catch (err) {
                playBeep('error');
                showFeedback('Terjadi kesalahan jaringan saat mencatat presensi.', false);
            } finally {
                setTimeout(() => { isProcessing = false; }, 2000); // 2 seconds debounce between scans
            }
        }

        function handleManualScan(e) {
            e.preventDefault();
            const input = document.getElementById('manual-token');
            const val = input.value.trim();
            if (val) {
                submitScanToken(val);
                input.value = '';
            }
        }

        function startScanner() {
            document.getElementById('scanner-placeholder').classList.add('hidden');
            html5QrCode = new Html5Qrcode("qr-reader");

            const config = { fps: 10, qrbox: { width: 250, height: 250 } };

            html5QrCode.start(
                { facingMode: "environment" },
                config,
                (decodedText) => {
                    submitScanToken(decodedText);
                },
                (errorMessage) => {
                    // Ignore frame scan failures
                }
            ).catch((err) => {
                document.getElementById('scanner-placeholder').classList.remove('hidden');
                showFeedback('Gagal mengakses kamera. Pastikan izin kamera telah diberikan atau gunakan input manual.', false);
            });
        }
    </script>
</x-layouts.app>
