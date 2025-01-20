<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bank;
use Illuminate\Http\Request;


class BankController extends Controller
{
    public function addBank(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);  
        $bank = new Bank();
        $bank->bank_name = $request->bank_name;
        $bank->rib = $request->rib;
        $bank->user_id = $user->id;  
        $bank->save();
    
        return response()->json([
            'message' => 'Bank added successfully!',
            'bank' => $bank
        ]);
    }


    
    public function showBank($user_id)
{
    $user = User::findOrFail($user_id);
    $banks = Bank::where('user_id', $user_id)->get();
    // if ($banks->isEmpty()) {
    //     return response()->json(['message' => 'No banks found for this user'], 404);
    // }
    return response()->json($banks);
}


public function update(Request $request, $id)
{
     $bank = Bank::findOrFail($id);

     $bank->bank_name = $request->bank_name;
     $bank->rib = $request->rib;
     $bank->save();

     return response()->json($bank);
}


}
