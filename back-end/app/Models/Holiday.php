<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type_vacancy',
        'statutes',
        'start_date',
        'end_date',
        'certificate',
    ];

    protected $appends = ['status'];

    // Relations
    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor pour le status
    public function getStatusAttribute()
    {
        $currentDate = Carbon::today();
        $startDate = Carbon::parse($this->start_date)->startOfDay();
        $endDate = $this->end_date ? Carbon::parse($this->end_date)->endOfDay() : null;

        if ($currentDate->lt($startDate)) {
            return 'À venir';
        } elseif ($currentDate->gte($startDate) && (!$endDate || $currentDate->lte($endDate))) {
            return 'En cours';
        } elseif ($endDate && $currentDate->gt($endDate)) {
            return 'Terminé';
        } else {
            return 'Non défini';
        }
    }
}
