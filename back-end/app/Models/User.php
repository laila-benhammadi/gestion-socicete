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
 

    public function banks()
    {
        return $this->hasMany(Bank::class);
    }

    public function experiences(){
        return $this->hasMany(Experience::class);
    }
    
   
    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }
    






    protected $fillable = [
        'name',
        'email',
        'password',
    ];






    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'work_files' => 'array', // Automatically converts the JSON back into an array
    ];

  
    
}
