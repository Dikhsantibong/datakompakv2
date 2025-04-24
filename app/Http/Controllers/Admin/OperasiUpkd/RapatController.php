<?php

namespace App\Http\Controllers\Admin\OperasiUpkd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RapatController extends Controller
{
    public function index()
    {
        // Data sections
        $pekerjaan_tentatif = [
            ['no' => 1, 'uraian' => '', 'detail' => '', 'pic' => '', 'kondisi_eksisting' => '', 'tindaklanjut' => '', 'kondisi_akhir' => '', 'goal' => '', 'status' => '', 'keterangan' => ''],
            ['no' => 2, 'uraian' => '', 'detail' => '', 'pic' => '', 'kondisi_eksisting' => '', 'tindaklanjut' => '', 'kondisi_akhir' => '', 'goal' => '', 'status' => '', 'keterangan' => ''],
            ['no' => 3, 'uraian' => '', 'detail' => '', 'pic' => '', 'kondisi_eksisting' => '', 'tindaklanjut' => '', 'kondisi_akhir' => '', 'goal' => '', 'status' => '', 'keterangan' => '']
        ];

        $operation_management = [
            ['no' => 1, 'uraian' => '', 'detail' => '', 'pic' => '', 'kondisi_eksisting' => '', 'tindaklanjut' => '', 'kondisi_akhir' => '', 'goal' => '', 'status' => '', 'keterangan' => ''],
            ['no' => 2, 'uraian' => '', 'detail' => '', 'pic' => '', 'kondisi_eksisting' => '', 'tindaklanjut' => '', 'kondisi_akhir' => '', 'goal' => '', 'status' => '', 'keterangan' => '']
        ];

        $efisiensi_management = [
            ['no' => 1, 'uraian' => '', 'detail' => '', 'pic' => '', 'kondisi_eksisting' => '', 'tindaklanjut' => '', 'kondisi_akhir' => '', 'goal' => '', 'status' => '', 'keterangan' => ''],
            ['no' => 2, 'uraian' => '', 'detail' => '', 'pic' => '', 'kondisi_eksisting' => '', 'tindaklanjut' => '', 'kondisi_akhir' => '', 'goal' => '', 'status' => '', 'keterangan' => '']
        ];

        $program_kerja = [
            ['no' => 1, 'uraian' => '', 'detail' => '', 'pic' => '', 'kondisi_eksisting' => '', 'tindaklanjut' => '', 'kondisi_akhir' => '', 'goal' => '', 'status' => '', 'keterangan' => ''],
            ['no' => 2, 'uraian' => '', 'detail' => '', 'pic' => '', 'kondisi_eksisting' => '', 'tindaklanjut' => '', 'kondisi_akhir' => '', 'goal' => '', 'status' => '', 'keterangan' => '']
        ];

        $monitoring_aplikasi = [
            ['aplikasi' => 'DATA KOMPAK', 'uraian' => '', 'detail' => '', 'pic' => '', 'kondisi_eksisting' => '', 'tindaklanjut' => '', 'kondisi_akhir' => '', 'goal' => '', 'status' => '', 'keterangan' => ''],
            ['aplikasi' => 'NAVITAS', 'uraian' => '', 'detail' => '', 'pic' => '', 'kondisi_eksisting' => '', 'tindaklanjut' => '', 'kondisi_akhir' => '', 'goal' => '', 'status' => '', 'keterangan' => ''],
            ['aplikasi' => 'OMAMO', 'uraian' => '', 'detail' => '', 'pic' => '', 'kondisi_eksisting' => '', 'tindaklanjut' => '', 'kondisi_akhir' => '', 'goal' => '', 'status' => '', 'keterangan' => '']
        ];

        $pengawasan_kontrak = [
            ['item' => 'PP KIT & OM KIT', 'uraian' => '', 'detail' => '', 'pic' => '', 'kondisi_eksisting' => '', 'tindaklanjut' => '', 'kondisi_akhir' => '', 'goal' => '', 'status' => '', 'keterangan' => '']
        ];

        $laporan_pembangkit = [
            ['uraian' => '', 'detail' => '', 'pic' => '', 'kondisi_eksisting' => '', 'tindaklanjut' => '', 'kondisi_akhir' => '', 'goal' => '', 'status' => '', 'keterangan' => '']
        ];

        $laporan_transaksi = [
            ['uraian' => '', 'detail' => '', 'pic' => '', 'kondisi_eksisting' => '', 'tindaklanjut' => '', 'kondisi_akhir' => '', 'goal' => '', 'status' => '', 'keterangan' => '']
        ];

        // Rapat data
        $rapat_data = [
            'internal_ron' => ['uraian' => '', 'jadwal' => '', 'online_offline' => '', 'resume' => '', 'notulen' => '', 'eviden' => ''],
            'internal_upkd' => ['uraian' => '', 'jadwal' => '', 'online_offline' => '', 'resume' => '', 'notulen' => '', 'eviden' => ''],
            'eksternal_np1' => ['uraian' => '', 'jadwal' => '', 'online_offline' => '', 'resume' => '', 'notulen' => '', 'eviden' => ''],
            'eksternal_np2' => ['uraian' => '', 'jadwal' => '', 'online_offline' => '', 'resume' => '', 'notulen' => '', 'eviden' => '']
        ];

        // Link monitoring data
        $link_monitoring = [
            ['no' => 1, 'uraian' => '', 'detail' => '', 'pic' => '', 'kondisi_eksisting' => '', 'tindaklanjut' => ''],
            ['no' => 2, 'uraian' => '', 'detail' => '', 'pic' => '', 'kondisi_eksisting' => '', 'tindaklanjut' => '']
        ];

        return view('admin.operasi-upkd.rapat.index', compact(
            'pekerjaan_tentatif',
            'operation_management',
            'efisiensi_management',
            'program_kerja',
            'monitoring_aplikasi',
            'pengawasan_kontrak',
            'laporan_pembangkit',
            'laporan_transaksi',
            'rapat_data',
            'link_monitoring'
        ));
    }
} 