<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Storage;  // Ensure Storage is imported
use stdClass;
use App\Models\User;
use App\Models\Experience;
use Illuminate\Support\Facades\Log;
use App\Notifications\EmployeeCreated; // Importation de la notification.

use App\Mail\SendPasswordEmail;
use Illuminate\Support\Facades\Mail;


class EmployeeController extends Controller
{
    public function getEmployee(){
        $employees = User::where('is_admin', false)->get();
        return response()->json($employees);
    }


    // public function getEmployeeDetails($id)
    // {
    //     $employee = User::find($id);        
    //     if (!$employee) {
    //         return response()->json(['error' => 'Employé non trouvé'], 404);
    //     }
    //     return response()->json($employee);
    // }


    public function getEmployeeDetails($id)
    {
        $employee = User::with('experiences')->findOrFail($id);
        $employee->work_files = json_decode($employee->work_files);

        return response()->json([
            'employee' => $employee,
            'experiences' => $employee->experiences,
        ]);
    }


    public function save_file($name, $file)
{
     $filename = time() . '_' . $file->getClientOriginalName();
    return Storage::disk('public')->putFileAs("/users_files/$name", $file, $filename);
}

public function save_files($data, $name)
{
    $files = [];
    foreach ($data as $d) {
        $file = $this->save_file($name, $d);
        array_push($files, $file);
    }
    return $files;
}

public function addEmployee(Request $request)
{
     $request->validate([
        // 'email' => 'required|email|ends_with:@societe.com',
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'personal_email' => 'required|email',
        'email' => 'required|email',
        'date_of_birth' => 'required|date',
        'city' => 'required|string',
        'nationality' => 'required|string',
        'gender' => 'required|string',
        'family_situation' => 'required|string',
        'cin' => 'required|string',
        'hiring_date' => 'required|date',
        'address' => 'required|string',
        'contry' => 'required|string',
        'phone_number' => 'required|string',
        'picture' => 'nullable|file|image',  
        'diplomas' => 'nullable|array',  
        'cin_file' => 'nullable|file|mimes:jpeg,png,pdf|max:5120',
        'cv_file' => 'nullable|file|mimes:pdf|max:5120',
        'document_file' => 'nullable|file|mimes:pdf|max:5120',
    ]);
    
     $user = new User();
    $user->first_name = $request->first_name;
    $user->last_name = $request->last_name;
    $user->personal_email = $request->personal_email;
    $user->date_of_birth = $request->date_of_birth;
    $user->city = $request->city;
    $user->nationality = $request->nationality;
    $user->gender = $request->gender;
    $user->family_situation = $request->family_situation;
    $user->cin = $request->cin;
    $user->hiring_date = $request->hiring_date;
    $user->address = $request->address;
    $user->contry = $request->contry;
    $user->email = $request->email;
    $user->phone_number = $request->phone_number;
    // $user->password = Hash::make(Str::random(12));

    $rawPassword = Str::random(12);
    $user->password = Hash::make($rawPassword);
    $user->user_folder = $user->first_name . $user->last_name;

     $work_file = new stdClass();

     if ($request->hasFile('picture')) {
        $work_file->picture = $this->save_file($user->user_folder, $request->file('picture'));
    }

     if ($request->hasFile('diplomas')) {
        $work_file->diplomas = $this->save_files($request->file('diplomas'), $user->user_folder);
    }

     if ($request->hasFile('cin_file')) {
        $work_file->cin_file = $this->save_file($user->user_folder, $request->file('cin_file'));
    }

     if ($request->hasFile('cv_file')) {
        $work_file->cv_file = $this->save_file($user->user_folder, $request->file('cv_file'));
    }

     if ($request->hasFile('document_file')) {
        $work_file->document_file = $this->save_file($user->user_folder, $request->file('document_file'));
    }

     $user->work_files = json_encode($work_file);  

     $user->save();
      $user->notify(new EmployeeCreated($user->first_name, $rawPassword));

 
 
    return response()->json([
        'message' => 'Employé ajouté avec succès',
        'user' => $user,
    ]
);
}
 
  

    
    public function getEmployeeActif()
    {
         $employees = User::with(['experiences' => function ($query) {
            $query->whereNull('end_date');  
        }])->count();
    
         return response()->json([
            'message' => 'Employés actifs récupérés avec succès',
            'data' => $employees,
        ]);
    }

    public function countStagiaires()
    {

         $expereince = Experience::with('user')->where('type_contract', 'stage') ->count();

    
         return response()->json([
            'message' => 'Staagiaire récupérés avec succès',
            'data' => $expereince,
        ]);
    }
    
    
     

}
