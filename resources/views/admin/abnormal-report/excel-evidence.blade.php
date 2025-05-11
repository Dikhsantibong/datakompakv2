<table>
    <!-- Header -->
    <tr class="main-header">
        <td colspan="4">Daftar Evidence - {{ $report->created_at->format('d F Y') }}</td>
    </tr>

    <!-- Spacing row -->
    <tr><td colspan="4"></td></tr>

    <!-- Table Headers -->
    <tr class="table-header">
        <th>No</th>
        <th>File</th>
        <th>Link Download</th>
        <th>Deskripsi</th>
    </tr>

    <!-- Evidence Data -->
    @foreach($report->evidences as $index => $evidence)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ basename($evidence->file_path) }}</td>
        <td>=HYPERLINK("{{ asset('storage/' . $evidence->file_path) }}", "Download")</td>
        <td>{{ $evidence->description ?? '-' }}</td>
    </tr>
    @endforeach

    <!-- Footer -->
    <tr><td colspan="4"></td></tr>
    <tr class="footer">
        <td colspan="4">Diekspor pada: {{ now()->format('d/m/Y H:i') }}</td>
    </tr>
</table> 