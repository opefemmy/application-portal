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

    public function getGradeClassAttribute()
    {
        return $this->academic_info['grade_class'] ?? '';
    }

    public function getGraduationYearAttribute()
    {
        return $this->academic_info['graduation_year'] ?? '';
    }

    public function getInstitutionAttendedAttribute()
    {
        return $this->academic_info['institution_attended'] ?? '';
    }

    public function getCourseStudiedAttribute()
    {
        return $this->academic_info['course_studied'] ?? '';
    }

    // Personal info accessors
    public function getDateOfBirthAttribute()
    {
        return $this->personal_info['date_of_birth'] ?? '';
    }

    public function getMaritalStatusAttribute()
    {
        return $this->personal_info['marital_status'] ?? '';
    }

    public function getNationalityAttribute()
    {
        return $this->personal_info['nationality'] ?? '';
    }

    public function getLocalGovernmentAttribute()
    {
        return $this->personal_info['local_government'] ?? '';
    }

    public function getResidentialAddressAttribute()
    {
        return $this->personal_info['residential_address'] ?? '';
    }

    public function getPostalAddressAttribute()
    {
        return $this->personal_info['postal_address'] ?? '';
    }

    public function getAlternativePhoneAttribute()
    {
        return $this->personal_info['alternative_phone'] ?? '';
    }

    public function getPositionApplyingForAttribute()
    {
        $details = $this->application_details ?? [];
        return $details['position_applying_for'] ?? $details['programme_applying_for'] ?? '';
    }

    public static function generateApplicationNumber()
    {
        $prefix = Setting::get('application_prefix', 'APP-CLG');

        // Generate unique number using timestamp + random (not sequential 1,2,3)
        $maxAttempts = 100;
        for ($i = 0; $i < $maxAttempts; $i++) {
            // Use timestamp (without dashes) + random 4-digit number for uniqueness
            $timestamp = date('ymdHis'); // 6-digit timestamp (YYMMDDHHMMSS)
            $random = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            $uniqueNum = $timestamp . $random;

            // Format: APP-CLG-2507211432561234 (prefix + unique number)
            // Or with year: APP-CLG-2026-2507211432561234
            $newAppNumber = $prefix . '-' . $uniqueNum;

            // Check if it exists in database
            $exists = self::where('application_number', $newAppNumber)->exists();

            if (!$exists) {
                return $newAppNumber;
            }

            // Small delay to get different timestamp on next attempt
            usleep(10000); // 10ms
        }

        // Ultimate fallback - use microtime for guaranteed uniqueness
        $micro = str_replace('.', '', microtime());
        return $prefix . '-' . $micro . rand(1000, 9999);
    }
}