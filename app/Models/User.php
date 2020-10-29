<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cookie;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
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
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!$model->ref_code)
                $model->ref_code = static::makeRefCode();
        });

        static::creating(function ($model) {
            if ($ref=static::getRef()) {
                $referrer = User::where(['ref_code'=>$ref])->first();
                if ($referrer) {
                    $model->referrer_id = $referrer->id;
                }
            }
        });
    }

    private static function getRef()
    {
        $ref = session()->get('ref');
        if (!$ref) {
            $ref = Cookie::get('ref');
        }
        return $ref;
    }

    public static function makeRefCode($counter = 0)
    {
        $symbols = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFJHIJKLMNOPQRSTUVWXYZ';
        $lastSymbolPos = strlen($symbols)-1;
        $refCode = '';

        for ($i=0;$i<10;$i++)
        {
            $refCode .= $symbols[rand(0,$lastSymbolPos)];
        }

        if ($counter < 10 && User::where(['ref_code'=>$refCode])->exists()) {
            $counter++;
            return static::makeRefCode($counter);
        }

        return $refCode;
    }

    public function getReferrals()
    {
        return $this::where(['referrer_id'=>$this->id])->get();
    }

    public function setPreferredLanguage($lang)
    {
        $this->preferred_language = $lang;
        $this->save();
    }
}
