<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Experience;
use App\Models\Salary;
use App\Models\Bank;

class SalaryController extends Controller
{
    public function addSalary(Request $request, $experience_id)
    {
        $experience = Experience::findOrFail($experience_id);

        $netAmount = $request->net_amount;
        $additionalGrossAmount = $request->gross_amount ?? 0;
        $userId = $request->user_id;

        // Calculer le montant brut
        $grossAmount = $netAmount + $additionalGrossAmount;

        // Créer une nouvelle instance de Salary
        $salary = new Salary();
        $salary->bank_id = $request->bank_id;
        $salary->experience_id = $experience->id;
        $salary->user_id = $userId;
        $salary->salary_date = $request->salary_date;
        $salary->gross_amount = $grossAmount;
        $salary->net_amount = $netAmount;

        $salary->save();

        return response()->json([
            'message' => 'Salaire ajouté avec succès.',
            'salary' => $salary,
        ], 201);
    }

    public function getSalariesByEmployee($user_id)
    {
        $salaries = Salary::where('user_id', $user_id)
            ->with('bank') // Assurez-vous que 'bank' est une relation définie dans le modèle Salary
            ->get();

        // if ($salaries->isEmpty()) {
        //     return response()->json([
        //         'message' => 'Aucun salaire trouvé pour cet employé.',
        //     ], 404);
        // }

        return response()->json([
            'message' => 'Salaires récupérés avec succès.',
            'salaries' => $salaries,
        ]);
    }

    public function updateSalary(Request $request, $salary_id)
    {
        $salary = Salary::findOrFail($salary_id);

        $netAmount = $request->net_amount;
        $additionalGrossAmount = $request->gross_amount ?? 0;
        $grossAmount = $netAmount + $additionalGrossAmount;

        $salary->bank_id = $request->bank_id;
        $salary->net_amount = $netAmount;
        $salary->gross_amount = $grossAmount;
        $salary->salary_date = $request->salary_date;

        $salary->save();

        return response()->json([
            'message' => 'Salaire mis à jour avec succès.',
            'salary' => $salary,
        ]);
    }
}
