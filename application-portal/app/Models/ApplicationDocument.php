<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationDocument extends Model
{
    protected $fillable = [
        'application_id',
        'document_type',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function getUrlAttribute()
    {
        return route('admin.documents.download', $this->id);
    }
}