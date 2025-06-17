@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50 overflow-auto">
    @include('components.sidebar')



    <div class="flex-1 overflow-auto">
         <!-- Header -->
         <header class="bg-white shadow-sm sticky top-0 z-20
         ">
             <div class="flex justify-between items-center px-6 py-3">
                 <div class="flex items-center gap-x-3">
                     <!-- Mobile Menu Toggle -->
                     <button id="mobile-menu-toggle"
                         class="md:hidden relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-[#009BB9] hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                         aria-controls="mobile-menu" aria-expanded="false">
                         <span class="sr-only">Open main menu</span>
                         <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                         </svg>
                     </button>

                     <button id="desktop-menu-toggle"
                         class="hidden md:block relative items-center justify-center rounded-md text-gray-400 hover:bg-[#009BB9] p-2 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                         <span class="sr-only">Open main menu</span>
                         <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                         </svg>
                     </button>

                     <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
                 </div>

                 <div class="relative">
                     <button id="dropdownToggle" class="flex items-center" onclick="toggleDropdown()">
                         <img src="{{ Auth::user()->avatar ?? asset('foto_profile/admin1.png') }}" class="w-7 h-7 rounded-full mr-2">
                         <span class="text-gray-700 text-sm">{{ Auth::user()->name }}</span>
                         <i class="fas fa-caret-down ml-2 text-gray-600"></i>
                     </button>
                     <div id="dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-10">
                         <a href="{{ route('logout') }}"
                            class="block px-4 py-2 text-gray-800 hover:bg-gray-200"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                             Logout
                         </a>
                         <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                             @csrf
                             <input type="hidden" name="redirect" value="{{ route('homepage') }}">
                         </form>
                     </div>
                 </div>
             </div>
         </header>
        <div class="p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Data KIT UP Kendari</h1>
                <div class="text-sm text-gray-500">Monitoring data pembangkit per tanggal</div>
            </div>

            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th rowspan="3" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10 border-r">
                                PEMBANGKIT IPP
                            </th>
                            @foreach($dates as $date)
                                <th colspan="3" class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[200px] border">
                                    {{ str_pad($date, 2, '0', STR_PAD_LEFT) }}
                                </th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach($dates as $date)
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">DMP</th>
                                <th colspan="2" class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">BEBAN</th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach($dates as $date)
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 tracking-wider border"></th>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 tracking-wider border">11:00</th>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 tracking-wider border">19:00</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($powerPlants as $system => $plants)
                            <!-- System Header -->
                            <tr class="bg-gray-50">
                                <td colspan="{{ count($dates) * 3 + 1 }}" class="px-6 py-2 text-sm font-medium text-gray-900">
                                    {{ $system }}
                                </td>
                            </tr>
                            <!-- Power Plants -->
                            @foreach($plants as $plant)
                                <tr>
                                    <td class="px-6 py-2 text-sm text-gray-900 sticky left-0 bg-white z-10 border-r">
                                        {{ $plant }}
                                    </td>
                                    @foreach($dates as $date)
                                        <td class="px-4 py-2 text-sm text-center border">
                                            <!-- DMP cell -->
                                        </td>
                                        <td class="px-4 py-2 text-sm text-center border">
                                            <!-- Beban 11:00 cell -->
                                        </td>
                                        <td class="px-4 py-2 text-sm text-center border">
                                            <!-- Beban 19:00 cell -->
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            <!-- Total Row for each system -->
                            <tr class="bg-gray-100">
                                <td class="px-6 py-2 text-sm font-medium text-gray-900 sticky left-0 bg-gray-100 z-10 border-r">
                                    TOTAL
                                </td>
                                @foreach($dates as $date)
                                    <td class="px-4 py-2 text-sm text-center font-medium border">
                                        -
                                    </td>
                                    <td class="px-4 py-2 text-sm text-center font-medium border">
                                        -
                                    </td>
                                    <td class="px-4 py-2 text-sm text-center font-medium border">
                                        -
                                    </td>
                                @endforeach
                            </tr>
                            <!-- Spacer row -->
                            <tr class="h-2"></tr>
                        @endforeach
                        <!-- Final Total Row -->
                        <tr class="bg-gray-200">
                            <td class="px-6 py-2 text-sm font-medium text-gray-900 sticky left-0 bg-gray-200 z-10 border-r">
                                TOTAL KESELURUHAN
                            </td>
                            @foreach($dates as $date)
                                <td class="px-4 py-2 text-sm text-center font-medium border">
                                    -
                                </td>
                                <td class="px-4 py-2 text-sm text-center font-medium border">
                                    -
                                </td>
                                <td class="px-4 py-2 text-sm text-center font-medium border">
                                    -
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="{{ asset('js/toggle.js') }}"></script>

@push('styles')
<style>
    /* Custom scrollbar styles */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endpush