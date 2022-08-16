<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Property;
use App\Models\AlertUser;
use App\Models\OAuthProvider;
use Auth;
use Hash;

class User extends Authenticatable implements JWTSubject //, MustVerifyEmail
{
    use Notifiable,
        HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'image',
        'firstname',
        'lastname',
        'newsletter',
        'provider',
        'provider_id',
        'role'
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
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'photo_url',
    ];

    /**
     * Get the profile photo URL attribute.
     *
     * @return string
     */
    public function getPhotoUrlAttribute()
    {
        return vsprintf('https://www.gravatar.com/avatar/%s.jpg?s=200&d=%s', [
            md5(strtolower($this->email)),
            $this->name ? urlencode("https://ui-avatars.com/api/$this->name") : 'mp',
        ]);
    }

    /**
     * Get the firstname attribute.
     *
     * @return string
     */
    public function getFirstnameAttribute()
    {
        $name =  explode(" ", $this->name);

        return $name[0] ?? '';
    }

    /**
     * Get the lastname attribute.
     *
     * @return string
     */
    public function getLastnameAttribute()
    {
        $name =  explode(" ", $this->name);

        return $name[1] ?? '';
    }

    /**
     * Get the oauth providers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function oauthProviders()
    {
        return $this->hasMany(OAuthProvider::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * @return int
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function favorites()
    {
        return $this->belongsToMany(Property::class, 'favorites', 'user_id', 'property_id')->withTimeStamps();
    }

    public function getProviderAttribute()
    {
        return OAuthProvider::where('user_id', Auth::user()->id)->first();
    }

    public function setPasswordAttribute($password){
        $this->attributes['password'] = Hash::make($password);
    }

    public function getImageAttribute($value)
    {
        return $value !== null ? asset('uploads/'.$value) : null;
    }

}
