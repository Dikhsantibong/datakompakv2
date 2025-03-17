<aside id="mobile-menu"
    class="fixed z-20 transform overflow-hidden transition-transform duration-300 md:relative md:translate-x-0 h-screen w-[280px] bg-transparent shadow-md text-white hidden md:block p-3">
    <!-- Container untuk background dengan padding -->
    <div class="bg-[#0A749B] rounded-2xl h-full px-4 py-6 flex flex-col">
        <!-- Logo section -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex justify-center items-center">
                <img src="{{ asset('logo/navlogo.png') }}" alt="Logo Aplikasi Rapat Harian" class="w-40">
            </div>
            <button id="menu-toggle-close"
                class="md:hidden relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-[#0A749B] hover:text-white focus:outline-none">
                <span class="sr-only">Open main menu</span>
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Navigation dengan style yang lebih modern -->
        <nav class="space-y-2 flex-grow">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                <i class="fas fa-home w-5 h-5"></i>
                <span class="ml-3 text-sm">Dashboard</span>
            </a>

            <!-- Dropdown Menu 1 -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2.5 rounded-lg text-gray-100 hover:bg-white/10">
                    <div class="flex items-center">
                        <i class="fas fa-chart-bar w-5 h-5"></i>
                        <span class="ml-3 text-sm">Energi Primer</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform" :class="{'rotate-180': open}"></i>
                </button>
                <div x-show="open" @click.away="open = false" class="pl-4 mt-1 space-y-1">
                    <a href="{{ route('admin.energiprimer.bahan-bakar') }}" class="flex items-center px-3 py-2 rounded-lg text-gray-100 hover:bg-white/10">
                        <i class="fas fa-file-alt w-5 h-5"></i>
                        <span class="ml-3 text-sm">Bahan Bakar</span>
                    </a>
                    <a href="{{ route('admin.energiprimer.pelumas') }}" class="flex items-center px-3 py-2 rounded-lg text-gray-100 hover:bg-white/10">
                        <i class="fas fa-file-contract w-5 h-5"></i>
                        <span class="ml-3 text-sm">Pelumas</span>
                    </a>
                    <a href="{{ route('admin.energiprimer.bahan-kima') }}" class="flex items-center px-3 py-2 rounded-lg text-gray-100 hover:bg-white/10">
                        <i class="fas fa-file-invoice w-5 h-5"></i>
                        <span class="ml-3 text-sm">Bahan Kimia</span>
                    </a>
                </div>
            </div>

            <!-- Dropdown Menu 2 -->
           

            <a href="{{ route('admin.monitor-kinerja') }}"
            class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.monitor-kinerja') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
            <i class="fas fa-chart-line w-5 h-5"></i>
            <span class="ml-3 text-sm">Monitor Kinerja Pembangkit</span>
            </a>
            <a href="{{ route('admin.daily-summary') }}"
                class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.daily-summary') || request()->routeIs('admin.daily-summary.results') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                <i class="fas fa-calendar-day w-5 h-5"></i>
                <span class="ml-3 text-sm">Ikhtisar Harian</span>
            </a>

            <a href="{{ route('admin.machine-status.view') }}"
                class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.machine-status.view') || request()->routeIs('admin.machine-status.*') || request()->routeIs('admin.pembangkit.ready') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                <i class="fas fa-tools w-5 h-5"></i>
                <span class="ml-3 text-sm">laporan Kesiapan Kit</span>
            </a>

            <a href="{{ route('admin.rencana-daya-mampu') }}"
                class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.rencana-daya-mampu') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                <i class="fas fa-bolt w-5 h-5"></i>
                <span class="ml-3 text-sm">Rencana Daya Mampu Bulanan</span>
            </a>

            <a href="{{ route('admin.machine-monitor') }}"
                class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.machine-monitor') || request()->routeIs('admin.machine-monitor.show') || request()->routeIs('admin.power-plants.index') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                <i class="fas fa-cogs w-5 h-5"></i>
                <span class="ml-3 text-sm">Monitor Mesin</span>
            </a>
            <a href="{{ route('admin.administrasi_operasi.index')}}"
                class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.administrasi_operasi.index') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                <i class="fas fa-clipboard-list w-5 h-5"></i>
                <span class="ml-3 text-sm">Administrasi Operasi</span>
            </a>

            <a href="{{ route('admin.library.index')}}"
                class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.library.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                <i class="fas fa-book w-5 h-5"></i>
                <span class="ml-3 text-sm">Library</span>
            </a>

            <a href="{{ route('admin.users') }}"
                class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.users') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                <i class="fas fa-users w-5 h-5"></i>
                <span class="ml-3 text-sm">Manajemen Pengguna</span>
            </a>
            

            @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.settings') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.settings') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                    <i class="fas fa-cog w-5 h-5"></i>
                    <span class="ml-3 text-sm">Pengaturan</span>
                </a>
            @endif

        </nav>

        {{-- <!-- Bottom Section: Logout -->
        <div class="mt-2">
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <button type="button" 
                    onclick="confirmLogout()"
                    class="flex items-center w-full px-3 py-2.5 rounded-lg text-white bg-red-400 hover:bg-red-700 transition-colors duration-200">
                    <i class="fas fa-sign-out-alt w-5 h-5"></i>
                    <span class="ml-3 text-sm">Logout</span>
                </button>
            </form>
        </div> --}}
    </div>
</aside>