<?php

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/administration/organisations/templates', [ApiController::class, 'fetchTemplate'])->name('admin.organisations.create');
Route::get('/administration/organisations/instances', [ApiController::class, 'fetchOrganisationInstances'])->name('admin.organisations.fetchInstances');


Route::get('/administration/roles/{id}/{type}', [App\Http\Controllers\API\ApiController::class, 'fetchOrganisationRoles']);
Route::get('/administration/users/{id}/{type}', [App\Http\Controllers\API\ApiController::class, 'fetchOrganisationUsers']);
Route::get('/administration/roles/{id}', [App\Http\Controllers\API\ApiController::class, 'fetchRole']);
Route::get('/administration/permissions/{id}', [App\Http\Controllers\API\ApiController::class, 'fetchRolePermissions']);
Route::post('/administration/permissions/update', [App\Http\Controllers\API\ApiController::class, 'updateRolePermissions']);
