<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\Admin\SalaryController;


Route::get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/users/add', [UserController::class, 'register'])->name('users.add');
Route::post('admin/login',[AuthController::class,'loginAdmin']);

Route::get('/getuser', [AuthController::class, 'getUser']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::get('admin/getEmployee',[EmployeeController::class,'getEmployee']);
Route::post('/addEmployee', [EmployeeController::class, 'addEmployee']);
Route::get('/getEmployeeDetails/{userId}', [EmployeeController::class, 'getEmployeeDetails']);
Route::get('/getEmployeeActif', [EmployeeController::class,'getEmployeeActif']);
Route::get('/countStagiaires', [EmployeeController::class,'countStagiaires']);

 

Route::get('showBank/{employeeId}', [BankController::class, 'showBank']);
Route::put('updateBank/{id}', [BankController::class, 'update']);
Route::post('/addBank/{employeeId}', [BankController::class, 'addBank']);


Route::post('/addExperience/{user_id}', [ExperienceController::class, 'addExperience']);
Route::get('showExperience/{id}', [ExperienceController::class, 'showExperience']);
Route::put('/updateExperience/{experience_id}', [ExperienceController::class, 'updateExperience']);
Route::get('/getExperience/{id}', [ExperienceController::class, 'getExperience']);


Route::get('calculateHolidayDays/{id}', [HolidayController::class, 'calculateHolidayDays']);
Route::get('showHoliday/{employeeId}', [HolidayController::class, 'showHoliday']);
Route::post('addHoliday/{id}', [HolidayController::class, 'addHoliday']);
Route::get('/getHoliday/{id}', [HolidayController::class, 'getHoliday']);
Route::post('/updateHoliday/{id}', [HolidayController::class, 'updateHoliday']);
Route::get('/getEmployeesOnHoliday', [HolidayController::class,'getEmployeesOnHoliday']);
Route::get('/countEmployeesOnHoliday', [HolidayController::class,'countEmployeesOnHoliday']);
// Route::get('/calculateTotalHolidayDays/{id}', [HolidayController::class,'calculateTotalHolidayDays']);
Route::get('/calculateTotalHolidayDays/{id}', [HolidayController::class, 'calculateTotalHolidayDays']);
Route::get('/calculateRemainingHolidayDays/{id}', [HolidayController::class, 'calculateRemainingHolidayDays']);



Route::post('/experience/{experience_id}/salary', [SalaryController::class, 'addSalary']);
Route::get('/getSalaries/{user_id}', [SalaryController::class, 'getSalariesByEmployee']);
Route::put('/updateSalary/{salary_id}', [SalaryController::class, 'updateSalary']);



































// // Routes protégées par le middleware "auth:sanctum" ou "auth:api"
// Route::middleware('auth:sanctum')->group(function () {
//     // Route pour obtenir les informations de l'utilisateur connecté
//     Route::get('/user', [UserController::class, 'getUser']);

//     // Autres routes protégées ici
//     Route::get('/protected-route', function () {
//         return response()->json(['message' => 'You are authenticated!']);
//     });
// });