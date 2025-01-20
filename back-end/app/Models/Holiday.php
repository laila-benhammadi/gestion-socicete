<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory; protected $fillable = [
        'user_id',
        'type_vacancy',
        'statutes',
        'start_date',
        'end_date',
        'certificate',
    ];

    public function experiences(){
        return $this->belongsTo(Experience::class);
    }


    
    public function user(){
        return $this->belongsTo(User::class);
    }


}
