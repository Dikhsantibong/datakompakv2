<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminMeetingController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\MachineMonitorController;
use App\Http\Controllers\Admin\UserMachineMonitorController;
use App\Http\Controllers\Admin\PembangkitController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\DaftarHadirController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\MeetingController;
use App\Http\Controllers\ScoreCardDailyController;
use App\Http\Controllers\WoBacklogController;
use App\Http\Controllers\DashboardPemantauanController;
use App\Http\Controllers\Admin\PowerPlantController;
use App\Http\Controllers\Admin\OtherDiscussionController;
use App\Http\Controllers\Admin\OverdueDiscussionController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\Admin\LaporanDeleteController;
use App\Http\Controllers\Admin\MachineStatusViewController;
use App\Http\Controllers\Admin\MachineStatusController;
use App\Http\Controllers\Admin\OtherDiscussionEditController;
use App\Http\Controllers\Admin\PasswordVerificationController;
use App\Http\Controllers\DailySummaryController;
use App\Http\Controllers\Admin\MonitorKinerjaController;
use App\Http\Controllers\Admin\RencanaDayaMampuController;
use App\Http\Controllers\Admin\AdministrasiOperasiController;
use App\Http\Controllers\Admin\LibraryController;
use App\Http\Controllers\BahanBakarController;
use App\Http\Controllers\PelumasController;
use App\Http\Controllers\BahanKimiaController;
use Illuminate\Http\Request;
use App\Models\BahanBakar;
use App\Models\Pelumas;
use App\Models\BahanKimia;
use App\Http\Controllers\Admin\DataEngineController;
use App\Http\Controllers\Admin\K3KampController;
use App\Http\Controllers\Admin\MeetingShiftController;
use App\Http\Controllers\FlmController;

Route::get('/', function () {
    return view('auth.login', [
        'selectedUnit' => session('selected_unit', 'mysql') // default ke mysql jika belum ada session
    ]);
})->name('homepage');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/profile', [UserController::class, 'showProfile'])->name('user.profile');
Route::post('/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');

Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/daily-meeting', [UserController::class, 'dailyMeeting'])->name('daily.meeting');
    Route::get('/monitoring', [UserController::class, 'monitoring'])->name('monitoring');
    Route::get('/documentation', [UserController::class, 'documentation'])->name('documentation');
    Route::get('/support', [UserController::class, 'support'])->name('support');
    Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/user/machine-monitor', [UserController::class, 'machineMonitor'])->name('user.machine.monitor');
    Route::get('/attendance/check', [AttendanceController::class, 'check'])->name('attendance.check');
    Route::get('/attendance/record', [AttendanceController::class, 'record'])->name('attendance.record');
    Route::get('/attendance/scan/{token}', [AttendanceController::class, 'showScanForm'])->name('attendance.scan');
    Route::post('/attendance/submit', [AttendanceController::class, 'submitAttendance'])->name('attendance.submit');
});

