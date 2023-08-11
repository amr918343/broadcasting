<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Contact extends Model
{
    use HasFactory,  HasTranslations;

    protected $fillable = [
        'name',
        'user_type',
        'email',
        'phone',
        'message',
    ];
    public $translatable = [
        // 'name',
    ];
    protected $casts = [
        'name' => 'string',
        'user_type' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'message' => 'string',
        'created_at' => 'datetime:d-m-Y', // Change your format
        'updated_at' => 'datetime:d-m-Y',
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
