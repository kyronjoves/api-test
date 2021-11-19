<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\ClientController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'jwt.verify'], function(){
    Route::prefix('admin')->group(function () {
        Route::post('invite', [AdminController::class, 'invite']);
    });
    Route::prefix('client')->group(function () {

    });
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::post('/login', [AuthController::class, 'login']);

/* Can be used if no seeder is created for admin */
Route::post('/register', [AuthController::class, 'register']);

/* For client registration */
Route::post('client/register', [ClientController::class, 'register']);