Route::prefix('attendance')->group(function () {
    Route::get('/scan/{token}', [AttendanceController::class, 'showScanForm'])
        ->name('attendance.scan-form')
        ->withoutMiddleware(['auth'])
        ->where('token', '.*');
    
    Route::post('/submit', [AttendanceController::class, 'submitAttendance'])
        ->name('attendance.submit')
        ->withoutMiddleware(['auth']);
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    
    Route::get('/dashboard/refresh', [DashboardController::class, 'refresh'])->name('dashboard.refresh');

    // Add monitor-kinerja route
    Route::get('/monitor-kinerja', [MonitorKinerjaController::class, 'index'])->name('monitor-kinerja');

    // Add rencana-daya-mampu route (temporary view only)
    Route::prefix('rencana-daya-mampu')->name('rencana-daya-mampu.')->group(function () {
        Route::get('/', [RencanaDayaMampuController::class, 'index'])->name('index');
        Route::get('/manage', [RencanaDayaMampuController::class, 'manage'])->name('manage');
        Route::get('/export', [RencanaDayaMampuController::class, 'export'])->name('export');
        Route::post('/update', [RencanaDayaMampuController::class, 'update'])->name('update');
        Route::get('/get-status', [RencanaDayaMampuController::class, 'getStatus'])->name('get-status');
        Route::post('/save-status', [RencanaDayaMampuController::class, 'saveStatus'])->name('save-status');
    });

    Route::prefix('machine-monitor')->group(function () {
        Route::get('/', [MachineMonitorController::class, 'index'])->name('machine-monitor');
        Route::get('/create', [MachineMonitorController::class, 'create'])->name('machine-monitor.create');
        Route::post('/store', [MachineMonitorController::class, 'store'])->name('machine-monitor.store');
        Route::get('/show', [MachineMonitorController::class, 'show'])->name('machine-monitor.show');
        Route::get('/{id}/edit', [MachineMonitorController::class, 'edit'])->name('machine-monitor.edit');
        Route::put('/{id}', [MachineMonitorController::class, 'update'])->name('machine-monitor.update');
        Route::delete('/{id}', [MachineMonitorController::class, 'destroy'])->name('machine-monitor.destroy');
    });

    Route::prefix('pembangkit')->group(function () {
        Route::get('/ready', [PembangkitController::class, 'ready'])->name('pembangkit.ready');
        Route::post('/save-status', [PembangkitController::class, 'saveStatus'])->name('pembangkit.save-status');
        Route::get('/get-status', [PembangkitController::class, 'getStatus'])->name('pembangkit.get-status');
        Route::get('/status-history', [PembangkitController::class, 'getStatusHistory'])->name('pembangkit.status-history');
        Route::get('/report', [PembangkitController::class, 'report'])->name('pembangkit.report');
        Route::get('/report/download', [PembangkitController::class, 'downloadReport'])->name('pembangkit.report.download');
        Route::get('/report/print', [PembangkitController::class, 'printReport'])->name('pembangkit.report.print');
        Route::post('/upload-image', [PembangkitController::class, 'uploadImage'])->name('admin.pembangkit.upload-image');
        Route::delete('/delete-image', [PembangkitController::class, 'deleteImage'])->name('admin.pembangkit.delete-image');
        Route::get('/get-images/{machineId}', [PembangkitController::class, 'getImages'])->name('admin.pembangkit.get-images');
    });

    Route::prefix('laporan')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/manage', [LaporanController::class, 'manage'])->name('laporan.manage');
        Route::get('/sr_wo', [LaporanController::class, 'srWo'])->name('laporan.sr_wo');
        Route::get('/sr_wo/closed', [LaporanController::class, 'srWoClosed'])->name('laporan.sr_wo_closed');
        Route::get('/sr_wo/closed/download', [LaporanController::class, 'downloadSrWoClosed'])->name('laporan.sr_wo.closed.download');
        Route::get('/sr_wo/closed/print', [LaporanController::class, 'printSrWoClosed'])->name('laporan.sr_wo.closed.print');
        Route::post('/store-sr', [LaporanController::class, 'storeSR'])->name('laporan.store-sr');
        Route::post('/store-wo', [LaporanController::class, 'storeWO'])->name('laporan.store-wo');
        

    });
   

    Route::prefix('daftar-hadir')->name('daftar_hadir.')->group(function () {
        Route::get('/', [DaftarHadirController::class, 'index'])->name('index');
        Route::post('/store-token', [DaftarHadirController::class, 'storeToken'])->name('store-token');
        Route::post('/admin/daftar-hadir/store-token', [DaftarHadirController::class, 'storeToken'])->name('admin.daftar_hadir.store_token');
        Route::get('/print', [AttendanceController::class, 'printView'])
            ->name('admin.daftar_hadir.print');
    });

    Route::prefix('meetings')->group(function () {
        Route::get('/', [AdminMeetingController::class, 'index'])->name('meetings');    
        Route::get('/create', [AdminMeetingController::class, 'create'])->name('meetings.create');
        Route::post('/upload', [AdminMeetingController::class, 'upload'])->name('meetings.upload');
        Route::get('/print', [AdminMeetingController::class, 'print'])->name('meetings.print');
        Route::get('/download-pdf', [AdminMeetingController::class, 'downloadPDF'])->name('meetings.download-pdf');
        Route::get('/{meeting}', [AdminMeetingController::class, 'show'])->name('meetings.show');
        Route::get('/export', [AdminMeetingController::class, 'export'])->name('meetings.export');
        Route::get('/user/daily-meeting', [UserController::class, 'dailyMeeting'])->name('user.daily-meeting');
        Route::get('/admin/score-card/data', [AdminMeetingController::class, 'getScoreCardData'])->name('admin.score-card.data');
        Route::get('/admin/score-card/download', [AdminMeetingController::class, 'downloadScoreCard']);
        Route::get('/admin/meetings/print', [AdminMeetingController::class, 'print'])
        ->name('admin.meetings.print'); 
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('users');
        Route::get('/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/store', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        Route::get('/search', [AdminUserController::class, 'search'])->name('users.search');
    });
    

    Route::prefix('activities')->group(function () {
        Route::get('/export', [ActivityController::class, 'export'])->name('activities.export');
    });

    Route::prefix('settings')->group(function () {
        Route::get('/', [AdminSettingController::class, 'index'])->name('settings');
        Route::post('/', [AdminSettingController::class, 'update'])->name('settings.update');
        Route::post('/regenerate-api-key', [AdminSettingController::class, 'regenerateApiKey'])->name('settings.regenerate-api-key');
    });

    Route::prefix('score-card')->group(function () {
        Route::resource('score-card', ScoreCardDailyController::class);
      
    });

    Route::get('/machine-monitor', [MachineMonitorController::class, 'index'])->name('machine-monitor');

    // Link Koordinasi routes
    Route::prefix('link-koordinasi')->name('link-koordinasi.')->group(function () {
        Route::get('/coordination-links', function () {
            return view('admin.link-koordinasi.coordination-links');
        })->name('coordination-links');
    });

    Route::prefix('meeting-shift')->group(function () {
        Route::get('/', [MeetingShiftController::class, 'index'])->name('meeting-shift.index');
        Route::post('/', [MeetingShiftController::class, 'store'])->name('meeting-shift.store');
        Route::post('/store-alat-bantu', [MeetingShiftController::class, 'storeAlatBantu'])->name('meeting-shift.store-alat-bantu');
        Route::post('/store-resource', [MeetingShiftController::class, 'storeResource'])->name('meeting-shift.store-resource');
        Route::post('/store-k3l', [MeetingShiftController::class, 'storeK3L'])->name('meeting-shift.store-k3l');
        Route::post('/store-sistem', [MeetingShiftController::class, 'storeSistem'])->name('meeting-shift.store-sistem');
        Route::post('/store-catatan-umum', [MeetingShiftController::class, 'storeCatatanUmum'])->name('meeting-shift.store-catatan-umum');
        Route::post('/store-absensi', [MeetingShiftController::class, 'storeAbsensi'])->name('meeting-shift.store-absensi');
    });
});


// Tambahkan route group untuk profile
Route::middleware(['auth'])->group(function () {
    // Profile routes
    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [UserController::class, 'destroy'])->name('profile.destroy');
    Route::get('/generate-qrcode', [AttendanceController::class, 'generateQrCode'])->name('generate.qrcode');
    Route::post('/record-attendance', [AttendanceController::class, 'recordAttendance'])->name('record.attendance');
    Route::get('/daftar-hadir', [AttendanceController::class, 'index'])->name('admin.daftar_hadir.index');
    Route::get('/rekapitulasi', [AttendanceController::class, 'rekapitulasi'])->name('admin.daftar_hadir.rekapitulasi');
    
    // Tambahkan route untuk Zoom meeting
    Route::post('/create-zoom-meeting', [ScoreCardDailyController::class, 'createZoomMeeting'])
        ->name('admin.create-zoom-meeting');

    // Route untuk attendance
    Route::get('/attendance/error', function() {
        return view('admin.daftar_hadir.error');
    })->name('attendance.error');
    
    Route::get('/attendance/success', function() {
        return view('admin.daftar_hadir.success');
    })->name('attendance.success');
});

// Tambahkan route untuk AJAX
Route::get('/admin/machine-monitor/operations', [MachineMonitorController::class, 'getMachineOperations'])
    ->name('admin.machine-monitor.operations');

// Route untuk attendance
Route::prefix('attendance')->group(function () {
    Route::get('/scan/{token}', [AttendanceController::class, 'showScanForm'])->name('attendance.scan-form');
    Route::post('/submit', [AttendanceController::class, 'submitAttendance'])->name('attendance.submit');
});

// Route untuk create Zoom Meeting
//  Route::post('/create-zoom-meeting', [ScoreCardDailyController::class, 'createMeeting'])->name('createZoomMeeting');

Route::post('/create-zoom-meeting', [ScoreCardDailyController::class, 'createZoomMeeting'])
    ->name('create.zoom.meeting')
    ->middleware('web');

Route::get('/admin/pembangkit/report', [PembangkitController::class, 'report'])->name('admin.pembangkit.report');
Route::get('/admin/pembangkit/downloadReport', [PembangkitController::class, 'downloadReport'])->name('admin.pembangkit.downloadReport');
Route::get('/admin/pembangkit/printReport', [PembangkitController::class, 'printReport'])->name('admin.pembangkit.printReport');

Route::get('/admin/machine-monitor/show-all', [MachineMonitorController::class, 'showAll'])
    ->name('admin.machine-monitor.show.all');

