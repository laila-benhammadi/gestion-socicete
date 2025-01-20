<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    public function holidays()
    {
        return $this->hasMany(Holiday::class);  
    }
    
    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }
        
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
