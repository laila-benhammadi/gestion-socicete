<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Relations
    public function banks()
    {
        return $this->hasMany(Bank::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }

    // Vérification admin
    public function isAdmin()
    {
        return $this->role === false; // Adjust the logic as needed
    }

    // Attributs remplissables
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Attributs cachés pour la sérialisation
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Attributs avec casts
    protected $casts = [
        'email_verified_at' => 'datetime',
        'work_files' => 'array', // Automatically converts the JSON back into an array
    ];
}
