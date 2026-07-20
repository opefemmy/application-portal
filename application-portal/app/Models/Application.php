<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'application_number',
        'application_type_id',
        'personal_info',
        'academic_info',
        'employment_info',
        'application_details',
        'status',
        'notes',
    ];

    protected $casts = [
        'personal_info' => 'array',
        'academic_info' => 'array',
        'employment_info' => 'array',
        'application_details' => 'array',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_REVIEWED = 'reviewed';
    const STATUS_SHORTLISTED = 'shortlisted';
    const STATUS_INTERVIEW_SCHEDULED = 'interview_scheduled';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_REVIEWED => 'Reviewed',
            self::STATUS_SHORTLISTED => 'Shortlisted',
            self::STATUS_INTERVIEW_SCHEDULED => 'Interview Scheduled',
            self::STATUS_ACCEPTED => 'Accepted',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_COMPLETED => 'Completed',
        ];
    }

    public function documents()
    {
        return $this->hasMany(ApplicationDocument::class, 'application_id');
    }

    public function interview()
    {
        return $this->hasOne(Interview::class, 'application_id');
    }

    public function applicationType()
    {
        return $this->belongsTo(ApplicationType::class, 'application_type_id');
    }

    public function getFullNameAttribute()
    {
        $personal = $this->personal_info ?? [];
        return trim(($personal['first_name'] ?? '') . ' ' . ($personal['middle_name'] ?? '') . ' ' . ($personal['last_name'] ?? ''));
    }

    public function getEmailAttribute()
    {
        return $this->personal_info['email'] ?? '';
    }

    public function getPhoneAttribute()
    {
        return $this->personal_info['phone_number'] ?? '';
    }

    public function getStateAttribute()
    {
        return $this->personal_info['state_of_origin'] ?? '';
    }

    public function getGenderAttribute()
    {
        return $this->personal_info['gender'] ?? '';
    }

    public function getQualificationAttribute()
    {
        return $this->academic_info['highest_qualification'] ?? '';
    }

    public static function generateApplicationNumber()
    {
        $prefix = Setting::get('application_prefix', 'APP');
        $year = date('Y');

        // Try multiple times to get a unique number
        for ($attempt = 1; $attempt <= 10; $attempt++) {
            $lastApplication = self::where('application_number', 'like', "{$prefix}-{$year}-%")
                ->orderBy('application_number', 'desc')
                ->first();

            if ($lastApplication) {
                $lastNumber = (int) substr($lastApplication->application_number, -6);
                $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '000001';
            }

            $newAppNumber = "{$prefix}-{$year}-{$newNumber}";

            // Check if this number already exists
            $exists = self::where('application_number', $newAppNumber)->exists();
            if (!$exists) {
                return $newAppNumber;
            }

            // Small delay if colliding (unlikely)
            if ($attempt < 10) {
                usleep(1000); // 1ms delay
            }
        }

        // Final fallback: use timestamp + random
        return "{$prefix}-{$year}-" . str_pad(date('His') . rand(10, 99), 8, '0', STR_PAD_LEFT);
    }
}