<?php

use App\Http\Controllers\V1\AdminController;
use App\Http\Controllers\V1\GroupController;
use App\Http\Controllers\V1\PermissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/**
 * Admin Login Section
 */
Route::prefix("admin")->group(function(){
    Route::get('/login', [AdminController::class, "login"]);
    Route::post('/login', [AdminController::class, "login"]);
    Route::post('/forget-password', [AdminController::class, "forgetPassword"]);
    Route::post('/password-reset', [AdminController::class, "passwordReset"]);
});

/********************************************************************************
 * Protect the Route Throw Admin API Token
 * All Admin Routes are Here
 ********************************************************************************/

Route::middleware(["auth:admin"])->prefix('admin')->group(function(){

    /**
     * Admin Section
     */
    Route::get('/list',[AdminController::class,'index']);
    Route::get('/show', [AdminController::class, 'show']);
    Route::post('/store',[AdminController::class,'store']);
    Route::post('/update', [AdminController::class, 'update']);
    Route::post('/delete', [AdminController::class, 'destroy']);
    Route::post('/logout', [AdminController::class, "logout"]);

    /**
     * Group Section
     */
    Route::prefix("group")->group(function(){
        Route::get('/',[GroupController::class,'index']);
        Route::get('/show', [GroupController::class, 'show']);
        Route::post('/store',[GroupController::class,'store']);
        Route::post('/update',[GroupController::class,'update']);
        Route::post('/delete',[GroupController::class,'destroy']);
    });

    /**
     * Group Permission Section
     */
    Route::prefix('group/permission')->group(function(){
        Route::get('/list', [PermissionController::class, "permissionList"]);
        Route::post('/store', [PermissionController::class, "store"]);
        Route::get('/view', [PermissionController::class, "viewGroupPermission"]);
        Route::get('/user-access', [PermissionController::class, "userAccess"]);
    });
    
});