Route::middleware(['auth'])->group(function () {
    // Prefix admin untuk semua route admin
    Route::prefix('admin')->name('admin.')->group(function () {
        // Route untuk Zoom Meeting
        Route::post('/create-zoom-meeting', [ScoreCardDailyController::class, 'createZoomMeeting'])
            ->name('create-zoom-meeting');

        // Route untuk Daftar Hadir
        Route::prefix('daftar-hadir')->name('daftar_hadir.')->group(function () {
            Route::post('/store-token', [DaftarHadirController::class, 'storeToken'])
                ->name('store_token');
        });

        // Route Score Card lainnya
        Route::resource('score-card', ScoreCardDailyController::class);

        // Route untuk laporan
        Route::get('/laporan/sr-wo', [LaporanController::class, 'srWo'])->name('laporan.sr_wo');
        Route::get('/laporan/create-sr', [LaporanController::class, 'createSR'])->name('laporan.create-sr');
        Route::get('/laporan/create-wo', [LaporanController::class, 'createWO'])->name('laporan.create-wo');
        Route::post('/laporan/store-sr', [LaporanController::class, 'storeSR'])->name('laporan.store-sr');
        Route::post('/laporan/store-wo', [LaporanController::class, 'storeWO'])->name('laporan.store-wo');
        
        // Route untuk update status
        Route::post('/laporan/update-sr-status/{id}', [LaporanController::class, 'updateSRStatus'])
            ->name('laporan.update-sr-status');
        Route::post('/laporan/update-wo-status/{id}', [LaporanController::class, 'updateWOStatus'])
            ->name('laporan.update-wo-status');
            
        // Route untuk WO Backlog
        Route::get('/laporan/create-wo-backlog', [LaporanController::class, 'createWOBacklog'])
            ->name('laporan.create-wo-backlog');
        Route::post('/laporan/store-wo-backlog', [LaporanController::class, 'storeWOBacklog'])
            ->name('laporan.store-wo-backlog');
        Route::get('/laporan/edit-wo-backlog/{id}', [LaporanController::class, 'editWoBacklog'])
            ->name('laporan.edit-wo-backlog');
        Route::put('/laporan/wo-backlog/{id}', [LaporanController::class, 'updateWoBacklog'])
            ->name('laporan.update-wo-backlog');
        Route::post('/laporan/update-backlog-status/{id}', [LaporanController::class, 'updateBacklogStatus'])
            ->name('laporan.update-backlog-status');
    });
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Pastikan route ini ada dan benar
    Route::get('/score-card/data', [AdminMeetingController::class, 'getScoreCardData'])
        ->name('admin.score-card.data');
});

// Route untuk attendance (tanpa middleware auth)
Route::prefix('attendance')->name('attendance.')->group(function () {
    Route::get('/scan/{token}', [AttendanceController::class, 'showScanForm'])
        ->name('scan-form')
        ->where('token', '.*')
        ->withoutMiddleware(['auth']);
        
    Route::post('/submit', [AttendanceController::class, 'submitAttendance'])
        ->name('submit')
        ->withoutMiddleware(['auth']);
});

// Route untuk admin (dengan middleware auth)
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Route untuk laporan
    Route::post('/laporan/update-sr-status/{id}', [LaporanController::class, 'updateSRStatus'])
        ->name('admin.laporan.update-sr-status');
    Route::post('/laporan/wo/{id}/update-status', [LaporanController::class, 'updateWOStatus'])->name('laporan.update-wo-status');
    
    // Route untuk daftar hadir
    Route::prefix('daftar-hadir')->name('daftar_hadir.')->group(function () {
        Route::get('/', [DaftarHadirController::class, 'index'])->name('index');
        Route::get('/generate-qr', [DaftarHadirController::class, 'generateQRCode'])->name('generate_qr');
        Route::post('/store-token', [DaftarHadirController::class, 'storeToken'])->name('store_token');
    });
});

Route::get('/attendance/scan/{token}', [AttendanceController::class, 'scan'])->name('attendance.scan');
Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
Route::get('/attendance/generate-qr', [AttendanceController::class, 'generateQRCode'])->name('attendance.generate-qr');

Route::get('/attendance/success', [AttendanceController::class, 'success'])->name('attendance.success');

Route::get('/admin/laporan/create-sr', [LaporanController::class, 'createSR'])->name('admin.laporan.create-sr');

// Rute untuk halaman SR/WO
Route::get('/admin/laporan/sr-wo', [LaporanController::class, 'srWo'])->name('admin.laporan.sr_wo');

// Rute untuk halaman tambah SR
Route::get('/admin/laporan/create-sr', [LaporanController::class, 'createSR'])->name('admin.laporan.create-sr');

// Rute untuk menyimpan SR
Route::post('/admin/laporan/store-sr', [LaporanController::class, 'storeSR'])->name('admin.laporan.store-sr');

Route::resource('wo_backlog', WoBacklogController::class);

Route::post('/admin/laporan/store-wo-backlog', [LaporanController::class, 'storeWOBacklog'])->name('admin.laporan.store-wo-backlog');

Route::get('/admin/laporan/create-wo-backlog', [LaporanController::class, 'createWOBacklog'])->name('admin.laporan.create-wo-backlog');

Route::get('/admin/laporan/create-wo', [LaporanController::class, 'createWO'])->name('admin.laporan.create-wo');

Route::post('/admin/laporan/store-wo', [LaporanController::class, 'storeWO'])->name('admin.laporan.store-wo');

Route::get('/attendance/signature/{id}', [AttendanceController::class, 'showSignature'])->name('attendance.signature');



Route::get('/dashboard-pemantauan', [DashboardPemantauanController::class, 'index'])
    ->name('dashboard.pemantauan');

Route::get('/admin/laporan/wo-backlog/{id}/edit', [LaporanController::class, 'editWoBacklog'])->name('admin.laporan.edit-wo-backlog');
Route::put('/admin/laporan/wo-backlog/{id}', [LaporanController::class, 'updateWoBacklog'])->name('admin.laporan.update-wo-backlog');

Route::post('/admin/laporan/wo-backlog/{id}/status', [LaporanController::class, 'updateBacklogStatus'])->name('admin.laporan.update-backlog-status');

Route::delete('/admin/machine-monitor/{id}', [MachineMonitorController::class, 'destroy'])
    ->name('admin.machine-monitor.destroy');

Route::get('/accumulation-data/{markerId}', [HomeController::class, 'getAccumulationData']);

Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        // Power Plants routes
        Route::get('/power-plants', [PowerPlantController::class, 'index'])->name('power-plants.index');
        Route::get('/power-plants/{id}/edit', [PowerPlantController::class, 'edit'])->name('power-plants.edit');
        Route::put('/power-plants/{id}', [PowerPlantController::class, 'update'])->name('power-plants.update');
        Route::delete('/power-plants/{id}', [PowerPlantController::class, 'destroy'])->name('power-plants.destroy');
        Route::get('/power-plants/create', [PowerPlantController::class, 'create'])->name('power-plants.create');
        Route::post('/power-plants', [PowerPlantController::class, 'store'])->name('power-plants.store');
    });
});

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});

