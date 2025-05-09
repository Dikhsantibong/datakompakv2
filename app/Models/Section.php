<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'order'
    ];

     // Eager load pics by default

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    

    public function subsections()
    {
        return $this->hasMany(Subsection::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($section) {
            Log::info('Section retrieved:', [
                'id' => $section->id,
                'name' => $section->name,
                'department_id' => $section->department_id
            ]);
        });
    }
    public function getConnectionName()
    {
        return session('unit');
    }
} 