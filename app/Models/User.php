<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'name',
    'lastname',
    'email',
    'password',
    'birthdate',
    'phone',
    'sex',
    'description',
    'photo',
    'address',
];

protected $hidden = [
    'password',
    'remember_token',
];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
    'photo' => 'string', // opcional
    'birthdate' => 'date',
];
}
