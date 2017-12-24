<?php

namespace App;

use Illuminate\Support\Str;
use App\Transformers\UserTransformer;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin'
    ];

    protected $dates = [
        'delete_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token'
    ];

    /**
     ** Transformers
     **/
    public $transformer = UserTransformer::class;

    /**
     ** Usuario verificado y admin
     **/
    const USER_VERIFIED = 1;
    const USER_NO_VERIFIED = 0;

    const USER_ADMIN = true;
    const USER_REGULAR = false;

    public function userVerified()
    {
        return $this->verified === User::USER_VERIFIED;
    }

    public function userAdmin()
    {
        return $this->admin === User::USER_ADMIN;
    }

    /**
     ** Token estatico sin instancia de usuario
     **/
    public static function generateVerificationToken()
    {
        return str_random(40);
    }

    /**
     ** Mutadores: antes de que se guarde en la base de datos
     **/
    public function setNameAttribute($valor)
    {
    	$this->attributes['name'] = Str::lower($valor);
    }

    public function setEmailAttribute($valor)
    {
    	$this->attributes['email'] = Str::lower($valor);
    }

    /**
     ** Accesores: al obtenerlos de la base de datos
     **/
    public function getNameAttribute($valor)
    {
    	return ucwords(str_replace(['-', '_'], ' ', $valor));
    }

}
