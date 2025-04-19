@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50 overflow-hidden">
    @include('components.sidebar')
    
    <div id="main-content" class="flex-1 overflow-auto">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="flex justify-between items-center px-6 py-3">
                <div class="flex items-center gap-x-3">
                    <!-- Mobile Menu Toggle -->
                    <button id="mobile-menu-toggle"
                        class="md:hidden relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-blue-50 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <button id="desktop-menu-toggle"
                        class="hidden md:block relative items-center justify-center rounded-md text-gray-400 hover:bg-blue-50 p-2 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800">Kalender Operasi</h1>
                </div>

                @include('components.timer')
                
                <div class="relative">
                    <button id="dropdownToggle" class="flex items-center space-x-2 hover:bg-gray-50 rounded-lg px-2 py-1" onclick="toggleDropdown()">
                        <img src="{{ Auth::user()->avatar ?? asset('foto_profile/admin1.png') }}" class="w-7 h-7 rounded-full">
                        <span class="text-gray-700 text-sm">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                    </button>
                    <div id="dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg hidden z-10 border border-gray-100">
                        <a href="{{ route('logout') }}" 
                           class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Kalender Operasi', 'url' => null]]" />
        </div>

        <!-- Main Content -->
        <main class="px-6">
            <!-- Welcome Card -->
            <div class="rounded-lg shadow-sm p-4 mb-6 text-white relative welcome-card min-h-[200px] md:h-64">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-400 opacity-90 rounded-lg"></div>
                <div class="relative z-10">
                    <!-- Text Content -->
                    <div class="space-y-2 md:space-y-4">
                        <div style="overflow: hidden;">
                            <h2 class="text-2xl md:text-3xl font-bold tracking-tight typing-animation">
                                Kalender Operasi Pembangkit
                            </h2>
                        </div>
                        <p class="text-sm md:text-lg font-medium fade-in">
                            PLN NUSANTARA POWER UNIT PEMBANGKITAN KENDARI
                        </p>
                        <div class="backdrop-blur-sm bg-white/30 rounded-lg p-3 fade-in">
                            <p class="text-xs md:text-base leading-relaxed">
                                Platform manajemen jadwal operasi dan pemeliharaan pembangkit untuk perencanaan yang lebih efektif.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Logo - Hidden on mobile -->
                    <img src="{{ asset('logo/navlogo.png') }}" alt="Power Plant" class="hidden md:block absolute top-4 right-4 w-32 md:w-48 fade-in">
                </div>
            </div>

            <!-- Quick Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Today's Events -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="p-4">
                        <div class="text-3xl text-blue-600 mb-2">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-1">Jadwal Hari Ini</h3>
                        <p class="text-gray-600 mb-2 text-sm">
                            @php
                                $todayDate = now()->format('Y-m-d');
                                $todaySchedules = $allSchedules[$todayDate] ?? [];
                                $todayCount = count($todaySchedules);
                            @endphp
                            {{ $todayCount }} kegiatan
                        </p>
                        <span class="text-blue-600 text-sm font-medium">Perlu Perhatian</span>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="p-4">
                        <div class="text-3xl text-green-600 mb-2">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-1">Jadwal Mendatang</h3>
                        <p class="text-gray-600 mb-2 text-sm">
                            @php
                                $upcomingCount = collect($allSchedules)
                                    ->flatten(1)
                                    ->where('status', 'scheduled')
                                    ->count();
                            @endphp
                            {{ $upcomingCount }} kegiatan
                        </p>
                        <span class="text-green-600 text-sm font-medium">Terjadwal</span>
                    </div>
                </div>

                <!-- Completed Events -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="p-4">
                        <div class="text-3xl text-purple-600 mb-2">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-1">Selesai</h3>
                        <p class="text-gray-600 mb-2 text-sm">
                            @php
                                $completedCount = collect($allSchedules)
                                    ->flatten(1)
                                    ->where('status', 'completed')
                                    ->count();
                            @endphp
                            {{ $completedCount }} kegiatan
                        </p>
                        <span class="text-purple-600 text-sm font-medium">Terlaksana</span>
                    </div>
                </div>

                <!-- Total Events -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="p-4">
                        <div class="text-3xl text-yellow-600 mb-2">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-1">Total Jadwal</h3>
                        <p class="text-gray-600 mb-2 text-sm">
                            @php
                                $totalCount = collect($allSchedules)
                                    ->flatten(1)
                                    ->count();
                            @endphp
                            {{ $totalCount }} kegiatan
                        </p>
                        <span class="text-yellow-600 text-sm font-medium">Keseluruhan</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons and Filter -->
            <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('admin.kalender.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        <span>Tambah Jadwal</span>
                    </a>
                    
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="inline-flex items-center px-4 py-2 bg-white text-gray-700 rounded-lg border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm transition-colors">
                            <i class="fas fa-download mr-2 text-blue-600"></i>
                            <span>Export</span>
                            <i class="fas fa-chevron-down ml-2 text-gray-400"></i>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-10">
                            <div class="py-1">
                                <a href="{{ route('admin.kalender.export.excel') }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-file-excel mr-3 text-green-500"></i>
                                    Export to Excel
                                </a>
                                <a href="{{ route('admin.kalender.export.pdf') }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-file-pdf mr-3 text-red-500"></i>
                                    Export to PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors" onclick="previousMonth()">
                        <i class="fas fa-chevron-left text-gray-600"></i>
                    </button>
                    <span class="text-base font-medium text-gray-700 min-w-[150px] text-center" id="currentMonth"></span>
                    <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors" onclick="nextMonth()">
                        <i class="fas fa-chevron-right text-gray-600"></i>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Calendar View -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-4">
                            <!-- Calendar Header -->
                            <div class="grid grid-cols-7 bg-gray-50 rounded-t-lg border-b border-gray-200">
                                <div class="text-center py-2 text-sm font-medium text-gray-600">Min</div>
                                <div class="text-center py-2 text-sm font-medium text-gray-600">Sen</div>
                                <div class="text-center py-2 text-sm font-medium text-gray-600">Sel</div>
                                <div class="text-center py-2 text-sm font-medium text-gray-600">Rab</div>
                                <div class="text-center py-2 text-sm font-medium text-gray-600">Kam</div>
                                <div class="text-center py-2 text-sm font-medium text-gray-600">Jum</div>
                                <div class="text-center py-2 text-sm font-medium text-gray-600">Sab</div>
                            </div>
                            
                            <!-- Calendar Grid -->
                            <div class="grid grid-cols-7 bg-white rounded-b-lg" id="calendar-days"></div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Today's Schedule -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-4">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Jadwal Hari Ini</h2>
                            <div class="space-y-3 overflow-auto max-h-[300px] custom-scrollbar" id="schedule-list">
                                @if(isset($allSchedules[now()->format('Y-m-d')]))
                                    @foreach($allSchedules[now()->format('Y-m-d')] as $schedule)
                                        <div class="bg-white p-3 rounded-lg border border-gray-200 hover:border-blue-500 transition-colors">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="font-medium text-gray-800 text-sm">{{ $schedule['title'] }}</h4>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        <i class="far fa-clock mr-1 text-blue-600"></i>
                                                        {{ $schedule['start_time'] }} - {{ $schedule['end_time'] }}
                                                    </p>
                                                </div>
                                                <span class="px-2 py-0.5 text-xs rounded-full {{ $schedule['status'] === 'completed' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                                    {{ ucfirst($schedule['status']) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-6">
                                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                                            <i class="fas fa-calendar-day text-xl text-gray-400"></i>
                                        </div>
                                        <p class="text-gray-500 text-sm">Tidak ada jadwal hari ini</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-4">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h2>
                            <div class="space-y-2">
                                <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors flex items-center text-gray-700">
                                    <i class="fas fa-plus-circle mr-2 text-blue-600"></i>
                                    <span>Tambah Jadwal Baru</span>
                                </button>
                                <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors flex items-center text-gray-700">
                                    <i class="fas fa-sync-alt mr-2 text-green-600"></i>
                                    <span>Segarkan Kalender</span>
                                </button>
                                <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors flex items-center text-gray-700">
                                    <i class="fas fa-calendar-week mr-2 text-purple-600"></i>
                                    <span>Tampilkan Minggu Ini</span>
                                </button>
                                <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors flex items-center text-gray-700">
                                    <i class="fas fa-filter mr-2 text-yellow-600"></i>
                                    <span>Filter Jadwal</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Schedule Detail Modal -->
<div id="scheduleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4">
        <div class="p-4">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-lg font-semibold text-gray-800" id="modalTitle"></h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 p-1">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="space-y-4" id="modalContent"></div>
            <div class="mt-4 flex justify-end">
                <button onclick="closeModal()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/toggle.js') }}"></script>
<script>
let currentDate = new Date();
let schedules = @json($allSchedules ?? []);

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    
    // Update month display
    const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];
    document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;
    
    // Get first day of month and last day
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    
    let calendarHTML = '';
    
    // Add empty cells for days before first day of month
    for (let i = 0; i < firstDay.getDay(); i++) {
        calendarHTML += `
            <div class="min-h-[120px] p-3 border-b border-r border-gray-200 bg-gray-50"></div>
        `;
    }
    
    // Add days of month
    for (let day = 1; day <= lastDay.getDate(); day++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const hasSchedule = schedules && schedules[dateStr] !== undefined;
        const isToday = new Date().toDateString() === new Date(year, month, day).toDateString();
        const isWeekend = new Date(year, month, day).getDay() === 0 || new Date(year, month, day).getDay() === 6;
        
        calendarHTML += `
            <div class="relative group min-h-[120px] p-3 border-b border-r border-gray-200 ${
                isWeekend ? 'bg-gray-50' : 'bg-white'
            } hover:bg-blue-50/50 transition-all duration-300">
                <div class="flex justify-between items-center mb-2">
                    <span class="inline-flex items-center justify-center ${
                        isToday 
                        ? 'w-8 h-8 rounded-full bg-blue-600 text-white font-semibold shadow-lg' 
                        : 'text-gray-700'
                    } ${isWeekend ? 'text-gray-500' : ''}">${day}</span>
                    ${hasSchedule ? `
                        <span class="w-2 h-2 rounded-full ${isToday ? 'bg-white' : 'bg-blue-600'} animate-pulse shadow-lg"></span>
                    ` : ''}
                </div>
                
                ${hasSchedule ? `
                    <div class="space-y-1">
                        ${schedules[dateStr].slice(0, 2).map(schedule => `
                            <div class="text-xs p-2 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 truncate cursor-pointer hover:bg-blue-100 transition-all duration-300 transform hover:-translate-y-0.5">
                                <div class="font-medium">${schedule.title}</div>
                                <div class="text-blue-600 text-[10px]">${schedule.start_time}</div>
                            </div>
                        `).join('')}
                        ${schedules[dateStr].length > 2 ? `
                            <div class="text-xs text-center p-1 text-blue-600 font-medium">
                                +${schedules[dateStr].length - 2} lainnya
                            </div>
                        ` : ''}
                    </div>
                ` : ''}
            </div>
        `;
    }
    
    // Add empty cells for days after last day of month
    const remainingDays = 7 - ((firstDay.getDay() + lastDay.getDate()) % 7);
    if (remainingDays < 7) {
        for (let i = 0; i < remainingDays; i++) {
            calendarHTML += `
                <div class="min-h-[120px] p-3 border-b border-r border-gray-200 bg-gray-50"></div>
            `;
        }
    }
    
    document.getElementById('calendar-days').innerHTML = calendarHTML;
}

function previousMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
}

function nextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
}

function showSchedules(date) {
    fetch(`/admin/kalender/schedules/${date}`)
        .then(response => response.json())
        .then(data => {
            const scheduleList = document.getElementById('schedule-list');
            if (data.length === 0) {
                scheduleList.innerHTML = `
                    <div class="text-center py-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 mb-4">
                            <i class="fas fa-calendar-day text-2xl text-blue-600"></i>
                        </div>
                        <p class="text-gray-500">Tidak ada jadwal untuk tanggal ini</p>
                    </div>
                `;
                return;
            }
            
            scheduleList.innerHTML = data.map(schedule => `
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:border-blue-500 transition-colors">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-medium text-gray-800">${schedule.title}</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                <i class="far fa-clock mr-1 text-blue-600"></i>
                                ${schedule.start_time} - ${schedule.end_time}
                            </p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full ${schedule.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-600'}">
                            ${schedule.status.charAt(0).toUpperCase() + schedule.status.slice(1)}
                        </span>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">${schedule.description}</p>
                    ${schedule.location ? `
                        <p class="mt-2 text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt mr-1 text-blue-600"></i>
                            ${schedule.location}
                        </p>
                    ` : ''}
                    ${schedule.participants ? `
                        <p class="mt-2 text-sm text-gray-600">
                            <i class="fas fa-users mr-1 text-blue-600"></i>
                            ${schedule.participants.join(', ')}
                        </p>
                    ` : ''}
                    <div class="mt-3 pt-3 border-t border-gray-100 flex justify-end space-x-2">
                        <a href="/admin/kalender/${schedule.id}/edit" 
                           class="text-blue-600 hover:text-blue-700 p-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="deleteSchedule(${schedule.id})" 
                                class="text-red-600 hover:text-red-800 p-1">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `).join('');
        });
}

