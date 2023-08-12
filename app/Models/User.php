<?php
namespace App\Models;

use App\Observers\UserObserver;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// sanctum
use Laravel\Sanctum\HasApiTokens;
use Spatie\Translatable\HasTranslations;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'identity_number',
        'hijri_date',
        'image',
        'reset_code',
        'verification_code',
        'phone_number',
        'email',
        'is_banned',
        'ban_reason',
        'wallet',
        'locale',
        'password',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'identity_number' => 'string',
        'hijri_date' => 'datetime',
    ];

    // protected static function boot()
    // {
    //     parent::boot();
    //     User::observe(UserObserver::class);
    // }

    ########################RELATIONS########################

        // rooms
        public function ownedRooms() {
            return $this->hasMany(Room::class, 'owner_id');
        }

        public function rooms() {
            return $this->belongsToMany(Room::class);
        }

        // chats
        public function chats() {
            return $this->hasMany(Chat::class, 'senders_id');
        }
        // messages
        public function messages() {
            return $this->hasMany(Message::class);
        }
        // rooms
    ########################ACCESSORS########################

    ########################MUTATORS########################
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    ########################RELATIONS########################
}