// Other Discussions Routes
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {
    Route::get('/other-discussions', [OtherDiscussionController::class, 'index'])
        ->name('other-discussions.index');
    Route::get('/other-discussions/create', [OtherDiscussionController::class, 'create'])
        ->name('other-discussions.create');
    Route::post('/other-discussions', [OtherDiscussionController::class, 'store'])
        ->name('other-discussions.store');
    Route::get('/other-discussions/{id}/edit', [OtherDiscussionController::class, 'edit'])
        ->name('other-discussions.edit');
    Route::put('/other-discussions/{id}', [OtherDiscussionEditController::class, 'update'])
        ->name('admin.other-discussions.update');
    Route::delete('/other-discussions/{id}', [OtherDiscussionController::class, 'destroy'])
        ->name('other-discussions.destroy');
    Route::post('/other-discussions/{id}/update-status', [OtherDiscussionController::class, 'updateStatus'])
        ->name('other-discussions.update-status');
});

// Route untuk overdue discussions
Route::delete('/admin/overdue-discussions/{discussion}', [OverdueDiscussionController::class, 'destroy'])
     ->name('admin.overdue-discussions.destroy');
Route::post('/admin/overdue-discussions/{discussion}/update-status', [OverdueDiscussionController::class, 'updateStatus'])
     ->name('admin.overdue-discussions.update-status');

Route::post('/admin/peserta/update', [PesertaController::class, 'update'])->name('admin.peserta.update');

Route::prefix('admin/laporan')->group(function () {
    // Route yang sudah ada
    Route::get('/sr-wo', [LaporanController::class, 'srWo'])->name('admin.laporan.sr_wo');
    
    // Route baru untuk halaman manage
    Route::get('/manage', [LaporanController::class, 'manage'])->name('admin.laporan.manage');
    
    // Route untuk delete
    Route::delete('/sr/{id}', [LaporanController::class, 'destroySR'])->name('admin.laporan.sr.destroy');
    Route::delete('/wo/{id}', [LaporanController::class, 'destroyWO'])->name('admin.laporan.wo.destroy');
    Route::delete('/backlog/{id}', [LaporanController::class, 'destroyBacklog'])->name('admin.laporan.backlog.destroy');
});

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/manage', [LaporanController::class, 'manage'])->name('manage');
        // Perbaikan route delete
        Route::delete('/delete/{type}/{id}', [LaporanDeleteController::class, 'destroy'])
            ->name('delete')
            ->where(['type' => 'sr|wo|backlog']);
    });
});

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    // ... route lainnya ...
    
    // Route khusus untuk delete
    Route::delete('/laporan/delete/{type}/{id}', [LaporanDeleteController::class, 'destroy'])
        ->name('laporan.delete')
        ->where(['type' => 'sr|wo|backlog']);
});

Route::get('/get-mothballed-machines', [DashboardPemantauanController::class, 'getMothballedMachines']);

Route::get('/get-maintenance-machines', [DashboardPemantauanController::class, 'getMaintenanceMachines']);

Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    // Pembangkit routes
    Route::prefix('pembangkit')->name('pembangkit.')->group(function () {
        Route::get('/ready', [PembangkitController::class, 'ready'])->name('ready');
        Route::post('/save-status', [PembangkitController::class, 'saveStatus'])->name('save-status');
        Route::get('/get-status', [PembangkitController::class, 'getStatus'])->name('get-status');
        Route::get('/status-history', [PembangkitController::class, 'getStatusHistory'])->name('status-history');
        Route::get('/report', [PembangkitController::class, 'report'])->name('report');
        Route::get('/report/download', [PembangkitController::class, 'downloadReport'])->name('report.download');
        Route::get('/report/print', [PembangkitController::class, 'printReport'])->name('report.print');
        
        // Image routes
        Route::post('/upload-image', [PembangkitController::class, 'uploadImage'])->name('upload-image');
        Route::delete('/delete-image/{machineId}', [PembangkitController::class, 'deleteImage'])->name('delete-image');
        Route::get('/check-image/{machineId}', [PembangkitController::class, 'checkImage'])->name('check-image');
    });
});

Route::post('/admin/pembangkit/reset-status', [PembangkitController::class, 'resetStatus'])
    ->name('admin.pembangkit.reset-status');

Route::get('/admin/pembangkit/ready', [PembangkitController::class, 'ready'])->name('admin.pembangkit.ready');

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Route yang sudah ada
    Route::get('/pembangkit/ready', [PembangkitController::class, 'ready'])->name('pembangkit.ready');
    Route::post('/pembangkit/save-status', [PembangkitController::class, 'saveStatus'])->name('pembangkit.save-status');
    Route::get('/pembangkit/get-status', [PembangkitController::class, 'getStatus'])->name('pembangkit.get-status');
    
    // Tambahkan route baru untuk search
    Route::get('/pembangkit/search', [PembangkitController::class, 'searchMachines'])->name('pembangkit.search');
});

Route::middleware(['auth', 'admin'])->group(function () {
    // ... other admin routes ...
    
    // Backlog routes
    Route::get('/admin/laporan/create-backlog', [App\Http\Controllers\Admin\LaporanController::class, 'createBacklog'])
        ->name('admin.laporan.create-backlog');
    Route::post('/admin/laporan/store-backlog', [App\Http\Controllers\Admin\LaporanController::class, 'storeBacklog'])
        ->name('admin.laporan.store-backlog');
});

Route::middleware(['auth'])->group(function () {
    // ... route lainnya ...
    
    Route::get('/admin/machine-status/view', [MachineStatusViewController::class, 'index'])
         ->name('admin.machine-status.view');
});

Route::get('/admin/machine-status/view', [MachineStatusController::class, 'view'])
    ->name('admin.machine-status.view')
    ->middleware(['auth', 'json.response']);

Route::delete('/admin/discussions/{id}', [OtherDiscussionController::class, 'destroy'])
    ->name('admin.discussions.destroy');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('other-discussions', OtherDiscussionController::class)
        ->names([
            'index' => 'other-discussions.index',
            'create' => 'other-discussions.create',
            'store' => 'other-discussions.store',
            'edit' => 'other-discussions.edit',
            'update' => 'other-discussions.update',
            'destroy' => 'other-discussions.destroy'
        ]);
        
    // Tambahkan route untuk print dan export
    Route::get('other-discussions/print', [OtherDiscussionController::class, 'print'])
        ->name('other-discussions.print');
    
    Route::get('other-discussions/export/{format}', [OtherDiscussionController::class, 'export'])
        ->name('other-discussions.export')
        ->where('format', 'xlsx|pdf');
        
    Route::post('other-discussions/update-status', [OtherDiscussionController::class, 'updateStatus'])
        ->name('other-discussions.update-status');
});

