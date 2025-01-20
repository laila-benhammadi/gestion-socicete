<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;  
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;  // Ensure Storage is imported
use stdClass;

class UserController extends Controller
{
    public function save_file($name, $file)
    {
        return Storage::disk('public')->putFile("/users_files/$name", $file);
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

    public function register(Request $request){
        $user = new User();

        $user->gender = $request->gender;
        $user->city = $request->city;
        $user->pro_email = $request->pro_email;
        $user->family_situation = $request->family_situation;
        $user->contry = $request->contry;
        $user->nationality = $request->nationality;


        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->cin = $request->cin;
        $user->date_of_birth = $request->date_of_birth;
        $user->hiring_date = $request->hiring_date;
        $user->password = Hash::make(Str::random(12));
        $user->user_folder = $user->first_name . $user->last_name;
        $work_file = new stdClass();
        if ($request->hasFile('picture'))
            $work_file->picture  = $this->save_file($user->user_folder, $request->picture);
        if ($request->hasFile('diplomas')) 
            $work_file->diplomas =  $this->save_files($request->diplomas, $user->user_folder);
        $user->work_files = $work_file;
        $user->save();

        return response()->json([
            'message'=>'User ajoute avec succes',
            'user'=>$user,
        ]);
    }
}
