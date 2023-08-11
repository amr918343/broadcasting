<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Policy extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'title',
        'description',
    ];

    protected $allowedFilters = [
        'title',
        'description',
    ];

    protected $allowedSorts = [
        'title',
        'description',
    ];

    protected $hidden = [

    ];

    protected $casts = [
        'title' => 'string',
        'description' => 'string',
    ];

    public $translatable = [
        'title',
        'description',
    ];

    ########################RELATIONS########################

    ########################ACCESSORS########################

    ########################MUTATORS########################
}