Route::delete('/admin/overdue-discussions/{id}', [OtherDiscussionController::class, 'destroyOverdue'])
    ->name('admin.overdue-discussions.destroy');

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin'], 'as' => 'admin.'], function () {
    Route::get('machine-status/view', [MachineStatusController::class, 'view'])->name('machine-status.view');
});

Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    // Route yang sudah ada
    Route::delete('/overdue-discussions/{id}', [OverdueDiscussionController::class, 'destroy'])
        ->name('overdue-discussions.destroy');
    Route::post('/overdue-discussions/{id}/update-status', [OverdueDiscussionController::class, 'updateStatus'])
        ->name('overdue-discussions.update-status');
    
    // Tambahkan route baru untuk pengecekan overdue
    Route::post('/overdue-discussions/check', [OverdueDiscussionController::class, 'checkAndMoveOverdue'])
        ->name('overdue-discussions.check')
        ->middleware('web');
});

Route::post('/other-discussions/update-status', [OtherDiscussionController::class, 'updateStatus'])
    ->name('admin.other-discussions.update-status');

Route::get('/admin/other-discussions/{id}/edit', [OtherDiscussionEditController::class, 'edit'])
    ->name('admin.other-discussions.edit');

Route::put('/admin/other-discussions/{id}', [OtherDiscussionEditController::class, 'update'])
    ->name('admin.other-discussions.update');

Route::resource('other-discussions', OtherDiscussionController::class)
    ->except(['edit', 'update']);

Route::post('other-discussions/update-status', [OtherDiscussionController::class, 'updateStatus'])
    ->name('admin.other-discussions.update-status');

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
    // Pastikan namespace lengkap
    Route::get('other-discussions/{id}/edit', [OtherDiscussionEditController::class, 'edit'])
        ->name('other-discussions.edit');
    Route::put('other-discussions/{id}', [OtherDiscussionEditController::class, 'update'])
        ->name('other-discussions.update');
});

// Tambahkan route fallback untuk debugging
Route::fallback(function () {
    return response()->json(['error' => 'Route tidak ditemukan'], 404);
});

Route::get('/generate-no-pembahasan', [App\Http\Controllers\Admin\OtherDiscussionController::class, 'generateNoPembahasan'])->name('generate.no-pembahasan');

// Tambahkan route untuk generate nomor pembahasan
Route::post('/admin/other-discussions/generate-no-pembahasan', [App\Http\Controllers\Admin\OtherDiscussionController::class, 'generateNoPembahasan'])
    ->name('admin.other-discussions.generate-no-pembahasan')
    ->middleware(['auth']);

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
    // Other routes...
    Route::post('/other-discussions/generate-no-pembahasan', [
        App\Http\Controllers\Admin\OtherDiscussionController::class, 
        'generateNoPembahasan'
    ])->name('admin.other-discussions.generate-no-pembahasan');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/public/attendance/generate-qr', [AttendanceController::class, 'generateQRCode'])
        ->name('attendance.generate-qr');
    Route::get('/public/attendance/scan/{token}', [AttendanceController::class, 'scan'])
        ->name('attendance.scan');
});

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // ... route lainnya ...
    
    Route::get('other-discussions/show', [OtherDiscussionController::class, 'show'])
        ->name('other-discussions.show');
    
    Route::get('other-discussions/{id}', [OtherDiscussionController::class, 'show_single'])
        ->name('other-discussions.show_single');
});

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('other-discussions/export/xlsx', [OtherDiscussionController::class, 'exportExcel'])
        ->name('other-discussions.export.xlsx');
});

Route::get('/admin/other-discussions/{id}/print', [OtherDiscussionController::class, 'printSingle'])->name('admin.other-discussions.print.single');
Route::get('/admin/other-discussions/{id}/export/{format}', [OtherDiscussionController::class, 'exportSingle'])->name('admin.other-discussions.export.single');

Route::get('/get-plant-chart-data/{plantId}', [HomeController::class, 'getPlantChartData'])->name('plant.chart.data');

Route::get('other-discussions/{id}/download-document', 
    [OtherDiscussionController::class, 'downloadDocument'])
    ->name('admin.other-discussions.download-document');

Route::post('/other-discussions/{id}/remove-file', [OtherDiscussionEditController::class, 'removeFile'])
    ->name('admin.other-discussions.remove-file');

Route::middleware(['auth', 'web'])->prefix('admin')->group(function () {
    // Verifikasi password
    Route::post('/verify-password', [PasswordVerificationController::class, 'verify'])
        ->name('verify-password');
        
    // Route lainnya...
    Route::delete('/other-discussions/{discussion}/remove-file/{index}', 
        [OtherDiscussionController::class, 'removeFile']);
    Route::delete('/other-discussions/{discussion}/commitments/{commitment}', 
        [OtherDiscussionController::class, 'removeCommitment']);
});

// Verifikasi password
Route::post('/admin/verify-password', [App\Http\Controllers\Admin\PasswordVerificationController::class, 'verify'])
    ->name('admin.verify-password');

// Hapus file
Route::delete('/admin/other-discussions/{discussion}/remove-file/{index}', [App\Http\Controllers\Admin\OtherDiscussionController::class, 'removeFile'])
    ->name('admin.other-discussions.remove-file');

// Route untuk hapus commitment
Route::delete('/admin/other-discussions/{discussion}/commitments/{commitment}', 
    [App\Http\Controllers\Admin\OtherDiscussionController::class, 'removeCommitment'])
    ->name('admin.other-discussions.remove-commitment');

// Tambahkan di akhir file, sebelum route fallback
Route::middleware(['auth'])->group(function () {
    // Update status routes
    Route::post('/admin/laporan/update-sr-status/{id}', [App\Http\Controllers\Admin\LaporanController::class, 'updateSRStatus'])
        ->name('admin.laporan.update-sr-status');
    Route::post('/admin/laporan/update-wo-status/{id}', [App\Http\Controllers\Admin\LaporanController::class, 'updateWOStatus'])
        ->name('admin.laporan.update-wo-status');
});

// Route fallback untuk debugging
Route::fallback(function () {
    return response()->json(['error' => 'Route tidak ditemukan'], 404);
});

Route::middleware(['auth', 'web'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('/verify-password', [PasswordVerificationController::class, 'verify'])
            ->name('verify-password');
        Route::resource('other-discussions', OtherDiscussionController::class);
    });
});

Route::post('/admin/laporan/verify-delete', [LaporanController::class, 'verifyPasswordAndDelete'])
    ->name('admin.laporan.verify-delete');

