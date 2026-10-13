<x-layouts.app>
    <x-slot:title>WhatsApp Gateway & Logs Audit — Admin</x-slot:title>

    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-neutral-border shadow-xs p-6 sm:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-soft text-brand-primary border border-brand-primary/20 mb-3">
                        <span class="w-2 h-2 rounded-full bg-brand-primary animate-pulse"></span>
                        Outbound Messaging Automation
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-neutral-text">WhatsApp Gateway &amp; Logs Audit <i class="fa-solid fa-paper-plane ml-1 text-brand-primary"></i></h1>
                    <p class="text-neutral-muted text-sm mt-1">
                        Monitoring pesan notifikasi presensi otomatis, alert siswa berisiko, kartu nilai Tryout, dan pengujian gateway.
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-neutral-subtle hover:bg-neutral-border/60 text-neutral-text text-xs font-semibold border border-neutral-border transition-all">
                        <i class="fa-solid fa-arrow-left text-[11px]"></i> Dashboard Admin
                    </a>
                </div>
            </div>
        </div>

        <!-- KPI Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white border border-neutral-border rounded-2xl p-5 border-l-4 border-l-emerald-600 shadow-xs">
                <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Total Pesan Terkirim</span>
                <div class="text-3xl font-extrabold text-neutral-text mt-2">{{ $totalSent }}</div>
                <p class="text-xs text-neutral-muted mt-1">Notifikasi sukses disampaikan</p>
            </div>

            <div class="bg-white border border-neutral-border rounded-2xl p-5 border-l-4 border-l-red-accent shadow-xs">
                <span class="text-xs font-semibold text-red-accent-text uppercase tracking-wider">Gagal Terkirim</span>
                <div class="text-3xl font-extrabold text-neutral-text mt-2">{{ $totalFailed }}</div>
                <p class="text-xs text-neutral-muted mt-1">Nomor salah atau offline</p>
            </div>

            <div class="bg-white border border-neutral-border rounded-2xl p-5 border-l-4 border-l-amber-accent shadow-xs">
                <span class="text-xs font-semibold text-amber-accent-text uppercase tracking-wider">Antrean / Pending</span>
                <div class="text-3xl font-extrabold text-neutral-text mt-2">{{ $totalQueued }}</div>
                <p class="text-xs text-neutral-muted mt-1">Pesan dalam antrean sistem</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Logs Table (2 Cols) -->
            <div class="lg:col-span-2 bg-white border border-neutral-border rounded-2xl p-6 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-neutral-text">Log Pengiriman Pesan Terkini</h2>
                    <div class="flex items-center gap-1.5 text-xs">
                        <a href="{{ route('admin.whatsapp.index') }}" class="px-3 py-1 rounded-lg {{ empty($status) ? 'bg-brand-primary text-white font-semibold shadow-xs' : 'bg-neutral-subtle text-neutral-muted hover:bg-neutral-border/50' }}">Semua</a>
                        <a href="{{ route('admin.whatsapp.index', ['status' => 'sent']) }}" class="px-3 py-1 rounded-lg {{ $status === 'sent' ? 'bg-emerald-600 text-white font-semibold shadow-xs' : 'bg-neutral-subtle text-neutral-muted hover:bg-neutral-border/50' }}">Terkirim</a>
                        <a href="{{ route('admin.whatsapp.index', ['status' => 'failed']) }}" class="px-3 py-1 rounded-lg {{ $status === 'failed' ? 'bg-red-accent text-white font-semibold shadow-xs' : 'bg-neutral-subtle text-neutral-muted hover:bg-neutral-border/50' }}">Gagal</a>
                    </div>
                </div>

                @if ($logs->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-neutral-text">
                            <thead class="text-xs uppercase bg-neutral-subtle/70 text-neutral-muted border-b border-neutral-border font-semibold">
                                <tr>
                                    <th class="px-3 py-3">Tujuan</th>
                                    <th class="px-3 py-3">Pemicu Event</th>
                                    <th class="px-3 py-3">Status</th>
                                    <th class="px-3 py-3">Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-border">
                                @foreach ($logs as $log)
                                    <tr class="hover:bg-neutral-subtle/50 transition-colors">
                                        <td class="px-3 py-3 font-mono text-xs text-brand-primary font-semibold">
                                            {{ $log->parent_phone_number }}
                                        </td>
                                        <td class="px-3 py-3 text-xs text-neutral-muted">
                                            <span class="px-2 py-0.5 rounded-md bg-neutral-subtle text-neutral-text text-[10px] font-semibold border border-neutral-border">
                                                {{ $log->trigger_event }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-3">
                                            @if ($log->status === 'sent')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    <i class="fa-solid fa-check text-[9px] mr-1"></i> Sent
                                                </span>
                                            @elseif ($log->status === 'failed')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-accent-soft text-red-accent-text border border-red-accent/30">
                                                    <i class="fa-solid fa-xmark text-[9px] mr-1"></i> Failed
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-accent-soft text-amber-accent-text border border-amber-accent/30">
                                                    Queued
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3 text-xs text-neutral-muted">
                                            {{ $log->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                @else
                    <p class="text-xs text-neutral-muted py-8 text-center">Belum ada riwayat pesan.</p>
                @endif
            </div>

            <!-- Test Send Tool (1 Col) -->
            <div class="space-y-6">
                <div class="bg-white border border-neutral-border rounded-2xl p-6 shadow-xs">
                    <h3 class="text-base font-bold text-neutral-text mb-1 flex items-center gap-2">
                        <span><i class="fa-solid fa-vial text-brand-primary"></i> Uji Coba Pengiriman Pesan</span>
                    </h3>
                    <p class="text-xs text-neutral-muted mb-4">Kirimkan pesan uji coba ke nomor tujuan untuk memeriksa fungsionalitas gateway.</p>

                    <form method="POST" action="{{ route('admin.whatsapp.test') }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-neutral-text mb-1">Nomor WhatsApp Tujuan</label>
                            <input type="text" name="phone" required placeholder="Contoh: 6281234567890" class="w-full bg-surface border border-neutral-border rounded-lg p-2.5 text-xs text-neutral-text font-mono focus:outline-none focus:border-brand-primary">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-neutral-text mb-1">Isi Pesan Uji Coba</label>
                            <textarea name="message" rows="3" required placeholder="Ketik pesan testing..." class="w-full bg-surface border border-neutral-border rounded-lg p-2.5 text-xs text-neutral-text focus:outline-none focus:border-brand-primary">Uji coba integrasi WhatsApp Gateway ALTURA Bimbel Alto. Sistem operasional normal.</textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl bg-brand-primary hover:bg-brand-navy text-white font-bold text-xs shadow-xs transition-all">
                            Kirim Pesan Uji Coba
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
