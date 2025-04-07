<?php
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HumanResourcesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('password/reset/ajax', [UserController::class, 'resetPassword'])->name('password.reset.ajax');
    Route::post('/check-email', [UserController::class, 'checkEmail'])->name('check.email');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::prefix('hr')->name('hr.')->middleware(['auth'])->group(function() {
    Route::get('index', [HumanResourcesController::class, 'index'])->name('index');
});

require __DIR__.'/auth.php';
