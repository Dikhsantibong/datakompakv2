<?php

namespace App\Providers;

use App\Models\ServiceRequest;
use App\Observers\ServiceRequestObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\MachineStatusUpdated;
use App\Listeners\SyncMachineStatusToUpKendari;
use App\Events\OtherDiscussionUpdated;
use App\Listeners\SyncOtherDiscussionToUpKendari;
use App\Events\ScoreCardDailyUpdated;
use App\Listeners\SyncScoreCardDailyToUpKendari;
use App\Events\PesertaUpdated;
use App\Listeners\SyncPesertaToUpKendari;
use App\Events\DailySummaryUpdated;
use App\Listeners\SyncDailySummaryToUpKendari;
use App\Events\RencanaDayaMampuUpdated;
use App\Listeners\SyncRencanaDayaMampuToUpKendari;
use App\Events\MachineLogUpdated;
use App\Listeners\SyncMachineLogToUpKendari;
use App\Events\MeetingShiftUpdated;
use App\Listeners\SyncMeetingShiftToUpKendari;
use App\Events\Registered;
use App\Listeners\SendEmailVerificationNotification;

class EventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();
        ServiceRequest::observe(ServiceRequestObserver::class);
    }

    protected $listen = [
        MachineStatusUpdated::class => [
            SyncMachineStatusToUpKendari::class,
        ],
        OtherDiscussionUpdated::class => [
            SyncOtherDiscussionToUpKendari::class,  
        ],
        ScoreCardDailyUpdated::class => [
            SyncScoreCardDailyToUpKendari::class,
        ],
        PesertaUpdated::class => [
            SyncPesertaToUpKendari::class,
        ],
        DailySummaryUpdated::class => [
            SyncDailySummaryToUpKendari::class,
        ],
        RencanaDayaMampuUpdated::class => [
            SyncRencanaDayaMampuToUpKendari::class,
        ],
        MachineLogUpdated::class => [
            SyncMachineLogToUpKendari::class,
        ],
        MeetingShiftUpdated::class => [
            SyncMeetingShiftToUpKendari::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \App\Events\AbnormalReportUpdated::class => [
            \App\Listeners\SyncAbnormalReportToUpKendari::class,
        ],
        'App\Events\FlmInspectionUpdated' => [
            'App\Listeners\SyncFlmInspectionToUpKendari',
        ],
    ];
} 