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
        <main class="p-6">
            <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('admin.kalender.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        <span>Tambah Jadwal</span>
                    </a>
                    <div class="relative">
                        <button id="filterButton" 
                                class="inline-flex items-center px-4 py-2 bg-white text-gray-700 rounded-lg border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm transition-colors">
                            <i class="fas fa-filter mr-2 text-blue-600"></i>
                            <span>Filter</span>
                        </button>
                        <div id="filterDropdown" class="hidden absolute left-0 mt-2 w-64 bg-white rounded-lg shadow-lg z-10 p-4 border border-gray-100">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Filter Jadwal</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Status</label>
                                    <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500/20">
                                        <option value="">Semua Status</option>
                                        <option value="scheduled">Terjadwal</option>
                                        <option value="completed">Selesai</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Periode</label>
                                    <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500/20">
                                        <option value="week">Minggu Ini</option>
                                        <option value="month">Bulan Ini</option>
                                        <option value="custom">Kustom</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end space-x-2">
                                <button class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-800">Reset</button>
                                <button class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                    Terapkan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Calendar View -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-semibold text-gray-800">Kalender</h2>
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

                <!-- Schedule List -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-4">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Jadwal Hari Ini</h2>
                            <div class="space-y-3 overflow-auto max-h-[calc(100vh-16rem)] custom-scrollbar" id="schedule-list">
                                @forelse($allSchedules as $date => $daySchedules)
                                    <div class="border-b border-gray-100 pb-3 last:border-b-0">
                                        <h3 class="font-medium text-gray-700 mb-2 bg-gray-50 px-3 py-1.5 rounded-md text-sm">
                                            {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
                                        </h3>
                                        <div class="space-y-2">
                                            @foreach($daySchedules as $schedule)
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
                                                    @if($schedule['location'])
                                                        <p class="mt-2 text-xs text-gray-600">
                                                            <i class="fas fa-map-marker-alt mr-1 text-blue-600"></i>
                                                            {{ $schedule['location'] }}
                                                        </p>
                                                    @endif
                                                    <div class="mt-2 pt-2 border-t border-gray-100 flex justify-end space-x-2">
                                                        <a href="{{ route('admin.kalender.edit', $schedule['id']) }}" 
                                                           class="text-blue-600 hover:text-blue-700 p-1 hover:bg-blue-50 rounded transition-colors">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('admin.kalender.destroy', $schedule['id']) }}" 
                                                              method="POST" 
                                                              class="inline"
                                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-700 p-1 hover:bg-red-50 rounded transition-colors">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6">
                                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                                            <i class="fas fa-calendar-day text-xl text-gray-400"></i>
                                        </div>
                                        <p class="text-gray-500 text-sm">Tidak ada jadwal hari ini</p>
                                    </div>
                                @endforelse
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
let schedules = @json($allSchedules);

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
        const hasSchedule = schedules[dateStr] !== undefined;
        const isToday = new Date().toDateString() === new Date(year, month, day).toDateString();
        const isWeekend = new Date(year, month, day).getDay() === 0 || new Date(year, month, day).getDay() === 6;
        
        calendarHTML += `
            <div class="relative group min-h-[120px] p-3 border-b border-r border-gray-200 ${
                isWeekend ? 'bg-gray-50' : 'bg-white'
            } hover:bg-[#009BB9]/5 transition-all duration-300">
                <div class="flex justify-between items-center mb-2">
                    <span class="inline-flex items-center justify-center ${
                        isToday 
                        ? 'w-8 h-8 rounded-full bg-gradient-to-r from-[#009BB9] to-[#0A749B] text-white font-semibold shadow-lg' 
                        : 'text-gray-700'
                    } ${isWeekend ? 'text-gray-500' : ''}">${day}</span>
                    ${hasSchedule ? `
                        <span class="w-2 h-2 rounded-full ${isToday ? 'bg-white' : 'bg-[#009BB9]'} animate-pulse shadow-lg"></span>
                    ` : ''}
                </div>
                
                ${hasSchedule ? `
                    <div class="space-y-1">
                        ${schedules[dateStr].slice(0, 2).map(schedule => `
                            <div class="text-xs p-2 rounded-lg bg-gradient-to-r from-[#009BB9]/10 to-[#009BB9]/20 border border-[#009BB9]/20 text-[#009BB9] truncate cursor-pointer hover:from-[#009BB9]/20 hover:to-[#009BB9]/30 transition-all duration-300 transform hover:-translate-y-0.5">
                                <div class="font-medium">${schedule.title}</div>
                                <div class="text-[#009BB9] text-[10px]">${schedule.start_time}</div>
                            </div>
                        `).join('')}
                        ${schedules[dateStr].length > 2 ? `
                            <div class="text-xs text-center p-1 text-[#009BB9] font-medium">
                                +${schedules[dateStr].length - 2} lainnya
                            </div>
                        ` : ''}
                    </div>
                    
                    <!-- Hover Popup dengan posisi dinamis -->
                    <div class="hidden group-hover:block absolute z-50 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 p-4 transform transition-all duration-300 ease-in-out hover:shadow-2xl popup-calendar"
                         style="
                            ${((firstDay.getDay() + day - 1) % 7 >= 5) ? 'right: calc(100% + 1rem); left: auto;' : 'left: calc(100% + 1rem);'}
                            top: 0;
                         ">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="font-semibold text-gray-800 text-lg">
                                    ${day} ${monthNames[month]} ${year}
                                </h4>
                                <p class="text-sm text-gray-500 mt-1">
                                    ${schedules[dateStr].length} Jadwal Kegiatan
                                </p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-[#009BB9] to-[#0A749B] text-white shadow-lg">
                                ${schedules[dateStr].length} Jadwal
                            </span>
                        </div>
                        <div class="space-y-3 max-h-[300px] overflow-y-auto custom-scrollbar">
                            ${schedules[dateStr].map(schedule => `
                                <div class="p-3 rounded-xl bg-gradient-to-r from-gray-50 to-gray-100 hover:from-[#009BB9]/10 hover:to-[#009BB9]/20 transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-md">
                                    <div class="flex justify-between items-start mb-2">
                                        <h5 class="font-medium text-gray-800">${schedule.title}</h5>
                                        <span class="px-2 py-1 text-xs rounded-full ${
                                            schedule.status === 'completed' 
                                            ? 'bg-green-100 text-green-800' 
                                            : 'bg-[#009BB9]/10 text-[#009BB9]'
                                        }">
                                            ${schedule.status.charAt(0).toUpperCase() + schedule.status.slice(1)}
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-600 mb-2">
                                        <i class="far fa-clock mr-1 text-[#009BB9]"></i>
                                        ${schedule.start_time} - ${schedule.end_time}
                                    </div>
                                    ${schedule.location ? `
                                        <div class="text-sm text-gray-600 mb-1">
                                            <i class="fas fa-map-marker-alt mr-1 text-[#009BB9]"></i>
                                            ${schedule.location}
                                        </div>
                                    ` : ''}
                                    ${schedule.participants ? `
                                        <div class="text-sm text-gray-600">
                                            <i class="fas fa-users mr-1 text-[#009BB9]"></i>
                                            ${schedule.participants.join(', ')}
                                        </div>
                                    ` : ''}
                                    <div class="mt-3 pt-3 border-t border-gray-200 flex justify-end space-x-2">
                                        <a href="/admin/kalender/${schedule.id}/edit" 
                                           class="text-[#009BB9] hover:text-[#0A749B] p-1.5 hover:bg-[#009BB9]/10 rounded-lg transition-all duration-300">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="deleteSchedule(${schedule.id})" 
                                                class="text-red-600 hover:text-red-800 p-1.5 hover:bg-red-100 rounded-lg transition-all duration-300">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
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
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#009BB9]/10 mb-4">
                            <i class="fas fa-calendar-day text-2xl text-[#009BB9]"></i>
                        </div>
                        <p class="text-gray-500">Tidak ada jadwal untuk tanggal ini</p>
                    </div>
                `;
                return;
            }
            
            scheduleList.innerHTML = data.map(schedule => `
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:border-[#009BB9] transition-colors">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-medium text-gray-800">${schedule.title}</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                <i class="far fa-clock mr-1 text-[#009BB9]"></i>
                                ${schedule.start_time} - ${schedule.end_time}
                            </p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full ${schedule.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-[#009BB9]/10 text-[#009BB9]'}">
                            ${schedule.status.charAt(0).toUpperCase() + schedule.status.slice(1)}
                        </span>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">${schedule.description}</p>
                    ${schedule.location ? `
                        <p class="mt-2 text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt mr-1 text-[#009BB9]"></i>
                            ${schedule.location}
                        </p>
                    ` : ''}
                    ${schedule.participants ? `
                        <p class="mt-2 text-sm text-gray-600">
                            <i class="fas fa-users mr-1 text-[#009BB9]"></i>
                            ${schedule.participants.join(', ')}
                        </p>
                    ` : ''}
                    <div class="mt-3 pt-3 border-t border-gray-100 flex justify-end space-x-2">
                        <a href="/admin/kalender/${schedule.id}/edit" 
                           class="text-[#009BB9] hover:text-[#0A749B] p-1">
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
                    <i class="far fa-clock mr-1 text-[#009BB9]"></i>
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
                        <i class="fas fa-map-marker-alt mr-1 text-[#009BB9]"></i>
                        ${schedule.location}
                    </p>
                </div>
            ` : ''}
            ${schedule.participants ? `
                <div>
                    <p class="text-sm text-gray-500">Peserta</p>
                    <p class="text-gray-700">
                        <i class="fas fa-users mr-1 text-[#009BB9]"></i>
                        ${schedule.participants.join(', ')}
                    </p>
                </div>
            ` : ''}
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                    schedule.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-[#009BB9]/10 text-[#009BB9]'
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
</style>
@endpush

@endsection 