<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Spatie\Translatable\HasTranslations;

class Room extends Model
{
    use HasFactory;
    //use HasTranslations;

    protected $fillable = [

    ];

    protected $allowedFilters = [

    ];

    protected $allowedSorts = [

    ];

    protected $hidden = [

    ];

    protected $casts = [

    ];

    public $translatable = [

    ];

    ########################RELATIONS########################
    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function chats() {
        return $this->hasMany(Chat::class);
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }

    public function users() {
        return $this->belongsToMany(User::class);
    }
    ########################ACCESSORS########################

    ########################MUTATORS########################
}
