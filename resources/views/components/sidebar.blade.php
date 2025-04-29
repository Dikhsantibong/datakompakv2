<aside id="mobile-menu"
    class="fixed z-20 transform overflow-hidden transition-transform duration-300 md:relative md:translate-x-0 h-screen w-[280px] bg-transparent shadow-md text-white hidden md:block p-3">
    <!-- Container untuk background dengan padding -->
    <div class="bg-[#0A749B] rounded-2xl h-full px-4 py-6 flex flex-col">
        <!-- Logo section -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex justify-center items-center">
                <img src="{{ asset('logo/navlogo.png') }}" alt="Logo Aplikasi Rapat Harian" class="w-40">
            </div>
            <button id="menu-toggle-close"
                class="md:hidden relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-[#0A749B] hover:text-white focus:outline-none">
                <span class="sr-only">Open main menu</span>
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Navigation Menu -->
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            <nav class="space-y-3">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-4 py-3 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                    <i class="fas fa-home w-5 h-5"></i>
                    <span class="ml-3 text-sm">Dashboard</span>
                </a>

                <!-- Monitoring Datakompak -->
                <a href="{{ route('admin.monitoring-datakompak') }}"
                    class="flex items-center px-4 py-3 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.monitoring-datakompak') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                    <i class="fas fa-chart-pie w-5 h-5"></i>
                    <span class="ml-3 text-sm">Monitoring Datakompak</span>
                </a>

                <!-- Kalender Operasi -->
                <a href="{{ route('admin.kalender.calendar') }}"
                    class="flex items-center px-4 py-3 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.kalender.calendar') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                    <i class="fas fa-calendar-alt w-5 h-5"></i>
                    <span class="ml-3 text-sm">Kalender Operasi</span>
                </a>

                <!-- Monitoring Section -->
                <a href="{{ route('admin.monitor-kinerja') }}"
                    class="flex items-center px-4 py-3 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.monitor-kinerja') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                    <i class="fas fa-chart-line w-5 h-5"></i>
                    <span class="ml-3 text-sm">Monitoring Kinerja UP Kendari</span>
                </a>

                <a href="{{ route('admin.machine-monitor') }}"
                    class="flex items-center px-4 py-3 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.machine-monitor') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                    <i class="fas fa-cogs w-5 h-5"></i>
                    <span class="ml-3 text-sm">Data Mesin Pembangkit</span>
                </a>

                <!-- Operator KIT Dropdown -->
                <div class="relative" x-data="{ 
                    open: {{ request()->routeIs('admin.meeting-shift.*') || 
                             request()->routeIs('admin.flm.*') || 
                             request()->routeIs('admin.5s5r.*') || 
                             request()->routeIs('admin.k3-kamp.*') ? 'true' : 'false' }} 
                }">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-4 py-3 rounded-lg transition-colors duration-300 text-gray-100 hover:bg-white/10">
                        <div class="flex items-center">
                            <i class="fas fa-users-cog w-5 h-5"></i>
                            <span class="ml-3 text-sm">Operator KIT</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-300" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         @click.away="open = false" 
                         class="pl-4 mt-1 space-y-1">
                        <a href="{{ route('admin.meeting-shift.index') }}" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.meeting-shift.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                            <i class="fas fa-exchange-alt w-5 h-5"></i>
                            <span class="ml-3 text-sm">Meeting-Mutasi-shift</span>
                        </a>
                        <a href="{{ route('admin.abnormal-report.index') }}" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.laporan-abnormal.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                            <i class="fas fa-exclamation-triangle w-5 h-5"></i>
                            <span class="ml-3 text-sm">Laporan Abnormal/Gangguan</span>
                        </a>
                        <a href="{{ route('admin.flm.index') }}" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.flm.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                            <i class="fas fa-tasks w-5 h-5"></i>
                            <span class="ml-3 text-sm">FLM</span>
                        </a>
                        <a href="{{ route('admin.5s5r.index') }}" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.5s5r.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                            <i class="fas fa-check-double w-5 h-5"></i>
                            <span class="ml-3 text-sm">5S5R</span>
                        </a>
                        <a href="{{ route('admin.k3-kamp.index') }}" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.k3-kamp.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                            <i class="fas fa-hard-hat w-5 h-5"></i>
                            <span class="ml-3 text-sm">K3, KAM & Lingkungan</span>
                        </a>
                    <a href="{{ route('admin.data-engine.index') }}" 
                    class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.data-engine.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                        <i class="fas fa-cogs w-5 h-5"></i> 
                        <span class="ml-3 text-sm">Data Engine Perjam</span>
                        </a>

                        <a href="{{ route('admin.laporan-kit.index') }}" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.laporan-kit.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                            <i class="fas fa-clock w-5 h-5"></i>
                            <span class="ml-3 text-sm">Laporan KIT 00.00</span>
                        </a>

                        <a href="{{ route('admin.patrol-check.index') }}" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.patrol-check.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                            <i class="fas fa-clipboard-check w-5 h-5"></i>
                            <span class="ml-3 text-sm">Patrol Check KIT</span>
                        </a>
                    </div>
                </div>

                <!-- Operasi UL/Sentral Dropdown -->
                <div class="relative" x-data="{ 
                    open: {{ request()->routeIs('admin.daily-summary') || 
                             request()->routeIs('admin.machine-status.*') || 
                             request()->routeIs('admin.pembangkit.*') || 
                             request()->routeIs('admin.rencana-daya-mampu') ? 'true' : 'false' }} 
                }">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-4 py-3 rounded-lg transition-colors duration-300 text-left {{ request()->routeIs('admin.daily-summary') || request()->routeIs('admin.machine-status.*') || request()->routeIs('admin.pembangkit.*') || request()->routeIs('admin.rencana-daya-mampu') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                        <div class="flex items-center">
                            <i class="fas fa-industry w-5 h-5"></i>
                            <span class="ml-3 text-sm">Operasi UL/Sentral</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-300" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         @click.away="open = false" 
                         class="pl-4 mt-1 space-y-1">
                        <a href="{{ route('admin.daily-summary') }}" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.daily-summary') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                            <i class="fas fa-calendar-day w-5 h-5"></i>
                            <span class="ml-3 text-sm">Ikhtisar Harian</span>
                        </a>
                        <a href="{{ route('admin.machine-status.view') }}" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.machine-status.*') || request()->routeIs('admin.pembangkit.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                            <i class="fas fa-tools w-5 h-5"></i>
                            <span class="ml-3 text-sm">Kesiapan KIT</span>
                        </a>
                        <a href="{{ route('admin.rencana-daya-mampu') }}" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.rencana-daya-mampu') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                            <i class="fas fa-bolt w-5 h-5"></i>
                            <span class="ml-3 text-sm">Rencana Daya Mampu Bulanan</span>
                        </a>
                        
                        <a href="{{ route('admin.energiprimer.bahan-bakar') }}" 
                        class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.energiprimer.bahan-bakar') || request()->routeIs('admin.energiprimer.bahan-bakar.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                         <i class="fas fa-gas-pump w-5 h-5"></i>
                         <span class="ml-3 text-sm">BBM</span>
                     </a>
                     <a href="{{ route('admin.energiprimer.pelumas') }}" 
                        class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.energiprimer.pelumas') || request()->routeIs('admin.energiprimer.pelumas.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                         <i class="fas fa-oil-can w-5 h-5"></i>
                         <span class="ml-3 text-sm">Pelumas</span>
                     </a>
                     <a href="{{ route('admin.energiprimer.bahan-kimia') }}" 
                        class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.energiprimer.bahan-kimia') || request()->routeIs('admin.energiprimer.bahan-kimia.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                         <i class="fas fa-flask w-5 h-5"></i>
                         <span class="ml-3 text-sm">Bahan Kimia</span>
                     </a>
                    </div>
                </div>

                <!-- Operasi UPKD Dropdown -->
                <div class="relative" x-data="{ 
                    open: {{ request()->routeIs('admin.operasi-upkd.*') ? 'true' : 'false' }} 
                }">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-4 py-3 rounded-lg transition-colors duration-300 text-gray-100 hover:bg-white/10">
                        <div class="flex items-center">
                            <i class="fas fa-building w-5 h-5"></i>
                            <span class="ml-3 text-sm">Operasi UPKD</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-300" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         @click.away="open = false" 
                         class="pl-4 mt-1 space-y-1">
                        <a href="{{ route('admin.operasi-upkd.rapat.index') }}" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.operasi-upkd.rapat.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                            <i class="fas fa-handshake w-5 h-5"></i>
                            <span class="ml-3 text-sm">Rapat & Link Koordinasi RON</span>
                        </a>
                        <a href="{{ route('admin.operasi-upkd.program-kerja.index') }}" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.operasi-upkd.program-kerja.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                            <i class="fas fa-project-diagram w-5 h-5"></i>
                            <span class="ml-3 text-sm">Program Kerja</span>
                        </a>
                        <a href="{{ route('admin.operasi-upkd.kinerja.index') }}" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.operasi-upkd.kinerja.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                            <i class="fas fa-chart-bar w-5 h-5"></i>
                            <span class="ml-3 text-sm">Kinerja dan Transaksi Energi</span>
                        </a>
                        <a href="{{ route('admin.operasi-upkd.pengadaan.index') }}" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.operasi-upkd.pengadaan.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                            <i class="fas fa-shopping-cart w-5 h-5"></i>
                            <span class="ml-3 text-sm">Pengadaan Barang dan Jasa</span>
                        </a>
                        <a href="{{ route('admin.operasi-upkd.maturity.index') }}" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.operasi-upkd.maturity.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                            <i class="fas fa-chart-line w-5 h-5"></i>
                            <span class="ml-3 text-sm">Maturity Level</span>
                        </a>
                        <a href="{{ route('admin.operasi-upkd.laporan.index') }}" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.operasi-upkd.laporan.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                            <i class="fas fa-file-alt w-5 h-5"></i>
                            <span class="ml-3 text-sm">Laporan Operasi UPKD</span>
                        </a>
                        <a href="{{ route('admin.operasi-upkd.rjpp-dpr.index') }}" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.operasi-upkd.rjpp-dpr.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                            <i class="fas fa-chart-line w-5 h-5"></i>
                            <span class="ml-3 text-sm">RJPP-DPR</span>
                        </a>
                    </div>
                </div>

                <!-- Energi Primer UPKD Dropdown -->
                <div class="relative" x-data="{ 
                    open: {{ request()->routeIs('admin.energiprimer.*') || request()->routeIs('admin.energiprimer-upkd.*') ? 'true' : 'false' }} 
                }">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-4 py-3 rounded-lg transition-colors duration-300 text-left {{ request()->routeIs('admin.energiprimer.*') || request()->routeIs('admin.energiprimer-upkd.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                        <div class="flex items-center">
                            <i class="fas fa-battery-three-quarters w-5 h-5"></i>
                            <span class="ml-3 text-sm">Energi Primer UPKD</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-300" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         @click.away="open = false" 
                         class="pl-4 mt-1 space-y-1">
                        <a href="#" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.energiprimer-upkd.rapat.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                            <i class="fas fa-handshake w-5 h-5"></i>
                            <span class="ml-3 text-sm">Rapat dan Link Koordinasi EP</span>
                        </a>
                        <a href="#" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.energiprimer-upkd.program-kerja.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                            <i class="fas fa-project-diagram w-5 h-5"></i>
                            <span class="ml-3 text-sm">Program Kerja</span>
                        </a>
                        <a href="#" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.energiprimer-upkd.maturity.*') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                            <i class="fas fa-chart-line w-5 h-5"></i>
                            <span class="ml-3 text-sm">Maturity Level</span>
                        </a>
                       
                    </div>
                </div>

                <!-- Library -->
                <a href="{{ route('admin.library.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg transition-colors duration-300 {{ request()->routeIs('admin.library.index') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                    <i class="fas fa-book w-5 h-5"></i>
                    <span class="ml-3 text-sm">Library</span>
                </a>

                <!-- Fitur Pendukung -->
                <div class="relative" x-data="{ 
                    open: {{ request()->routeIs('admin.users') || 
                             request()->routeIs('admin.settings') ? 'true' : 'false' }} 
                }">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-4 py-3 rounded-lg transition-colors duration-300 text-left text-gray-100 hover:bg-white/10">
                        <div class="flex items-center">
                            <i class="fas fa-tools w-5 h-5"></i>
                            <span class="ml-3 text-sm">Fitur Pendukung</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-300" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         @click.away="open = false" 
                         class="pl-4 mt-1 space-y-1">
                        <a href="{{ route('admin.users') }}" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 text-gray-100 hover:bg-white/10">
                            <i class="fas fa-users w-5 h-5"></i>
                            <span class="ml-3 text-sm">Manajemen Pengguna</span>
                        </a>
                        <a href="{{ route('admin.settings') }}" 
                           class="flex items-center px-4 py-2 rounded-lg transition-colors duration-300 text-gray-100 hover:bg-white/10">
                            <i class="fas fa-cog w-5 h-5"></i>
                            <span class="ml-3 text-sm">Pengaturan</span>
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</aside>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.25);
    }

    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.15) rgba(255, 255, 255, 0.05);
    }
</style>