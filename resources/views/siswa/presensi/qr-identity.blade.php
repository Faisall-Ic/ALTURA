<x-layouts.app>
    <x-slot:title>QR Identitas Presensi</x-slot:title>

    <div class="max-w-md mx-auto space-y-6">
        <!-- Top Nav Link -->
        <div class="flex items-center justify-between text-xs">
            <a href="{{ route('siswa.dashboard') }}" class="inline-flex items-center gap-1.5 text-brand-primary hover:text-brand-navy font-semibold transition-colors">
                <i class="fa-solid fa-arrow-left text-[11px]"></i> Kembali ke Dashboard
            </a>
            <a href="{{ route('siswa.presensi.riwayat') }}" class="inline-flex items-center gap-1.5 text-neutral-muted hover:text-neutral-text font-medium transition-colors">
                Riwayat Presensi <i class="fa-solid fa-arrow-right text-[11px]"></i>
            </a>
        </div>

        <!-- Student ID Card (Apple Style Identity Card) -->
        <div class="bg-white border border-neutral-border rounded-3xl p-6 sm:p-8 shadow-sm relative overflow-hidden text-center">
            <!-- Brand Accent Bar -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-brand-primary to-brand-active"></div>

            <!-- Alto Brand Header -->
            <div class="flex items-center justify-between border-b border-neutral-border pb-4 mb-6 pt-1">
                <div class="text-left">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-brand-primary block">KARTU DIGITAL SISWA</span>
                    <span class="text-sm font-bold text-neutral-text tracking-tight">ALTURA • BIMBEL ALTO</span>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1 align-middle"></span> AKTIF
                </span>
            </div>

            <!-- Student Info -->
            <div class="space-y-1 mb-6">
                <h1 class="text-xl font-bold text-neutral-text">{{ $student->user?->name }}</h1>
                <p class="text-xs text-neutral-muted">
                    {{ $student->academicClass?->name }} • <span class="text-neutral-text font-semibold">{{ $student->major?->name }}</span>
                </p>
                <p class="text-xs font-mono text-brand-primary font-semibold pt-1">
                    NISN: {{ $student->nisn }}
                </p>
            </div>

            <!-- QR Code Container -->
            <div class="bg-surface p-5 rounded-2xl shadow-xs inline-block mx-auto border-2 border-neutral-border mb-6 relative group">
                <div id="qrcode" class="flex items-center justify-center min-w-[200px] min-h-[200px]"></div>
                <div class="mt-2 text-[10px] font-mono text-neutral-muted font-bold uppercase tracking-wider">
                    {{ $student->qr_identity_token }}
                </div>
            </div>

            <!-- Live Clock & Dynamic Refresh Info -->
            <div class="bg-canvas border border-neutral-border rounded-xl p-3 text-xs space-y-1 text-neutral-muted">
                <div class="flex items-center justify-center gap-2 text-neutral-text font-medium">
                    <span class="w-2 h-2 rounded-full bg-brand-active animate-ping"></span>
                    <span>Waktu Sekarang: <span id="live-time" class="font-mono text-neutral-text font-bold">--:--:--</span></span>
                </div>
                <p class="text-[11px] text-neutral-muted">
                    Tunjukkan QR ini ke Guru/Mentor saat presensi meja (desk-side scan).
                </p>
            </div>

            <!-- Mentor Details Footer -->
            <div class="mt-6 pt-4 border-t border-neutral-border text-left text-xs grid grid-cols-2 gap-2 text-neutral-muted">
                <div>
                    <span class="text-[10px] uppercase text-neutral-muted block">Mentor Resmi:</span>
                    <span class="text-brand-primary font-semibold">{{ $student->mentor?->user?->name ?? '-' }}</span>
                </div>
                <div class="text-right">
                    <span class="text-[10px] uppercase text-neutral-muted block">Kehadiran Kumulatif:</span>
                    <span class="font-bold text-emerald-700">{{ $stats['percentage'] }}% ({{ $stats['status_label'] }})</span>
                </div>
            </div>
        </div>
    </div>

    <!-- QRCode Generator Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const token = "{{ $student->qr_identity_token }}";
            new QRCode(document.getElementById("qrcode"), {
                text: token,
                width: 200,
                height: 200,
                colorDark: "#0f172a",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });

            // Live Clock
            function updateClock() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('id-ID');
                const liveEl = document.getElementById('live-time');
                if (liveEl) liveEl.textContent = timeString;
            }
            setInterval(updateClock, 1000);
            updateClock();
        });
    </script>
</x-layouts.app>
