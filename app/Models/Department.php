<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'code',
        'full_name',
        'description',
        'head_name',
        'phone',
        'email',
        'office_location',
        'established_date',
        'student_count',
        'staff_count',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'established_date' => 'date',
        'is_active' => 'boolean',
        'student_count' => 'integer',
        'staff_count' => 'integer',
        'metadata' => 'array',
    ];

    // Relationships
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function university()
    {
        return $this->hasOneThrough(University::class, School::class, 'id', 'id', 'school_id', 'university_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfSchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeOfUniversity($query, $universityId)
    {
        return $query->whereHas('school', function ($q) use ($universityId) {
            $q->where('university_id', $universityId);
        });
    }

    // Helper methods
    public function getFullPath()
    {
        return $this->school->university->name . ' - ' . $this->school->name . ' - ' . $this->name;
    }

    public function getTotalPeople()
    {
        return $this->student_count + $this->staff_count;
    }
}
