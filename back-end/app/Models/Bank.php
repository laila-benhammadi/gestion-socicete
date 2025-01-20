<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    public function employee()
    {
        return $this->belongsTo(User::class);
    }

    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }

}
