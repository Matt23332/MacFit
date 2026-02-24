<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\BundleController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerifyEmailController;
use App\Http\Controllers\ResendEmailVerificationController;

// Public routes - Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
    ->name('verification.verify')
    ->middleware(['signed', 'throttle:6,1']);
Route::post('/email/resend', [ResendEmailVerificationController::class, 'resend'])
    ->name('verification.resend')
    ->middleware('throttle:6,1');


// Protected routes - Require authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Role routes
    Route::post('/saveRole', [RoleController::class, 'createRole']);
    Route::get('/getRoles', [RoleController::class, 'readAll']);
    Route::get('/getRole/{id}', [RoleController::class, 'readRole']);
    Route::put('updateRole/{id}', [RoleController::class, 'updateRole']);
    Route::delete('deleteRole/{id}', [RoleController::class, 'deleteRole']);

    // Category routes
    Route::post('/saveCategory', [CategoryController::class, 'createCategory']);
    Route::get('/getCategories', [CategoryController::class, 'readAll']);
    Route::get('/getCategory/{id}', [CategoryController::class, 'readCategory']);
    Route::put('updateCategory/{id}', [CategoryController::class, 'updateCategory']);
    Route::delete('deleteCategory/{id}', [CategoryController::class, 'deleteCategory']);

    // Gym routes
    Route::post('/saveGym', [GymController::class, 'createGym']);
    Route::get('/getGyms', [GymController::class, 'readAll']);
    Route::get('/getGym/{id}', [GymController::class, 'readGym']);
    Route::put('updateGym/{id}', [GymController::class, 'updateGym']);
    Route::delete('deleteGym/{id}', [GymController::class, 'deleteGym']);

    // Bundle routes
    Route::post('/saveBundle', [BundleController::class, 'createBundle']);
    Route::get('/getBundles', [BundleController::class, 'readAll']);
    Route::get('/getBundle/{id}', [BundleController::class, 'readBundle']);
    Route::put('updateBundle/{id}', [BundleController::class, 'updateBundle']);
    Route::delete('deleteBundle/{id}', [BundleController::class, 'deleteBundle']);

    // Equipment routes
    Route::post('/saveEquipment', [EquipmentController::class, 'createEquipment']);
    Route::get('/getEquipment', [EquipmentController::class, 'readAll']);
    Route::get('/getEquipment/{id}', [EquipmentController::class, 'readEquipment']);
    Route::put('updateEquipment/{id}', [EquipmentController::class, 'updateEquipment']);
    Route::delete('deleteEquipment/{id}', [EquipmentController::class, 'deleteEquipment']);

    // Subscription routes
    Route::post('/saveSubscription', [SubscriptionController::class, 'createSubscription']);
    Route::get('/getSubscriptions', [SubscriptionController::class, 'readAll']);
    Route::get('/getSubscription/{id}', [SubscriptionController::class, 'readSubscription']);
    Route::put('updateSubscription/{id}', [SubscriptionController::class, 'updateSubscription']);
    Route::delete('deleteSubscription/{id}', [SubscriptionController::class, 'deleteSubscription']);
});
