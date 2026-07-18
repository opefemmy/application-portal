<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'is_read',
        'data',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array',
    ];

    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    public static function createNotification($type, $title, $message, $data = [])
    {
        return self::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'is_read' => false,
        ]);
    }
}