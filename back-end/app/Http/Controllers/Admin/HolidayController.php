<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;

use App\Models\Holiday;
use App\Models\User;
use App\Models\Experience;

class HolidayController extends Controller
{
    public function addHoliday(Request $request, $id)
    {
        $experience = Experience::findOrFail($id);
        $currentDate = today();

        $startDate = Carbon::parse($request->start_date);
        $endDate = $request->has('end_date') ? Carbon::parse($request->end_date) : null;

        if ($currentDate < $startDate) {
            $status = 'À venir';
        } elseif ($currentDate >= $startDate && (!$endDate || $currentDate <= $endDate)) {
            $status = 'En cours';
        } elseif ($endDate && $currentDate > $endDate) {
            $status = 'Terminé';
        } else {
            $status = 'Non défini';
        }

        $holiday = new Holiday();
        $holiday->type_vacancy = $request->type_vacancy;
        $holiday->number_days = $request->number_days;
        $holiday->description = $request->description;
        $holiday->start_date = $startDate;
        $holiday->end_date = $endDate;
        # $holiday->status = $status;
        $holiday->experience_id = $experience->id;

        $user = $experience->user;

        if ($request->hasFile('certificate')) {
            $folder = "certificates/{$user->last_name}_{$user->first_name}";
            $certificatePath = $request->file('certificate')->store($folder, 'public');
            $holiday->certificate = $certificatePath;
        }

        $holiday->save();

        return response()->json([
            'message' => 'Holiday created successfully.',
            'data' => $holiday,
        ], 201);
    }

    public function showHoliday($id)
    {
        $experience = Experience::findOrFail($id);
        $holidays = Holiday::where('experience_id', $experience->id)->get();
        $totalVacationDays = 0;

        // foreach ($holidays as $holiday) {
        //     if ($holiday->end_date) {
        //         $startDate = \Carbon\Carbon::parse($holiday->start_date);
        //         $endDate = \Carbon\Carbon::parse($holiday->end_date);
        //         $holiday->number_of_days = $startDate->diffInDays($endDate) + 1;
        //     } else {
        //         $holiday->number_of_days = \Carbon\Carbon::parse($holiday->start_date)->diffInDays(\Carbon\Carbon::now()) + 1;
        //     }
        //     $totalVacationDays += $holiday->number_of_days;
        // }

        $holidays->each(function ($holiday) {
            $holiday->append('status');
        });

        return response()->json([
            'holidays' => $holidays,
            // 'total_vacation_days' => $totalVacationDays,
        ]);
    }

    public function getHoliday($id)
    {
        $holiday = Holiday::findOrFail($id);
        return response()->json($holiday);
    }

    public function updateHoliday(Request $request, $id)
    {
        $holiday = Holiday::findOrFail($id);
        $currentDate = now();
        $startDate = Carbon::parse($request->start_date);
        $endDate = $request->has('end_date') ? Carbon::parse($request->end_date) : null;

        // if ($endDate) {
        //     $numberDays = $startDate->diffInDays($endDate) + 1;
        // } else {
        //     $numberDays = 1;
        // }

        if ($currentDate < $startDate) {
            $status = 'À venir';
        } elseif ($currentDate >= $startDate && (!$endDate || $currentDate <= $endDate)) {
            $status = 'En cours';
        } elseif ($endDate && $currentDate > $endDate) {
            $status = 'Terminé';
        } else {
            $status = 'Non défini';
        }

        $holiday->type_vacancy = $request->type_vacancy;
        $holiday->start_date = $startDate;
        $holiday->number_days = $request->number_days;
        $holiday->description = $request->description;
        $holiday->end_date = $endDate;

        $user = $holiday->user;

        if ($request->hasFile('certificate')) {
            $folder = "certificates/{$user->last_name}_{$user->first_name}";
            $certificatePath = $request->file('certificate')->store($folder, 'public');
            $holiday->certificate = $certificatePath;
        }

        $holiday->save();

        return response()->json([
            'message' => 'Congé mis à jour avec succès.',
            'data' => $holiday,
        ], 200);
    }

    public function getEmployeesOnHoliday()
    {
        $now = Carbon::now();

        $users = User::where('is_admin', '!=', 1)
            ->with(['experiences' => function ($q) use ($now) {
                $q->where(function ($q2) use ($now) {
                    $q2->whereDate('end_date', '>=', $now)
                       ->orWhereNull('end_date');
                })
                ->with(['holidays']);
            }])
            ->get();

        $usersOnHoliday = $users->filter(function ($user) {
            return $user->experiences->flatMap->holidays
                        ->contains(fn($holiday) => $holiday->status === 'En cours');
        });

        return $usersOnHoliday->values();
    }

    public function countEmployeesOnHoliday()
    {
        $holidays = Holiday::with('experience')->get();

        $holidayCount = $holidays->filter(function ($holiday) {
            return $holiday->status === 'En cours';
        })->count();

        return response()->json([
            'message' => 'Number of employees currently on holiday retrieved successfully.',
            'data' => $holidayCount,
        ]);
    }

    public function calculateHolidayDays($id)
    {
        $experience = Experience::where('id', $id)->first();
        $totalHolidayDays = 0;
        $startDate = Carbon::parse($experience->start_date);
        $endDate = $experience->end_date ? Carbon::parse($experience->end_date) : now();
        $months = $startDate->diffInMonths($endDate);
        $totalHolidayDays += $months * 1.5;

        return response()->json([
            'message' => 'Calcul des jours avec succès.',
            'total_holiday_days' => $totalHolidayDays,
        ], 200);
    }

    public function calculateTotalHolidayDays($id)
    {
        $experience = Experience::with(['holidays' => function ($query) {
            $query->where('type_vacancy', 'Congé annuel');
        }])->findOrFail($id);

        $totalDays = $experience->holidays->sum('number_days');

        return response()->json([
            'message' => 'Total des jours de congé calculé avec succès.',
            'total_days' => $totalDays,
        ], 200);
    }

    public function calculateRemainingHolidayDays($id)
    {
        $holidayDaysResponse = $this->calculateHolidayDays($id);
        $totalHolidayDays = $holidayDaysResponse->getData()->total_holiday_days;

        $holidayTotalResponse = $this->calculateTotalHolidayDays($id);
        $totalDaysUsed = $holidayTotalResponse->getData()->total_days;

        $remainingDays = $totalHolidayDays - $totalDaysUsed;

        return response()->json([
            'message' => 'Calcul des jours restants effectué avec succès.',
            'remaining_days' => $remainingDays,
        ], 200);
    }
}
