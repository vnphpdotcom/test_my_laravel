<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';
    protected $fillable = [
        'name', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'level', 'remember_token',
    ];

    protected static function getPrice($user_id)
    {
        return User::select('price')->where(['id'=>$user_id])->first()->price;
    }

    protected static function updatePrice($value,$user_id)
    {
        return DB::table('users')->where(['id'=>$user_id])->update(['price'=>$value]);
    }
}
