<table class="min-w-full divide-y divide-gray-200 border">
    <thead>
        <tr>
            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-white z-10 border-r">
                Unit
            </th>
            @foreach($hours as $hour)
                <th class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                    {{ \Carbon\Carbon::parse($hour)->format('H:i') }}
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @foreach($powerPlants as $powerPlant)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white z-10 border-r">
                    {{ $powerPlant->name }}
                </td>
                @foreach($hours as $hour)
                    <td class="px-3 py-4 whitespace-nowrap text-center">
                        @if($powerPlant->hourlyStatus[$hour])
                            <span class="inline-flex items-center justify-center size-6 bg-green-100 text-green-800 rounded-full">
                                <i class="fas fa-check text-xs"></i>
                            </span>
                        @else
                            <a href="{{ route('admin.data-engine.edit', ['date' => $date, 'time' => $hour]) }}" 
                               class="inline-flex items-center justify-center size-6 bg-red-100 text-red-800 rounded-full hover:bg-red-200">
                                <i class="fas fa-times text-xs"></i>
                            </a>
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table> 