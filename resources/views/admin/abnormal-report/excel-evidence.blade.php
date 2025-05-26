<!-- Header Logo Row -->
<table>
    <tr>
        <td colspan="4" height="50" style="border: none;">
            <!-- Logos will be placed by the Drawings class -->
        </td>
    </tr>

    <!-- Title Row -->
    <tr>
        <td colspan="4" style="text-align: center; font-size: 14px; font-weight: bold; border: none;">
            DAFTAR EVIDENCE - {{ strtoupper($report->created_at->isoFormat('D MMMM Y')) }}
        </td>
    </tr>

    <!-- Spacing row -->
    <tr>
        <td colspan="4" height="20" style="border: none;"></td>
    </tr>

    <!-- Table Headers -->
    <tr>
        <th style="width: 40px; background-color: #E2E8F0; text-align: center; font-weight: bold;">NO</th>
        <th style="width: 200px; background-color: #E2E8F0; text-align: center; font-weight: bold;">FILE</th>
        <th style="width: 100px; background-color: #E2E8F0; text-align: center; font-weight: bold;">DOWNLOAD</th>
        <th style="width: 300px; background-color: #E2E8F0; text-align: center; font-weight: bold;">DESKRIPSI</th>
    </tr>

    <!-- Evidence Data -->
    @foreach($report->evidences as $index => $evidence)
    <tr>
        <td style="text-align: center;">{{ $index + 1 }}</td>
        <td>{{ basename($evidence->file_path) }}</td>
        <td style="text-align: center;">=HYPERLINK("{{ asset('storage/' . $evidence->file_path) }}", "Download")</td>
        <td>{{ $evidence->description ?? '-' }}</td>
    </tr>
    @endforeach

    <!-- Spacing before footer -->
    <tr>
        <td colspan="4" height="20" style="border: none;"></td>
    </tr>

    <!-- Footer -->
    <tr>
        <td colspan="4" style="font-style: italic; color: #666666; border: none;">
            Diekspor pada: {{ now()->format('d/m/Y H:i') }}
        </td>
    </tr>
</table> 