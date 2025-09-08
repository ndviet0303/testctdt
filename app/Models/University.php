<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class University extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'full_name',
        'description',
        'address',
        'phone',
        'email',
        'website',
        'logo',
        'established_date',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'established_date' => 'date',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    // Relationships
    public function schools(): HasMany
    {
        return $this->hasMany(School::class);
    }

    public function departments()
    {
        return $this->hasManyThrough(Department::class, School::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helper methods
    public function getTotalSchoolsCount()
    {
        return $this->schools()->count();
    }

    public function getTotalDepartmentsCount()
    {
        return $this->departments()->count();
    }

    public function getActiveSchools()
    {
        return $this->schools()->where('is_active', true);
    }
}
