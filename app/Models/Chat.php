<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Spatie\Translatable\HasTranslations;

class Chat extends Model
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
    public function messages() {
        return $this->hasMany(Message::class);
    }

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function reciever() {
        return $this->belongsTo(User::class, 'reciever_id');
    }


    public function room() {
        return $this->belongsTo(Room::class, 'room_id');
    }

    ########################ACCESSORS########################

    ########################MUTATORS########################
}
