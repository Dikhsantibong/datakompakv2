<table>
    <!-- Header -->
    <tr class="main-header">
        <td colspan="13">Form Pemeriksaan 5S5R - {{ $pemeriksaan->first()->created_at->format('d F Y') }}</td>
    </tr>
    
    <!-- Logo spacing row -->
    <tr>
        <td colspan="6"></td>
        <td></td>
        <td colspan="6"></td>
    </tr>
    <tr><td colspan="13"></td></tr>

    <!-- Pemeriksaan 5S5R Section -->
    <tr class="section-header">
        <td colspan="13">Tabel Pemeriksaan 5S5R</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Uraian</th>
        <th>Detail</th>
        <th>Kondisi Awal</th>
        <th>PIC</th>
        <th>Area Kerja</th>
        <th>Area Produksi</th>
        <th>Membersihkan</th>
        <th>Merapikan</th>
        <th>Membuang Sampah</th>
        <th>Mengecat</th>
        <th>Lainnya</th>
        <th>Kondisi Akhir</th>
    </tr>
    @foreach($pemeriksaan as $index => $item)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $item->kategori }}</td>
        <td>{{ $item->detail }}</td>
        <td>{{ $item->kondisi_awal }}</td>
        <td>{{ $item->pic }}</td>
        <td>{{ $item->area_kerja }}</td>
        <td>{{ $item->area_produksi }}</td>
        <td>{{ $item->membersihkan ? '✓' : '-' }}</td>
        <td>{{ $item->merapikan ? '✓' : '-' }}</td>
        <td>{{ $item->membuang_sampah ? '✓' : '-' }}</td>
        <td>{{ $item->mengecat ? '✓' : '-' }}</td>
        <td>{{ $item->lainnya ? '✓' : '-' }}</td>
        <td>{{ $item->kondisi_akhir }}</td>
    </tr>
    @endforeach
    <tr><td colspan="13"></td></tr>

    <!-- Program Kerja 5R Section -->
    <tr class="section-header">
        <td colspan="13">Tabel Program Kerja 5R</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Program Kerja 5R</th>
        <th>Goal</th>
        <th>Kondisi Awal</th>
        <th colspan="4">Progress</th>
        <th colspan="3">Kondisi Akhir</th>
        <th colspan="2">Catatan</th>
    </tr>
    @foreach($programKerja as $index => $item)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $item->program_kerja }}</td>
        <td>{{ $item->goal }}</td>
        <td>{{ $item->kondisi_awal }}</td>
        <td colspan="4">{{ $item->progress }}</td>
        <td colspan="3">{{ $item->kondisi_akhir }}</td>
        <td colspan="2">{{ $item->catatan ?? '-' }}</td>
    </tr>
    @endforeach
    <tr><td colspan="13"></td></tr>

    <!-- Footer -->
    <tr><td colspan="13"></td></tr>
    <tr class="footer">
        <td colspan="13">Dibuat oleh: {{ auth()->user()->name ?? 'System' }} | Tanggal: {{ now()->format('d/m/Y H:i') }}</td>
    </tr>
</table> 