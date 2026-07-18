<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'subject',
        'body',
        'variables',
    ];

    protected $casts = [
        'variables' => 'array',
    ];

    public static function getTemplate($slug)
    {
        return self::where('slug', $slug)->first();
    }

    public function render($data)
    {
        $subject = $this->subject;
        $body = $this->body;

        foreach ($this->variables ?? [] as $variable) {
            if (isset($data[$variable])) {
                $subject = str_replace('{{' . $variable . '}}', $data[$variable], $subject);
                $body = str_replace('{{' . $variable . '}}', $data[$variable], $body);
            }
        }

        return ['subject' => $subject, 'body' => $body];
    }
}