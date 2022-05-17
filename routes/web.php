<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Guest\CategoryController as GuestCategoryController;
use App\Http\Controllers\Guest\MenuController as GuestMenuController;
use App\Http\Controllers\Guest\ReservationController as GuestReservationController;
use App\Http\Controllers\Guest\WelcomeController;
use Illuminate\Support\Facades\Route;


Route::get('/', [WelcomeController::class,'index']);
Route::get('/thank-you', [WelcomeController::class,'thankYou'])->name('thank.you');

// Guests Routes
Route::get('/categories',[GuestCategoryController::class,'index'])->name('categories.index');
Route::get('/categories/{id}',[GuestCategoryController::class,'show'])->name('categories.show');
Route::get('/menus',[GuestMenuController::class,'index'])->name('menus.index');

Route::get('reservation/step-one',[GuestReservationController::class,'stepOne'])->name('reservations.step.one');
Route::get('reservation/step-two',[GuestReservationController::class,'stepTwo'])->name('reservations.step.two');
Route::post('reservation/step-one',[GuestReservationController::class,'stepOneStore'])->name('reservations.store.step.one');
Route::post('reservation/step-two',[GuestReservationController::class,'stepTwoStore'])->name('reservations.store.step.two');



// Admin Routes
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth', 'admin')->name('admin.')->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::resource('/categories',CategoryController::class);
    Route::resource('/menus', MenuController::class);
    Route::resource('/tables', TableController::class);
    Route::resource('/reservations', ReservationController::class);
});


require __DIR__ . '/auth.php';
