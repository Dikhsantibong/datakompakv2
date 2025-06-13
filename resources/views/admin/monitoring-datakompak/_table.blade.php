@if($data['type'] === 'data-engine')
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Data Engine - {{ \Carbon\Carbon::parse($data['date'])->isoFormat('D MMMM Y') }}</h3>
    </div>
    <table class="min-w-full divide-y divide-gray-200 border">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Unit</th>
                @foreach($data['hours'] as $hour)
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        {{ \Carbon\Carbon::parse($hour)->format('H:i') }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($data['powerPlants'] as $powerPlant)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r">
                        {{ $powerPlant->name }}
                    </td>
                    @foreach($data['hours'] as $hour)
                        <td class="px-6 py-4 whitespace-nowrap text-center border-r">
                            @if($powerPlant->hourlyStatus[$hour])
                                <span class="inline-flex items-center justify-center size-6 bg-green-100 text-green-800 rounded-full">
                                    <i class="fas fa-check text-xs"></i>
                                </span>
                            @else
                                <span class="inline-flex items-center justify-center size-6 bg-red-100 text-red-800 rounded-full">
                                    <i class="fas fa-times text-xs"></i>
                                </span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
@elseif($data['type'] === 'daily-summary')
    <table class="min-w-full divide-y divide-gray-200 border">
        <thead>
            <tr>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-white z-10 border-r">
                    Unit
                </th>
                @foreach($data['dates'] as $date)
                    <th class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap border-r">
                        {{ \Carbon\Carbon::parse($date)->format('d/m') }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($data['powerPlants'] as $powerPlant)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white z-10 border-r">
                        {{ $powerPlant->name }}
                    </td>
                    @foreach($data['dates'] as $date)
                        <td class="px-3 py-4 whitespace-nowrap text-center border-r">
                            @if($powerPlant->dailyStatus[$date])
                                <span class="inline-flex items-center justify-center size-6 bg-green-100 text-green-800 rounded-full">
                                    <i class="fas fa-check text-xs"></i>
                                </span>
                            @else
                                <span class="inline-flex items-center justify-center size-6 bg-red-100 text-red-800 rounded-full">
                                    <i class="fas fa-times text-xs"></i>
                                </span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <table class="min-w-full divide-y divide-gray-200 border">
        <thead>
            <tr>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-white z-10 border-r">
                    Unit
                </th>
                @foreach($data['dates'] as $date)
                    <th class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap border-r" colspan="4">
                        {{ \Carbon\Carbon::parse($date)->format('d/m') }}
                    </th>
                @endforeach
            </tr>
            <tr>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-white z-10 border-r"></th>
                @foreach($data['dates'] as $date)
                    @foreach($data['shifts'] as $shift)
                        <th class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap border-r">
                            {{ $shift }}
                        </th>
                    @endforeach
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($data['powerPlants'] as $powerPlant)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white z-10 border-r">
                        {{ $powerPlant->name }}
                    </td>
                    @foreach($data['dates'] as $date)
                        @foreach($data['shifts'] as $shift)
                            <td class="px-3 py-4 whitespace-nowrap text-center border-r">
                                @if($powerPlant->shiftStatus[$date . '_' . $shift])
                                    <span class="inline-flex items-center justify-center size-6 bg-green-100 text-green-800 rounded-full">
                                        <i class="fas fa-check text-xs"></i>
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center size-6 bg-red-100 text-red-800 rounded-full">
                                        <i class="fas fa-times text-xs"></i>
                                    </span>
                                @endif
                            </td>
                        @endforeach
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
@endif 