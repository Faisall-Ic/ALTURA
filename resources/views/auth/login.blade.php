<x-layouts.guest>
    <x-slot:title>Masuk ke Akun — ALTURA</x-slot:title>

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-8">
        <!-- Apple ID-style Minimalist Icon Box -->
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-brand-primary text-white shadow-sm mb-4 transition-transform duration-200 hover:scale-105">
            <span class="font-black text-2xl tracking-tighter">A</span>
        </div>

        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-neutral-text">
            Masuk ke Portal ALTURA
        </h1>
        <p class="mt-1.5 text-sm text-neutral-muted">
            Gunakan Akun Akademik Bimbingan Belajar Alto Anda
        </p>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Apple-Inspired Minimalist White Card -->
        <div class="bg-surface border border-neutral-border rounded-2xl shadow-sm p-6 sm:p-8">
            
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-accent-soft border border-red-200 text-red-accent-text text-sm flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation text-red-accent mt-0.5 text-base"></i>
                    <div>
                        <p class="font-bold leading-tight">Autentikasi Gagal</p>
                        <p class="text-xs text-red-accent-text/90 mt-0.5 leading-normal">{{ $errors->first() }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-neutral-muted uppercase tracking-wider mb-1.5">
                        Alamat Email
                    </label>
                    <div class="relative rounded-xl shadow-2xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-neutral-muted">
                            <i class="fa-solid fa-envelope text-sm"></i>
                        </div>
                        <input type="email" name="email" id="email" required autofocus
                            value="{{ old('email') }}"
                            placeholder="nama@altura.test"
                            class="block w-full pl-10 pr-4 py-3 rounded-xl bg-neutral-subtle/50 border border-neutral-border text-neutral-text placeholder-neutral-muted text-sm focus:outline-none focus:ring-2 focus:ring-brand-active/20 focus:border-brand-active focus:bg-white transition-all">
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-neutral-muted uppercase tracking-wider">
                            Kata Sandi
                        </label>
                    </div>
                    <div class="relative rounded-xl shadow-2xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-neutral-muted">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                            placeholder="••••••••"
                            class="block w-full pl-10 pr-11 py-3 rounded-xl bg-neutral-subtle/50 border border-neutral-border text-neutral-text placeholder-neutral-muted text-sm focus:outline-none focus:ring-2 focus:ring-brand-active/20 focus:border-brand-active focus:bg-white transition-all">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-neutral-muted hover:text-neutral-text transition-colors" title="Lihat Kata Sandi">
                            <i id="eye-icon" class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center cursor-pointer select-none">
                        <input type="checkbox" name="remember" id="remember" value="1"
                            class="w-4 h-4 rounded border-neutral-border text-brand-primary focus:ring-brand-active/30 cursor-pointer">
                        <span class="ml-2.5 text-xs text-neutral-muted font-medium">Ingat perangkat ini</span>
                    </label>
                    <span class="text-xs text-neutral-muted flex items-center gap-1.5" title="Sesi login aktif hingga 120 menit">
                        <i class="fa-solid fa-clock-rotate-left text-[11px]"></i>
                        120 menit
                    </span>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-brand-primary hover:bg-brand-navy rounded-xl shadow-xs transition-all duration-200 cursor-pointer">
                        <span>Lanjutkan</span>
                        <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                    </button>
                </div>
            </form>

            <!-- Quick Demo Credentials Selector -->
            <div class="mt-8 pt-6 border-t border-neutral-border">
                <p class="text-[11px] uppercase tracking-wider font-bold text-neutral-muted mb-3 text-center">
                    Akses Cepat Pengujian (5 Role)
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    <button type="button" onclick="fillCredential('siswa@altura.test', 'password')"
                        class="px-2.5 py-2 rounded-xl bg-neutral-subtle hover:bg-brand-soft hover:text-brand-primary border border-neutral-border text-[11px] text-neutral-text font-semibold transition-colors text-left truncate flex items-center gap-2">
                        <i class="fa-solid fa-graduation-cap text-emerald-600 shrink-0"></i>
                        <span class="truncate">Siswa</span>
                    </button>

                    <button type="button" onclick="fillCredential('budi.guru@altura.test', 'password')"
                        class="px-2.5 py-2 rounded-xl bg-neutral-subtle hover:bg-brand-soft hover:text-brand-primary border border-neutral-border text-[11px] text-neutral-text font-semibold transition-colors text-left truncate flex items-center gap-2">
                        <i class="fa-solid fa-chalkboard-user text-brand-primary shrink-0"></i>
                        <span class="truncate">Pak Budi (Guru)</span>
                    </button>

                    <button type="button" onclick="fillCredential('nisa.bk@altura.test', 'password')"
                        class="px-2.5 py-2 rounded-xl bg-neutral-subtle hover:bg-brand-soft hover:text-brand-primary border border-neutral-border text-[11px] text-neutral-text font-semibold transition-colors text-left truncate flex items-center gap-2">
                        <i class="fa-solid fa-brain text-purple-600 shrink-0"></i>
                        <span class="truncate">Kak Nisa (BK)</span>
                    </button>

                    <button type="button" onclick="fillCredential('ortu@altura.test', 'password')"
                        class="px-2.5 py-2 rounded-xl bg-neutral-subtle hover:bg-brand-soft hover:text-brand-primary border border-neutral-border text-[11px] text-neutral-text font-semibold transition-colors text-left truncate flex items-center gap-2">
                        <i class="fa-solid fa-user-group text-amber-accent shrink-0"></i>
                        <span class="truncate">Wali Siswa</span>
                    </button>

                    <button type="button" onclick="fillCredential('admin@altura.test', 'password')"
                        class="px-2.5 py-2 rounded-xl bg-neutral-subtle hover:bg-brand-soft hover:text-brand-primary border border-neutral-border text-[11px] text-neutral-text font-semibold transition-colors text-left truncate flex items-center gap-2 col-span-2 sm:col-span-2">
                        <i class="fa-solid fa-shield-halved text-rose-600 shrink-0"></i>
                        <span class="truncate">Admin Alto</span>
                    </button>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="mt-6 text-center text-xs text-neutral-muted">
                <span>Enkripsi Sesi Aktif • Bebas Paywall &amp; Captcha</span>
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-neutral-muted">
            &copy; {{ date('Y') }} Bimbel Alto (Alumni Tutorial). Hak Cipta Dilindungi.
        </p>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.className = 'fa-solid fa-eye-slash text-sm';
            } else {
                passwordInput.type = 'password';
                eyeIcon.className = 'fa-solid fa-eye text-sm';
            }
        }

        function fillCredential(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</x-layouts.guest>
