<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\CertificateController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\GroupController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\VendorContactPeopleController;
use App\Http\Controllers\Backend\VendorController;
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
    Route::get('/login', [AdminController::class, "showLogin"]);
    Route::post('/login', [AdminController::class, "login"]);
    Route::post('/forget-password', [AdminController::class, "forgetPassword"]);
    Route::post('/password-reset', [AdminController::class, "passwordReset"]);
});

    /**
     * Vendor Section
     */
    Route::prefix('vendor')->group(function(){
        Route::get('/show',   [VendorController::class, "show"]);
        Route::post('/store', [VendorController::class, "store"]);
        Route::post('/update/{id}', [VendorController::class, "update"]);
        Route::post('/delete/{id}', [VendorController::class, "destroy"]);
    });

    /**
     * Customer Section
     */
    Route::prefix('customer')->group(function(){
        Route::get('/show',         [CustomerController::class, "show"]);
        Route::post('/store',       [CustomerController::class, "store"]);
        Route::post('/update/{id}', [CustomerController::class, "update"]);
        Route::post('/delete/{id}', [CustomerController::class, "destroy"]);
    });

    /**
     *Vendor Certificate Section
     */
    Route::prefix('vendor_certificate')->group(function(){
        Route::get('/show',         [CertificateController::class, "show"]);
        Route::post('/store',       [CertificateController::class, "store"]);
        Route::post('/update/{id}', [CertificateController::class, "update"]);
        Route::post('/delete/{id}', [CertificateController::class, "destroy"]);
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
