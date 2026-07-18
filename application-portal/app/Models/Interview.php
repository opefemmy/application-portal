<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = [
        'application_id',
        'venue',
        'interview_date',
        'interview_time',
        'panel',
        'meeting_link',
        'notes',
    ];

    protected $casts = [
        'interview_date' => 'date',
        'interview_time' => 'datetime:H:i',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
}