function showScheduleDetail(schedule) {
    const modal = document.getElementById('scheduleModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalContent = document.getElementById('modalContent');
    
    modalTitle.textContent = schedule.title;
    modalContent.innerHTML = `
        <div class="space-y-4">
            <div>
                <p class="text-sm text-gray-500">Waktu</p>
                <p class="text-gray-700">
                    <i class="far fa-clock mr-1 text-blue-600"></i>
                    ${schedule.start_time} - ${schedule.end_time}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Deskripsi</p>
                <p class="text-gray-700">${schedule.description}</p>
            </div>
            ${schedule.location ? `
                <div>
                    <p class="text-sm text-gray-500">Lokasi</p>
                    <p class="text-gray-700">
                        <i class="fas fa-map-marker-alt mr-1 text-blue-600"></i>
                        ${schedule.location}
                    </p>
                </div>
            ` : ''}
            ${schedule.participants ? `
                <div>
                    <p class="text-sm text-gray-500">Peserta</p>
                    <p class="text-gray-700">
                        <i class="fas fa-users mr-1 text-blue-600"></i>
                        ${schedule.participants.join(', ')}
                    </p>
                </div>
            ` : ''}
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                    schedule.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-600'
                }">
                    ${schedule.status.charAt(0).toUpperCase() + schedule.status.slice(1)}
                </span>
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    const modal = document.getElementById('scheduleModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function deleteSchedule(id) {
    if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
        fetch(`/admin/kalender/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => {
            location.reload();
        });
    }
}

