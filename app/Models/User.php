<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    public static $isSyncing = false;

    // Role constants
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
    const ROLE_ASMAN_UNIT = 'asman_unit';
    const ROLE_TL_RON = 'tl_ron';
    const ROLE_TL_EP = 'tl_ep';
    const ROLE_OPERATOR = 'operator';
    const ROLE_STAF = 'staf';
    const ROLE_TL_RON_UPKD = 'tl_ron_upkd';

    protected $fillable = [
        'name',
        'email',
        'password',
        'department_id',
        'role',
        'unit_source'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function meetings()
    {
        return $this->belongsToMany(Meeting::class, 'meeting_participants')
                    ->withTimestamps()
                    ->withPivot('status', 'notes');
    }

    public function createdMeetings()
    {
        return $this->hasMany(Meeting::class, 'created_by');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isOperator()
    {
        return $this->role === 'operator';
    }

    public function isTlRonUpkd()
    {
        return $this->role === 'tl_ron_upkd';
    }
    public function isStaf()
    {
        return $this->role === 'staf';
    }

    public function isTlRon()
    {
        return $this->role === 'tl_ron';
    }
    public function getConnectionName()
    {
        return session('unit', 'u478221055_up_kendari');
    }

    protected static function boot()
    {
        parent::boot();

        // Handle Created Event
        static::created(function ($user) {
            self::syncToUpKendari('create', $user);
        });

        // Handle Updated Event
        static::updated(function ($user) {
            self::syncToUpKendari('update', $user);
        });

        // Handle Deleted Event
        static::deleted(function ($user) {
            self::syncToUpKendari('delete', $user);
        });
    }

    protected static function syncToUpKendari($action, $user)
    {
        if (self::$isSyncing) return;

        try {
            self::$isSyncing = true;

            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password,
                'role' => $user->role,
                'email_verified_at' => $user->email_verified_at,
                'remember_token' => $user->remember_token,
                'unit_source' => session('unit'),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ];

            Log::info("Attempting to {$action} User sync", ['data' => $data]);

            $upKendari = DB::connection('mysql')->table('users');

            switch($action) {
                case 'create':
                    $upKendari->insert($data);
                    break;

                case 'update':
                    $upKendari->where('id', $user->id)
                             ->update($data);
                    break;

                case 'delete':
                    $upKendari->where('id', $user->id)
                             ->delete();
                    break;
            }

            Log::info("User {$action} sync successful", [
                'id' => $user->id,
                'unit' => 'poasia'
            ]);

        } catch (\Exception $e) {
            Log::error("User {$action} sync failed", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        } finally {
            self::$isSyncing = false;
        }
    }
}