<!-- Top Header Bar (Frosted Glass) -->
<header class="bg-white/80 backdrop-blur-md border-b border-neutral-border px-4 sm:px-6 py-3.5 flex justify-between items-center sticky top-0 z-30 shadow-xs">
    <div class="flex items-center gap-3">
        <!-- Mobile Toggle Button -->
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-xl text-neutral-muted hover:text-neutral-text hover:bg-neutral-subtle focus:outline-none transition-colors">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>

        <!-- Breadcrumbs & Page Title -->
        <div>
            <div class="flex items-center gap-1.5 text-xs text-neutral-muted">
                <span>ALTURA</span>
                <i class="fa-solid fa-chevron-right text-[9px] text-neutral-muted/60"></i>
                <span class="text-neutral-text font-medium">{{ $title ?? 'Dashboard' }}</span>
            </div>
            <h1 class="text-base sm:text-lg font-extrabold text-neutral-text leading-tight mt-0.5">{{ $title ?? 'Portal ALTURA' }}</h1>
        </div>
    </div>

    <!-- Top Header Right Controls -->
    <div class="flex items-center gap-3 sm:gap-4">
        <!-- Role Badge -->
        @php
            $user = auth()->user();
            $primaryRole = $user?->roles->first()?->name ?? 'user';
            $roleBadges = [
                'admin' => ['label' => 'Admin Alto', 'class' => 'bg-rose-50 text-rose-700 border-rose-200/80', 'icon' => 'fa-shield-halved'],
                'guru_bk' => ['label' => 'Guru BK', 'class' => 'bg-purple-50 text-purple-700 border-purple-200/80', 'icon' => 'fa-brain'],
                'guru' => ['label' => 'Guru / Mentor', 'class' => 'bg-brand-soft text-brand-primary border-blue-200/80', 'icon' => 'fa-chalkboard-user'],
                'siswa' => ['label' => 'Siswa Bimbel', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80', 'icon' => 'fa-graduation-cap'],
                'orang_tua' => ['label' => 'Orang Tua', 'class' => 'bg-amber-accent-soft text-amber-accent-text border-amber-200/80', 'icon' => 'fa-user-group'],
            ];
            $badge = $roleBadges[$primaryRole] ?? ['label' => ucfirst($primaryRole), 'class' => 'bg-neutral-subtle text-neutral-text border-neutral-border', 'icon' => 'fa-user'];
        @endphp
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $badge['class'] }}">
            <i class="fa-solid {{ $badge['icon'] }} text-[10px]"></i>
            <span class="hidden sm:inline">{{ $badge['label'] }}</span>
        </span>

        <!-- Notification Bell Icon -->
        <button type="button" class="relative p-2 text-neutral-muted hover:text-brand-dark rounded-xl hover:bg-neutral-subtle transition-colors" title="Pusat Notifikasi">
            <i class="fa-solid fa-bell text-base"></i>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-brand-active ring-2 ring-white"></span>
        </button>

        <!-- User Profile Dropdown / Actions -->
        @auth
            <div class="flex items-center gap-2.5 pl-3 border-l border-neutral-border">
                <div class="w-8 h-8 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center text-xs shadow-xs ring-1 ring-neutral-border">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="hidden md:block text-left">
                    <p class="text-xs font-bold text-neutral-text leading-tight truncate max-w-[130px]">{{ $user->name }}</p>
                    <p class="text-[10px] text-neutral-muted leading-tight truncate max-w-[130px]">{{ $user->email }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline ml-1">
                    @csrf
                    <button type="submit" class="p-2 text-neutral-muted hover:text-red-accent rounded-xl hover:bg-red-accent-soft transition-colors" title="Keluar dari Akun">
                        <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                    </button>
                </form>
            </div>
        @endauth
    </div>
</header>
