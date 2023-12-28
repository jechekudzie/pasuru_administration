<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

//admin routes
Route::get('/administration/home', [AdminController::class, 'index'])->name('admin.home');
Route::get('/administration/organisations/create', [AdminController::class, 'create'])->name('admin.organisations.create');
Route::get('/administration/organisations/manage', [AdminController::class, 'manage'])->name('admin.organisations.manage');
Route::get('/administration/organisations/template', [AdminController::class, 'template'])->name('admin.organisations.template');

//post routes
Route::post('/administration/templates/store', [AdminController::class, 'storeTemplate'])->name('admin.organisations.saveTemplate');
Route::post('/administration/organisations/instances/store', [AdminController::class, 'addNewOrganisation'])->name('admin.organisations.saveInstance');
Route::post('/administration/organisations/role', [AdminController::class, 'addOrganisationRole'])->name('admin.organisations.saveRole');
Route::post('/administration/organisations/users', [AdminController::class, 'addOrganisationUser'])->name('admin.organisations.saveUser');