// Tambahkan route berikut di dalam group admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
    // ... existing routes ...

    // Routes untuk handle image
    Route::post('/pembangkit/upload-image', [PembangkitController::class, 'uploadImage'])->name('admin.pembangkit.upload-image');
    Route::delete('/pembangkit/delete-image', [PembangkitController::class, 'deleteImage'])->name('admin.pembangkit.delete-image');
    Route::get('/pembangkit/get-images/{machineId}', [PembangkitController::class, 'getImages'])->name('admin.pembangkit.get-images');
});

Route::post('/admin/laporan/move-to-backlog/{id}', [LaporanController::class, 'moveToBacklog'])
    ->name('admin.laporan.move-to-backlog');

Route::get('/admin/laporan/edit-wo/{id}', [LaporanController::class, 'editWO'])
    ->name('admin.laporan.edit-wo');
Route::post('/admin/laporan/update-wo/{id}', [LaporanController::class, 'updateWO'])
    ->name('admin.laporan.update-wo');

    Route::get('/admin/laporan/download-document/{id}', [LaporanController::class, 'downloadDocument'])
        ->name('admin.laporan.download-document');
    
Route::prefix('admin/daftar-hadir')->group(function () {
    Route::get('/export-excel', [AttendanceController::class, 'exportExcel'])
        ->name('admin.daftar_hadir.export-excel');
    Route::get('/export-pdf', [AttendanceController::class, 'exportPDF'])
        ->name('admin.daftar_hadir.export-pdf');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Route yang sudah ada
    Route::get('/daftar-hadir/rekapitulasi', [AttendanceController::class, 'rekapitulasi'])
        ->name('admin.daftar_hadir.rekapitulasi');
    
    // Tambahkan route untuk export dan print
    Route::get('/daftar-hadir/export-excel', [AttendanceController::class, 'exportExcel'])
        ->name('admin.daftar_hadir.export-excel');
    
    Route::get('/daftar-hadir/export-pdf', [AttendanceController::class, 'exportPDF'])
        ->name('admin.daftar_hadir.export-pdf');
    
    // Route baru untuk print
    Route::get('/daftar-hadir/print', [AttendanceController::class, 'printView'])
        ->name('admin.daftar_hadir.print');
});

Route::get('/admin/laporan/print/{type}', [LaporanController::class, 'print'])
    ->name('admin.laporan.print')
    ->middleware(['auth']);

Route::post('/admin/daftar-hadir/backdate', [AttendanceController::class, 'storeBackdate'])
    ->name('admin.daftar_hadir.backdate');

Route::get('/admin/laporan/download-backlog-document/{no_wo}', [LaporanController::class, 'downloadBacklogDocument'])
    ->name('admin.laporan.download-backlog-document');

Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        // ... other routes ...
        
        // Daftar Hadir routes
        Route::prefix('daftar-hadir')->name('daftar_hadir.')->group(function () {
            Route::get('/rekapitulasi', [AttendanceController::class, 'rekapitulasi'])->name('rekapitulasi');
            Route::post('/generate-backdate-token', [AttendanceController::class, 'generateBackdateToken'])->name('generate-backdate-token');
            Route::get('/export-excel', [AttendanceController::class, 'exportExcel'])->name('export-excel');
            Route::get('/export-pdf', [AttendanceController::class, 'exportPDF'])->name('export-pdf');
            Route::get('/print', [AttendanceController::class, 'printView'])->name('print');
        });
    });
});

// ... existing code ...

Route::get('/monitoring-data/{period}', [HomeController::class, 'getMonitoringData'])
    ->name('monitoring.data')
    ->where('period', 'daily|weekly|monthly');

Route::post('/attendance/scan-qr', [AttendanceController::class, 'scanQR'])->name('attendance.scan-qr');

// Route::get('/admin/daily-summary', [DailySummaryController::class, 'index'])->name('admin.daily-summary.index');

// Rute untuk Ikhtisar Harian
Route::get('/admin/daily-summary', [DailySummaryController::class, 'index'])->name('admin.daily-summary');
Route::post('/admin/daily-summary', [DailySummaryController::class, 'store'])->name('daily-summary.store');
Route::get('/daily-summary/results', [DailySummaryController::class, 'results'])->name('daily-summary.results');

Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        // ... other admin routes ...
        
        // Rencana Daya Mampu routes
        Route::get('rencana-daya-mampu', [RencanaDayaMampuController::class, 'index'])
            ->name('rencana-daya-mampu');
        
        Route::get('rencana-daya-mampu/get-status', [RencanaDayaMampuController::class, 'getStatus'])
            ->name('rencana-daya-mampu.get-status');
        Route::post('rencana-daya-mampu/save-status', [RencanaDayaMampuController::class, 'saveStatus'])
            ->name('rencana-daya-mampu.save-status');
    });
});

Route::post('/admin/rencana-daya-mampu/update', [RencanaDayaMampuController::class, 'update'])
    ->name('admin.rencana-daya-mampu.update');

Route::post('/daily-summary/store', [DailySummaryController::class, 'store'])->name('daily-summary.store');
Route::get('/daily-summary/results', [DailySummaryController::class, 'results'])->name('daily-summary.results');

Route::middleware(['auth'])->group(function () {
    // ... other routes ...
    
    // Daily Summary Routes
    Route::prefix('admin')->group(function () {
        Route::get('/daily-summary', [DailySummaryController::class, 'index'])->name('admin.daily-summary');
        Route::post('/daily-summary', [DailySummaryController::class, 'store'])->name('admin.daily-summary.store');
        Route::get('/daily-summary/results', [DailySummaryController::class, 'results'])->name('admin.daily-summary.results');
    });
    
});

Route::get('/admin/administrasi-operasi', [AdministrasiOperasiController::class, 'index'])
    ->name('admin.administrasi_operasi.index')
    ->middleware(['auth']);

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/library', [LibraryController::class, 'index'])->name('admin.library.index');
    Route::post('/admin/library/upload', [LibraryController::class, 'upload'])->name('admin.library.upload');
    Route::get('/admin/library/{document}/download', [LibraryController::class, 'download'])->name('admin.library.download');
    Route::delete('/admin/library/{document}', [LibraryController::class, 'destroy'])->name('admin.library.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/daily-summary/get-latest-data', [DailySummaryController::class, 'getLatestData'])->name('daily-summary.get-latest-data');
});

Route::get('/admin/library/berita-acara', [LibraryController::class, 'beritaAcara'])->name('admin.library.berita-acara');
Route::get('/admin/library/standarisasi', [LibraryController::class, 'standarisasi'])->name('admin.library.standarisasi');
Route::get('/admin/library/bacaan-digital', [LibraryController::class, 'bacaanDigital'])->name('admin.library.bacaan-digital');
Route::get('/admin/library/diklat', [LibraryController::class, 'diklat'])->name('admin.library.diklat');

