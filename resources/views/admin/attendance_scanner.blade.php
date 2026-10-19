<x-layouts.app>
    <x-slot:title>Scanner Presensi Meja Depan (Front Desk)</x-slot:title>

    <div class="space-y-6">
        <!-- Top Nav & Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20 mb-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Front Desk Ka-Administrasi</span>
                </div>
                <h1 class="text-2xl font-bold text-neutral-text flex items-center gap-2">
                    <i class="fa-solid fa-qrcode text-brand-primary"></i>
                    <span>Scanner Presensi Masuk Siswa</span>
                </h1>
                <p class="text-xs text-neutral-muted mt-1">
                    Pindai QR Identity Code siswa di meja masuk ALTO untuk mencatat kehadiran harian secara otomatis.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-neutral-border text-xs font-bold text-brand-primary shadow-xs">
                    <i class="fa-solid fa-users-viewfinder"></i>
                    <span id="scan-counter">{{ $todayScansCount }} Siswa Hadir Hari Ini</span>
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Camera Scanner & Mode Controls -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white border border-neutral-border rounded-2xl p-5 sm:p-6 relative overflow-hidden shadow-xs">
                    <!-- Scan Status Mode Selector -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold text-neutral-muted uppercase tracking-wider">Status Presensi:</span>
                        <div class="inline-flex rounded-xl bg-neutral-subtle p-1 border border-neutral-border">
                            <label class="cursor-pointer">
                                <input type="radio" name="scan_status" value="hadir" checked class="sr-only peer">
                                <span class="px-3 py-1.5 rounded-lg text-xs font-semibold text-neutral-muted peer-checked:bg-white peer-checked:text-emerald-700 peer-checked:shadow-xs transition-all inline-block">
                                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1.5 align-middle"></span> Hadir Tepat Waktu
                                </span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="scan_status" value="terlambat" class="sr-only peer">
                                <span class="px-3 py-1.5 rounded-lg text-xs font-semibold text-neutral-muted peer-checked:bg-white peer-checked:text-amber-accent-text peer-checked:shadow-xs transition-all inline-block">
                                    <span class="inline-block w-2 h-2 rounded-full bg-amber-accent mr-1.5 align-middle"></span> Terlambat
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Viewfinder Container -->
                    <div class="relative bg-neutral-subtle rounded-2xl overflow-hidden border-2 border-dashed border-neutral-border min-h-[340px] flex items-center justify-center">
                        <div id="qr-reader" class="w-full h-full"></div>
                        <div id="scanner-placeholder" class="text-center p-6 space-y-3">
                            <div class="w-16 h-16 rounded-2xl bg-brand-soft border border-brand-primary/20 flex items-center justify-center mx-auto text-brand-primary shadow-xs">
                                <i class="fa-solid fa-camera text-2xl"></i>
                            </div>
                            <p class="text-xs text-neutral-muted max-w-sm mx-auto">
                                Siapkan pemindai kamera untuk mendeteksi layar ponsel atau kartu cetak QR Identity Siswa.
                            </p>
                            <button type="button" id="start-scan-btn" onclick="startScanner()"
                                class="px-5 py-2.5 bg-brand-primary hover:bg-brand-navy text-white text-xs font-bold rounded-xl shadow-xs transition-all cursor-pointer">
                                <i class="fa-solid fa-play mr-1.5"></i> Aktifkan Kamera Depan
                            </button>
                        </div>
                    </div>

                    <!-- Scan Feedback Banner -->
                    <div id="scan-feedback" class="mt-4 hidden p-4 rounded-xl text-xs font-semibold transition-all"></div>

                    <!-- Quick Manual Input for Backup -->
                    <div class="mt-6 pt-5 border-t border-neutral-border">
                        <label class="block text-[11px] font-bold uppercase text-neutral-muted mb-2 tracking-wider">
                            Pencarian / Input Manual (Token QR atau NISN Siswa)
                        </label>
                        <form id="manual-scan-form" onsubmit="handleManualScan(event)" class="flex gap-2">
                            <input type="text" id="manual-token" required
                                placeholder="Ketik token misal: ALTURA-STD-XXXXX atau NISN siswa"
                                class="flex-1 px-4 py-2.5 rounded-xl bg-white border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs font-mono focus:outline-none focus:border-brand-primary focus:ring-1 focus:ring-brand-primary">
                            <button type="submit"
                                class="px-5 py-2.5 bg-brand-primary hover:bg-brand-navy text-white text-xs font-bold rounded-xl transition-all cursor-pointer shadow-xs">
                                <i class="fa-solid fa-arrow-right-to-bracket mr-1.5"></i> Verifikasi Masuk
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right 1 Col: Live Scan Stream Feed -->
            <div class="space-y-4">
                <div class="bg-white border border-neutral-border rounded-2xl p-5 sm:p-6 shadow-xs">
                    <div class="flex items-center justify-between pb-4 border-b border-neutral-border">
                        <h2 class="text-sm font-bold text-neutral-text flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Aktivitas Masuk Terkini</span>
                        </h2>
                        <span class="text-[11px] text-neutral-muted">Hari Ini</span>
                    </div>

                    <div id="scanned-feed-list" class="mt-4 space-y-3 max-h-[460px] overflow-y-auto pr-1">
                        @forelse ($recentAttendances as $att)
                            <div class="p-3.5 rounded-xl border border-neutral-border bg-neutral-subtle/50 flex items-center justify-between gap-3 text-xs">
                                <div class="min-w-0">
                                    <p class="font-bold text-neutral-text truncate">{{ $att->student?->user?->name ?? 'Siswa' }}</p>
                                    <p class="text-[10px] text-neutral-muted truncate">
                                        {{ $att->student?->academicClass?->name ?? 'Tanpa Rombel' }} • NISN: {{ $att->student?->nisn ?? '-' }}
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full font-bold text-[10px] {{ $att->status === 'hadir' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                        {{ ucfirst($att->status) }}
                                    </span>
                                    <span class="block text-[10px] text-neutral-muted font-mono mt-0.5">{{ $att->time_in ?? '08:00:00' }}</span>
                                </div>
                            </div>
                        @empty
                            <p id="empty-feed-notice" class="text-xs text-neutral-muted text-center py-6">
                                Belum ada siswa masuk hari ini.
                            </p>
                        @endforelse
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
                    osc.frequency.setValueAtTime(880, audioCtx.currentTime); // A5 note
                    gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.25);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.25);
                } else {
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(220, audioCtx.currentTime);
                    gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.4);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.4);
                }
            } catch (e) {
                console.log('Audio feedback not permitted:', e);
            }
        }

        function showFeedback(type, message, details = null) {
            const fb = document.getElementById('scan-feedback');
            fb.classList.remove('hidden', 'bg-emerald-50', 'text-emerald-800', 'border-emerald-200', 'bg-red-50', 'text-red-800', 'border-red-200', 'bg-amber-50', 'text-amber-800', 'border-amber-200');
            
            let html = `<div class="flex items-start gap-3">`;
            if (type === 'success') {
                fb.classList.add('bg-emerald-50', 'text-emerald-800', 'border', 'border-emerald-200');
                html += `<i class="fa-solid fa-circle-check text-emerald-600 text-lg mt-0.5"></i>`;
            } else if (type === 'warning') {
                fb.classList.add('bg-amber-50', 'text-amber-800', 'border', 'border-amber-200');
                html += `<i class="fa-solid fa-triangle-exclamation text-amber-600 text-lg mt-0.5"></i>`;
            } else {
                fb.classList.add('bg-red-50', 'text-red-800', 'border', 'border-red-200');
                html += `<i class="fa-solid fa-circle-xmark text-red-600 text-lg mt-0.5"></i>`;
            }
            
            html += `<div class="flex-1"><p class="font-bold text-xs">${message}</p>`;
            if (details) {
                html += `<div class="mt-2 text-[11px] grid grid-cols-2 gap-1 font-normal opacity-90">
                    <div>Siswa: <span class="font-semibold">${details.student_name || details.name}</span></div>
                    <div>Kelas: <span class="font-semibold">${details.class}</span></div>
                    <div>NISN: <span class="font-semibold">${details.nisn}</span></div>
                    <div>Jam Masuk: <span class="font-mono font-semibold">${details.time_in}</span></div>
                </div>`;
            }
            html += `</div></div>`;
            
            fb.innerHTML = html;
            fb.classList.remove('hidden');
        }

        async function processScan(token) {
            if (isProcessing) return;
            isProcessing = true;

            const status = document.querySelector('input[name="scan_status"]:checked').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch("{{ route('admin.presensi.scan.checkin') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ token: token, status: status })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    playBeep('success');
                    showFeedback('success', data.message, data.attendance);
                    addToFeed(data.attendance);
                } else if (response.status === 409) {
                    playBeep('warning');
                    showFeedback('warning', data.message, data.student);
                } else {
                    playBeep('error');
                    showFeedback('error', data.message || 'Gagal memproses QR code.');
                }
            } catch (err) {
                console.error(err);
                playBeep('error');
                showFeedback('error', 'Terjadi kesalahan koneksi saat verifikasi presensi.');
            } finally {
                setTimeout(() => { isProcessing = false; }, 2000);
            }
        }

        function addToFeed(att) {
            const feedList = document.getElementById('scanned-feed-list');
            const emptyNotice = document.getElementById('empty-feed-notice');
            if (emptyNotice) emptyNotice.remove();

            const isHadir = att.status === 'hadir';
            const badgeClass = isHadir 
                ? 'bg-emerald-50 text-emerald-700 border-emerald-200' 
                : 'bg-amber-50 text-amber-700 border-amber-200';

            const card = document.createElement('div');
            card.className = 'p-3.5 rounded-xl border border-neutral-border bg-white shadow-2xs flex items-center justify-between gap-3 text-xs animate-fade-in';
            card.innerHTML = `
                <div class="min-w-0">
                    <p class="font-bold text-neutral-text truncate">${att.student_name}</p>
                    <p class="text-[10px] text-neutral-muted truncate">${att.class} • NISN: ${att.nisn}</p>
                </div>
                <div class="text-right shrink-0">
                    <span class="inline-block px-2.5 py-0.5 rounded-full font-bold text-[10px] border ${badgeClass}">
                        ${att.status.charAt(0).toUpperCase() + att.status.slice(1)}
                    </span>
                    <span class="block text-[10px] text-neutral-muted font-mono mt-0.5">${att.time_in}</span>
                </div>
            `;
            feedList.insertBefore(card, feedList.firstChild);
        }

        function handleManualScan(e) {
            e.preventDefault();
            const input = document.getElementById('manual-token');
            const val = input.value.trim();
            if (val) {
                processScan(val);
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
                (decodedText) => { processScan(decodedText); },
                (errorMessage) => {}
            ).catch(err => {
                console.error("Camera access error:", err);
                document.getElementById('scanner-placeholder').classList.remove('hidden');
                showFeedback('error', 'Kamera tidak dapat diakses atau diblokir oleh browser. Gunakan input manual di bawah.');
            });
        }
    </script>
</x-layouts.app>
