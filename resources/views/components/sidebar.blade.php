<aside id="mobile-menu"
    class="fixed z-20 transform overflow-hidden transition-transform duration-300 md:relative md:translate-x-0 h-screen w-[280px] bg-transparent shadow-md text-white hidden md:block p-3">
    <!-- Container untuk background dengan padding -->
    <div class="bg-[#0A749B] rounded-2xl h-full px-4 py-6 flex flex-col">
        <!-- Logo section -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex justify-center">
                <img src="{{ asset('logo/navlogo.png') }}" alt="Logo Aplikasi Rapat Harian" class="w-40">
            </div>
            <button id="menu-toggle-close"
                class="md:hidden relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-[#0A749B] hover:text-white focus:outline-none">
                <span class="sr-only">Open main menu</span>
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Navigation dengan style yang lebih modern -->
        <nav class="space-y-2 flex-grow">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                <i class="fas fa-home w-6 h-6"></i>
                <span class="ml-3 text-base">Dashboard</span>
            </a>
            <a href="{{ route('admin.monitor-kinerja') }}"
            class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.monitor-kinerja') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
            <i class="fas fa-chart-line w-6 h-6"></i>
            <span class="ml-3 text-base">Monitor Kinerja Pembangkit</span>
            </a>
            <a href="{{ route('admin.daily-summary') }}"
                class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.daily-summary') || request()->routeIs('admin.daily-summary.results') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                <i class="fas fa-calendar-day w-6 h-6"></i>
                <span class="ml-3 text-base">Ikhtisar Harian</span>
            </a>

            <a href="{{ route('admin.machine-status.view') }}"
                class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.machine-status.view') || request()->routeIs('admin.machine-status.*') || request()->routeIs('admin.pembangkit.ready') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                <i class="fas fa-tools w-6 h-6"></i>
                <span class="ml-3 text-base">laporan Kesiapan Kit</span>
            </a>

            <a href="{{ route('admin.rencana-daya-mampu') }}"
                class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.rencana-daya-mampu') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                <i class="fas fa-bolt w-6 h-6"></i>
                <span class="ml-3 text-base">Rencana Daya Mampu Bulanan</span>
            </a>

            <a href="{{ route('admin.machine-monitor') }}"
                class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.machine-monitor') || request()->routeIs('admin.machine-monitor.show') || request()->routeIs('admin.power-plants.index') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                <i class="fas fa-desktop w-6 h-6"></i>
                <span class="ml-3 text-base">Monitor Mesin</span>
            </a>

           

            <a href="{{ route('admin.users') }}"
                class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.users') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                <i class="fas fa-users w-6 h-6"></i>
                <span class="ml-3 text-base">Manajemen Pengguna</span>
            </a>

            

            @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.settings') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.settings') ? 'bg-white/10 text-white font-medium' : 'text-gray-100 hover:bg-white/10' }}">
                    <i class="fas fa-cog w-6 h-6"></i>
                    <span class="ml-3 text-base">Pengaturan</span>
                </a>
            @endif

            
        </nav>

        <!-- Bottom Section: Logout -->
        <div class="mt-2">
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <button type="button" 
                    onclick="confirmLogout()"
                    class="flex items-center w-full px-3 py-2.5 rounded-lg text-white bg-red-400 hover:bg-red-700 transition-colors duration-200">
                    <i class="fas fa-sign-out-alt w-6 h-6"></i>
                    <span class="ml-3 text-base">Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>
{{-- 
<script>
// Fungsi untuk toggle dropdown
document.addEventListener('DOMContentLoaded', function() {
    const dropdownButton = document.querySelector('#machine-monitor-dropdown');
    const submenu = document.querySelector('#machine-monitor-submenu');
    let isOpen = false;

    dropdownButton.addEventListener('click', function(e) {
        e.preventDefault();
        isOpen = !isOpen;
        
        if (isOpen) {
            submenu.style.maxHeight = submenu.scrollHeight + 'px';
            dropdownButton.classList.add('rotate-180');
        } else {
            submenu.style.maxHeight = '0';
            dropdownButton.classList.remove('rotate-180');
        }
    });
});
</script> --}}

<script>
function confirmLogout() {
    Swal.fire({
        title: 'Konfirmasi Logout',
        text: "Apakah anda yakin untuk logout?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logout-form').submit();
        }
    });
}
</script>