function toggleDropdown() {
    const dropdown = document.getElementById('dropdown');
    dropdown.classList.toggle('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    renderCalendar();
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('dropdown');
        const dropdownToggle = document.getElementById('dropdownToggle');
        
        if (!dropdown.contains(event.target) && !dropdownToggle.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
    
    // Close modal when clicking outside
    document.getElementById('scheduleModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeModal();
        }
    });
});
</script>

<style>
.animate-fade-in-down {
    animation: fadeInDown 0.3s ease-out;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 2px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 2px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.calendar-day {
    min-height: 100px;
    transition: all 0.2s ease;
}

.calendar-day:hover {
    background-color: #f8fafc;
}

.calendar-event {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    margin-bottom: 0.25rem;
    border-radius: 0.25rem;
    background-color: #eff6ff;
    color: #2563eb;
    cursor: pointer;
    transition: all 0.2s ease;
}

.calendar-event:hover {
    background-color: #dbeafe;
    transform: translateY(-1px);
}

/* Additional Styles */
.welcome-card {
    background-size: cover;
    background-position: center;
    transition: all 0.3s ease;
}

.welcome-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to right,
        rgba(37, 99, 235, 0.9),
        rgba(59, 130, 246, 0.8)
    );
    border-radius: 0.5rem;
}

.calendar-day {
    min-height: 120px;
    transition: all 0.2s ease;
}

.calendar-day:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.calendar-event {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    margin-bottom: 0.25rem;
    border-radius: 0.25rem;
    background-color: #eff6ff;
    color: #2563eb;
    cursor: pointer;
    transition: all 0.2s ease;
}

.calendar-event:hover {
    background-color: #dbeafe;
    transform: translateY(-1px);
}

.popup-calendar {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@endsection 