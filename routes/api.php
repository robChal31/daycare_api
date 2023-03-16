<?php

use App\Http\Controllers\ApiControllers\AuthController;
use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\ApiControllers\UserController;
use \App\Http\Controllers\ApiControllers\ChildrenController;
use \App\Http\Controllers\ApiControllers\MerchantController;
use \App\Http\Controllers\ApiControllers\BookmarkController;
use App\Http\Controllers\ApiControllers\EmployeController;
use App\Http\Controllers\ApiControllers\OrderController;
use \App\Http\Controllers\ApiControllers\PositionController;
use App\Http\Controllers\ApiControllers\RatingController;
use App\Http\Controllers\ApiControllers\ServiceController;
use App\Http\Controllers\ApiControllers\ServiceHeadersController;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
  
    Route::get('login', [AuthController::class, 'loginView'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('user/register', [UserController::class, 'store']);
    Route::post('merchant/register', [MerchantController::class, 'store']);

    Route::middleware(['auth:sanctum'])->group(function () {

        Route::post('logout', [AuthController::class, 'logout']);

        //User routes
        Route::prefix('user')->middleware(['ability:user', 'verified'])->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::get('/show/{id}', [UserController::class, 'show']);
            Route::put('/update/{id}', [UserController::class, 'update']);
            Route::delete('/delete/{id}', [UserController::class, 'delete']);

            //user ratings
            Route::post('addRating', [RatingController::class, 'store']);

            //user orders
            Route::post('makeOrder', [OrderController::class, 'store']);
            Route::get('getMyOrder', [OrderController::class, 'getMyOrder']);
        });

        // Merchant routes
        Route::prefix('merchant')->middleware(['ability:merchant'])->group(function () {
            Route::get('show/{id}', [MerchantController::class, 'show']);
            Route::put('update/{id}', [MerchantController::class, 'update']);
            Route::delete('delete/{id}', [MerchantController::class, 'delete']);

            //Merchant Employe Route
            Route::post('addEmploye', [EmployeController::class, 'store']);
            Route::get('getEmployes/{id}', [EmployeController::class, 'getEmployes']);
            Route::delete('deleteEmploye/{id}', [MerchantController::class, 'delete']);
            Route::put('updateEmploye/{id}', [EmployeController::class, 'update']);

            //service
            Route::post('addService', [ServiceController::class, 'store']);
            Route::put('updateService/{id}', [ServiceController::class, 'update']);
            Route::delete('delete/{id}', [ServiceController::class, 'delete']);
        });
    
        Route::prefix('children')->group(function () {
            Route::get('/', [ChildrenController::class, 'index']);
            Route::get('getChildren/{id}', [ChildrenController::class, 'getChildren']);
            Route::get('getChild/{id}', [ChildrenController::class, 'getChild']);
            Route::post('store', [ChildrenController::class, 'store']);
            Route::put('update/{id}', [ChildrenController::class, 'update']);
            Route::delete('delete/{id}', [ChildrenController::class, 'delete']);
        });

        Route::prefix('bookmark')->group(function () {
            Route::get('getBookmarks/{id}', [BookmarkController::class, 'getBookmarks']);
            Route::post('store', [BookmarkController::class, 'store']);
        });

        Route::prefix('position')->group(function () {
            Route::get('/', [PositionController::class, 'index']);
            Route::post('store', [PositionController::class, 'store']);
            Route::put('update/{id}', [PositionController::class, 'update']);
            Route::delete('delete/{id}', [PositionController::class, 'delete']);
        });
        
        Route::prefix('employe')->middleware(['ability:employe', 'ability:merchant'])->group(function () {
            Route::get('getEmploye/{id}', [EmployeController::class, 'show']);
            // Route::post('store', [PositionController::class, 'store']);
            // Route::put('update/{id}', [PositionController::class, 'update']);
            // Route::delete('delete/{id}', [PositionController::class, 'delete']);
        });
        
        Route::prefix('serviceHeaders')->group(function () {
            Route::get('/', [ServiceHeadersController::class, 'index']);
            Route::get('show/{id}', [ServiceHeadersController::class, 'show']);
            Route::post('store', [ServiceHeadersController::class, 'store']);
            Route::put('update/{id}', [ServiceHeadersController::class, 'update']);
            Route::delete('delete/{id}', [ServiceHeadersController::class, 'delete']);
        });

        Route::prefix('service')->group(function() {
            Route::get('/', [ServiceController::class, 'index']);
            Route::get('show/{id}', [ServiceController::class, 'show']);
        });

        Route::prefix('rating')->group(function() {
            Route::get('/', [RatingController::class, 'index']);
            Route::get('show/{id}', [RatingController::class, 'show']);
        });

        Route::prefix('order')->group(function() {
            Route::get('/', [OrderController::class, 'index']);
            Route::get('show/{id}', [OrderController::class, 'show']);
        });

    });

});

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {

    // Hash the `hashFromUrl` parameter using the `hash` function
    try {
        $user = User::find($id);
        $user->email_verified_at = now();
        $user->save();
        return response()->json(["status" => true, "msg" => "email verified"]);
    } catch (\Throwable $th) {
        throw ValidationException::withMessages([
            "status" => false, 
            "msg" => "E-mail verification failed",
        ]);
    }

})->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
 
    return 'Verification link sent!';
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