Route::post('/admin/library/upload', [LibraryController::class, 'upload'])->name('admin.library.upload');

Route::get('/admin/library/view/{document}', [LibraryController::class, 'view'])->name('admin.library.view');



Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        // ... other routes ...
        
        // Perbaiki route untuk machine monitor
        Route::get('/machine-monitor', [MachineMonitorController::class, 'index'])->name('machine-monitor');
        Route::get('/machine-monitor/filter', [MachineMonitorController::class, 'filter'])->name('machine-monitor.filter');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        // Library routes
        Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
        Route::get('/library/berita-acara', [LibraryController::class, 'beritaAcara'])->name('library.berita-acara');
        Route::get('/library/standarisasi', [LibraryController::class, 'standarisasi'])->name('library.standarisasi');
        Route::get('/library/bacaan-digital', [LibraryController::class, 'bacaanDigital'])->name('library.bacaan-digital');
        Route::get('/library/diklat', [LibraryController::class, 'diklat'])->name('library.diklat');
        Route::post('/library/upload', [LibraryController::class, 'upload'])->name('library.upload');
        Route::get('/library/{document}/download', [LibraryController::class, 'download'])->name('library.download');
        Route::delete('/library/delete/{document}', [LibraryController::class, 'destroy'])->name('library.delete');
    });
});

Route::get('/admin/rencana-daya-mampu/manage', [RencanaDayaMampuController::class, 'manage'])
    ->name('admin.rencana-daya-mampu.manage');
Route::get('/admin/rencana-daya-mampu/export', [RencanaDayaMampuController::class, 'export'])
    ->name('admin.rencana-daya-mampu.export');

Route::get('/admin/daily-summary/export-pdf', [DailySummaryController::class, 'exportPdf'])
    ->name('admin.daily-summary.export-pdf');
Route::get('/admin/daily-summary/export-excel', [DailySummaryController::class, 'exportExcel'])
    ->name('admin.daily-summary.export-excel');

Route::get('/install-app', function () {
    return view('install-pwa');
})->name('install.pwa');

Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('machine-status/view', [MachineStatusController::class, 'view'])->name('machine-status.view');
        Route::get('machine-status/export-pdf', [MachineStatusController::class, 'exportPdf'])->name('machine-status.export-pdf');
        Route::get('machine-status/export-excel', [MachineStatusController::class, 'exportExcel'])->name('machine-status.export-excel');
    });
});

// Route for setting unit in session
Route::post('/set-unit', [DailySummaryController::class, 'setUnit'])->name('set-unit');

// Route for setting unit source
Route::post('/set-unit-source', [DailySummaryController::class, 'setUnitSource'])
    ->name('set-unit-source')
    ->middleware('auth');



Route::get('/admin/energiprimer/bahan-bakar', [BahanBakarController::class, 'index'])->name('admin.energiprimer.bahan-bakar');
Route::post('/admin/energiprimer/bahan-bakar', [BahanBakarController::class, 'store'])->name('admin.energiprimer.bahan-bakar.store');

Route::get('/admin/energiprimer/bahan-bakar/create', [BahanBakarController::class, 'create'])->name('admin.energiprimer.bahan-bakar.create');

Route::prefix('admin/energiprimer')->name('admin.energiprimer.')->group(function () {
    Route::get('/pelumas', [PelumasController::class, 'index'])->name('pelumas');
    Route::get('/pelumas/create', [PelumasController::class, 'create'])->name('pelumas.create');
    Route::post('/pelumas', [PelumasController::class, 'store'])->name('pelumas.store');
    
    // API route untuk cek saldo sebelumnya
    Route::get('/api/check-previous-balance-pelumas', function (Request $request) {
        $previousBalance = Pelumas::where('unit_id', $request->unit_id)
            ->where('jenis_pelumas', $request->jenis_pelumas)
            ->where('tanggal', '<', $request->tanggal)
            ->orderBy('tanggal', 'desc')
            ->first();

        return response()->json([
            'has_previous' => $previousBalance !== null,
            'previous_balance' => $previousBalance ? $previousBalance->saldo_akhir : null
        ]);
    });
});

Route::prefix('admin/energiprimer')->name('admin.energiprimer.')->group(function () {
    Route::get('/bahan-kimia', [BahanKimiaController::class, 'index'])->name('bahan-kimia');
    Route::get('/bahan-kimia/create', [BahanKimiaController::class, 'create'])->name('bahan-kimia.create');
    Route::post('/bahan-kimia', [BahanKimiaController::class, 'store'])->name('bahan-kimia.store');
    
    // API route untuk cek saldo sebelumnya
    Route::get('/api/check-previous-balance-kimia', function (Request $request) {
        $previousBalance = BahanKimia::where('unit_id', $request->unit_id)
            ->where('jenis_bahan', $request->jenis_bahan)
            ->where('tanggal', '<', $request->tanggal)
            ->orderBy('tanggal', 'desc')
            ->first();

        return response()->json([
            'has_previous' => $previousBalance !== null,
            'previous_balance' => $previousBalance ? $previousBalance->saldo_akhir : null
        ]);
    });
});

