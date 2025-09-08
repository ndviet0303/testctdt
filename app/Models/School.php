<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'university_id',
        'name',
        'code',
        'full_name',
        'description',
        'dean_name',
        'phone',
        'email',
        'address',
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
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfUniversity($query, $universityId)
    {
        return $query->where('university_id', $universityId);
    }

    // Helper methods
    public function getTotalDepartmentsCount()
    {
        return $this->departments()->count();
    }

    public function getActiveDepartments()
    {
        return $this->departments()->where('is_active', true);
    }

    public function getFullPath()
    {
        return $this->university->name . ' - ' . $this->name;
    }
}
