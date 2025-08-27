<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Experience;
use App\Models\User;

class ExperienceController extends Controller
{
    public function addExperience(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);
        $experience = new Experience();
        $experience->job_title = $request->job_title;
        $experience->type_contract = $request->type_contract;
        $experience->start_date = $request->start_date;
        $experience->end_date = $request->end_date;
        $experience->user_id = $user_id;
        $experience->save();

        return response()->json([
            'message' => 'Experience ajouté avec succees',
            'experience' => $experience,
        ]);
    }

    public function showExperience($user_id)
    {
        $user = User::findOrFail($user_id);
        $experiences = Experience::where('user_id', $user->id)->get();
        return response()->json($experiences);
    }

    public function updateExperience(Request $request, $experience_id)
    {
        $experience = Experience::findOrFail($experience_id);
        $experience->job_title = $request->job_title;
        $experience->type_contract = $request->type_contract;
        $experience->start_date = $request->start_date;
        $experience->end_date = $request->end_date;
        $experience->save();

        return response()->json([
            'message' => 'Experience mise à jour avec succès',
            'experience' => $experience,
        ]);
    }

    public function getExperience($id)
    {
        $experience = Experience::findOrFail($id);
        return response()->json($experience);
    }
}
