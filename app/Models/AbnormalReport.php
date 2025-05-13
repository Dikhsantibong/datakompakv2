<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbnormalReport extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function chronologies(): HasMany
    {
        return $this->hasMany(AbnormalChronology::class);
    }

    public function affectedMachines(): HasMany
    {
        return $this->hasMany(AffectedMachine::class);
    }

    public function followUpActions(): HasMany
    {
        return $this->hasMany(FollowUpAction::class);
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class);
    }

    public function admActions(): HasMany
    {
        return $this->hasMany(AdmAction::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function evidences(): HasMany
    {
        return $this->hasMany(AbnormalEvidence::class);
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
} 