Route::prefix('admin/energiprimer')->name('admin.energiprimer.')->group(function () {
    // Bahan Bakar routes
    Route::get('/bahan-bakar', [BahanBakarController::class, 'index'])->name('bahan-bakar');
    Route::get('/bahan-bakar/create', [BahanBakarController::class, 'create'])->name('bahan-bakar.create');
    Route::post('/bahan-bakar', [BahanBakarController::class, 'store'])->name('bahan-bakar.store');
    Route::get('/bahan-bakar/{id}/edit', [BahanBakarController::class, 'edit'])->name('bahan-bakar.edit');
    Route::put('/bahan-bakar/{id}', [BahanBakarController::class, 'update'])->name('bahan-bakar.update');
    Route::delete('/bahan-bakar/{id}', [BahanBakarController::class, 'destroy'])->name('bahan-bakar.destroy');

    // Bahan Kimia routes
    Route::get('/bahan-kimia', [BahanKimiaController::class, 'index'])->name('bahan-kimia');
    Route::get('/bahan-kimia/create', [BahanKimiaController::class, 'create'])->name('bahan-kimia.create');
    Route::post('/bahan-kimia', [BahanKimiaController::class, 'store'])->name('bahan-kimia.store');
    Route::get('/bahan-kimia/{id}/edit', [BahanKimiaController::class, 'edit'])->name('bahan-kimia.edit');
    Route::put('/bahan-kimia/{id}', [BahanKimiaController::class, 'update'])->name('bahan-kimia.update');
    Route::delete('/bahan-kimia/{id}', [BahanKimiaController::class, 'destroy'])->name('bahan-kimia.destroy');

    // Pelumas routes
    Route::get('/pelumas', [PelumasController::class, 'index'])->name('pelumas');
    Route::get('/pelumas/create', [PelumasController::class, 'create'])->name('pelumas.create');
    Route::post('/pelumas', [PelumasController::class, 'store'])->name('pelumas.store');
    Route::get('/pelumas/{id}/edit', [PelumasController::class, 'edit'])->name('pelumas.edit');
    Route::put('/pelumas/{id}', [PelumasController::class, 'update'])->name('pelumas.update');
    Route::delete('/pelumas/{id}', [PelumasController::class, 'destroy'])->name('pelumas.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // ... existing routes ...

    // Route untuk Energi Primer
    Route::prefix('energiprimer')->name('energiprimer.')->group(function () {
        // Bahan Bakar routes
        Route::get('bahan-bakar', [BahanBakarController::class, 'index'])->name('bahan-bakar');
        Route::get('bahan-bakar/create', [BahanBakarController::class, 'create'])->name('bahan-bakar.create');
        Route::post('bahan-bakar', [BahanBakarController::class, 'store'])->name('bahan-bakar.store');
        Route::get('bahan-bakar/{id}/edit', [BahanBakarController::class, 'edit'])->name('bahan-bakar.edit');
        Route::put('bahan-bakar/{id}', [BahanBakarController::class, 'update'])->name('bahan-bakar.update');
        Route::delete('bahan-bakar/{id}', [BahanBakarController::class, 'destroy'])->name('bahan-bakar.destroy');
        Route::get('bahan-bakar/export-pdf', [BahanBakarController::class, 'exportPdf'])->name('bahan-bakar.export-pdf');
        Route::get('bahan-bakar/export-excel', [BahanBakarController::class, 'exportExcel'])->name('bahan-bakar.export-excel');

        // Pelumas routes
        Route::get('pelumas', [PelumasController::class, 'index'])->name('pelumas');
        Route::get('pelumas/create', [PelumasController::class, 'create'])->name('pelumas.create');
        Route::post('pelumas', [PelumasController::class, 'store'])->name('pelumas.store');
        Route::get('pelumas/{id}/edit', [PelumasController::class, 'edit'])->name('pelumas.edit');
        Route::put('pelumas/{id}', [PelumasController::class, 'update'])->name('pelumas.update');
        Route::delete('pelumas/{id}', [PelumasController::class, 'destroy'])->name('pelumas.destroy');
        Route::get('pelumas/export-pdf', [PelumasController::class, 'exportPdf'])->name('pelumas.export-pdf');
        Route::get('pelumas/export-excel', [PelumasController::class, 'exportExcel'])->name('pelumas.export-excel');

        // Bahan Kimia routes
        Route::get('bahan-kimia', [BahanKimiaController::class, 'index'])->name('bahan-kimia');
        Route::get('bahan-kimia/create', [BahanKimiaController::class, 'create'])->name('bahan-kimia.create');
        Route::post('bahan-kimia', [BahanKimiaController::class, 'store'])->name('bahan-kimia.store');
        Route::get('bahan-kimia/{id}/edit', [BahanKimiaController::class, 'edit'])->name('bahan-kimia.edit');
        Route::put('bahan-kimia/{id}', [BahanKimiaController::class, 'update'])->name('bahan-kimia.update');
        Route::delete('bahan-kimia/{id}', [BahanKimiaController::class, 'destroy'])->name('bahan-kimia.destroy');
        Route::get('bahan-kimia/export-pdf', [BahanKimiaController::class, 'exportPdf'])->name('bahan-kimia.export-pdf');
        Route::get('bahan-kimia/export-excel', [BahanKimiaController::class, 'exportExcel'])->name('bahan-kimia.export-excel');
    });

    // ... existing routes ...
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/data-engine', [DataEngineController::class, 'index'])
        ->name('admin.data-engine.index');
});

Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        // ... existing routes ...
        
        // K3 KAMP routes (temporary views)
        Route::get('/k3-kamp', function () {
            return view('admin.k3-kamp.index');
        })->name('k3-kamp.index');
        
        Route::get('/k3-kamp/view', function () {
            return view('admin.k3-kamp.view');
        })->name('k3-kamp.view');
    });
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // ... existing routes ...
    
    // FLM Routes
    Route::get('/flm', function () {
        return view('admin.flm.index');
    })->name('flm.index');
    Route::post('/flm', [FlmController::class, 'store'])->name('flm.store');
});

Route::get('/admin/data-engine/{date}/edit', [DataEngineController::class, 'edit'])->name('admin.data-engine.edit');
Route::post('/admin/data-engine/update', [DataEngineController::class, 'update'])->name('admin.data-engine.update');

Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        // ... existing routes ...
        
        // Calendar routes
        Route::get('/kalender', [App\Http\Controllers\Admin\OperationScheduleController::class, 'index'])->name('kalender.calendar');
        Route::get('/kalender/create', [App\Http\Controllers\Admin\OperationScheduleController::class, 'create'])->name('kalender.create');
        Route::post('/kalender', [App\Http\Controllers\Admin\OperationScheduleController::class, 'store'])->name('kalender.store');
        Route::get('/kalender/{schedule}/edit', [App\Http\Controllers\Admin\OperationScheduleController::class, 'edit'])->name('kalender.edit');
        Route::put('/kalender/{schedule}', [App\Http\Controllers\Admin\OperationScheduleController::class, 'update'])->name('kalender.update');
        Route::delete('/kalender/{schedule}', [App\Http\Controllers\Admin\OperationScheduleController::class, 'destroy'])->name('kalender.destroy');
        Route::get('/kalender/schedules/{date}', [App\Http\Controllers\Admin\OperationScheduleController::class, 'getSchedulesByDate'])->name('kalender.schedules');
    });
});

// Meeting dan Mutasi Shift routes
Route::get('/meeting-shift', [MeetingShiftController::class, 'index'])->name('admin.meeting-shift.index');
Route::post('/meeting-shift', [MeetingShiftController::class, 'store'])->name('admin.meeting-shift.store');
Route::post('/admin/meeting-shift/store-alat-bantu', [MeetingShiftController::class, 'storeAlatBantu'])->name('admin.meeting-shift.store-alat-bantu');
Route::post('/admin/meeting-shift/store-resource', [MeetingShiftController::class, 'storeResource'])->name('admin.meeting-shift.store-resource');
Route::post('/admin/meeting-shift/store-k3l', [MeetingShiftController::class, 'storeK3L'])->name('admin.meeting-shift.store-k3l');
Route::post('/admin/meeting-shift/store-sistem', [MeetingShiftController::class, 'storeSistem'])->name('admin.meeting-shift.store-sistem');
Route::post('/admin/meeting-shift/store-catatan-umum', [MeetingShiftController::class, 'storeCatatanUmum'])->name('admin.meeting-shift.store-catatan-umum');
