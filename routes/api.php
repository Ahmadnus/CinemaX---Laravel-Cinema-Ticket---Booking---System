<?php

use App\Http\Controllers\api\BookingController;
use App\Http\Controllers\api\HallController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\MovieController;
use App\Http\Controllers\api\MovieReviewController;
use App\Http\Controllers\api\ScreeningController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SnackController;
use App\Http\Controllers\api\SeatController ;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::controller(MovieController::class)->prefix('movies')->group(function () {
    Route::get('/', 'index');        // عرض كل الأفلام
    Route::post('/', 'store');       // إنشاء فيلم جديد
    Route::get('{id}', 'show');      // عرض فيلم معيّن
    Route::put('{id}', 'update');    // تعديل فيلم
    Route::delete('{id}', 'destroy'); // حذف فيلم
});
Route::controller(ScreeningController::class)->prefix('screening')->group(function () {
    Route::get('/', 'index');        // عرض كل الأفلام
    Route::post('/', 'store');       // إنشاء فيلم جديد
    Route::get('{screening}', 'show');      // عرض فيلم معيّن
    Route::put('{screening}', 'update');    // تعديل فيلم
    Route::delete('{screening}', 'destroy'); // حذف فيلم
});

Route::prefix('bookings')->group(function () {
    Route::post('/', [BookingController::class, 'store']);
    Route::put('{booking}', [BookingController::class, 'update']);
    Route::delete('{booking}', [BookingController::class, 'destroy']);
});


Route::prefix('snacks')->group(function () {
    Route::get('/', [SnackController::class, 'index']);         // عرض كل السناكات
    Route::post('/', [SnackController::class, 'store']);        // إضافة سناك جديد
    Route::get('/{id}', [SnackController::class, 'show']);      // عرض سناك معين
    Route::put('/{id}', [SnackController::class, 'update']);    // تحديث بيانات سناك
    Route::delete('/{id}', [SnackController::class, 'destroy']); // حذف سناك
});

Route::prefix('screenings/{screeningId}')->group(function () {
    Route::get('seats', [SeatController::class, 'index']);
    Route::post('seats', [SeatController::class, 'store']);

});
Route::delete('seats', [SeatController::class, 'destroy']);
Route::post('seats/update-reservation', [SeatController::class, 'updateReservation']);


Route::prefix('halls')->controller(HallController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('{hall}', 'show');
    Route::put('{hall}', 'update');
    Route::delete('{hall}', 'destroy');
});
Route::prefix('movie-reviews')->controller(MovieReviewController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('{movieReview}', 'show');
    Route::put('{movieReview}', 'update');
    Route::delete('{movieReview}', 'destroy');
});