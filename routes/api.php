<?php
use App\Http\Controllers\Api\Auth\AuthController;

use App\Http\Controllers\Api\Catalog\BrandController;
use App\Http\Controllers\Api\Catalog\CategoryController;
use App\Http\Controllers\Api\Catalog\LocationController;
use App\Http\Controllers\Api\Catalog\OfferController;
use App\Http\Controllers\Api\Catalog\VehicleModelController;
use App\Http\Controllers\Api\Catalog\VehicleController;

use App\Http\Controllers\Api\Customer\Address\AddressController;
use App\Http\Controllers\Api\Customer\PreOrder\PreOrderController;
use App\Http\Controllers\Api\Customer\Reservation\ReservationController;
use App\Http\Controllers\Api\Customer\Deal\DealController;
use App\Http\Controllers\Api\Customer\Payment\PaymentController;
use App\Http\Controllers\Api\Customer\Installment\InstallmentController;

use App\Http\Controllers\Api\Admin\Catalog\BrandController as AdminBrandController;
use App\Http\Controllers\Api\Admin\Catalog\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\Catalog\VehicleModelController as AdminVehicleModelController;
use App\Http\Controllers\Api\Admin\Catalog\VehicleController as AdminVehicleController;
use App\Http\Controllers\Api\Admin\Catalog\CarDetailController as AdminCarDetailController;
use App\Http\Controllers\Api\Admin\Catalog\MotorcycleDetailController as AdminMotorcycleDetailController;
use App\Http\Controllers\Api\Admin\Catalog\VehicleImageController as AdminVehicleImageController;

use App\Http\Controllers\Api\Admin\Discount\DiscountCodeController as AdminDiscountCodeController;
use App\Http\Controllers\Api\Admin\Reservation\ReservationController as AdminReservationController;
use App\Http\Controllers\Api\Admin\Deal\DealController as AdminDealController;
use App\Http\Controllers\Api\Admin\Payment\PaymentController as AdminPaymentController;
use App\Http\Controllers\Api\Admin\Installment\InstallmentController as AdminInstallmentController;
use App\Http\Controllers\Api\Admin\PreOrder\PreOrderController as AdminPreOrderController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::patch('/profile', [AuthController::class, 'updateProfile']);
        Route::put('/password', [AuthController::class, 'changePassword']);
    });
});


/*
|--------------------------------------------------------------------------
| Public Catalog
|--------------------------------------------------------------------------
*/

Route::get('/brands', [BrandController::class, 'index']);

Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/vehicle-models', [VehicleModelController::class, 'index']);
Route::get('/vehicle-models/{vehicleModel}', [VehicleModelController::class, 'show']);

Route::get('/vehicles', [VehicleController::class, 'index']);
Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show']);

Route::get('/offers', [OfferController::class, 'index']);
Route::get('/offers/{offer}', [OfferController::class, 'show']);

Route::prefix('locations')->group(function () {
    Route::get('/provinces', [LocationController::class, 'provinces']);
    Route::get('/provinces/{province}/cities', [LocationController::class, 'cities']);
});


/*
|--------------------------------------------------------------------------
| Customer
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Addresses
    Route::apiResource('addresses', AddressController::class);


    // Reservations
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show']);
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel']);

    // Pre order
    Route::post('/pre-orders', [PreOrderController::class, 'store']);
    Route::get('/pre-orders', [PreOrderController::class, 'index']);
    Route::get('/pre-orders/{preOrder}', [PreOrderController::class, 'show']);
    Route::post('/pre-orders/{preOrder}/cancel', [PreOrderController::class, 'cancel']);

    // Deals
    Route::post('/deals', [DealController::class, 'store']);
    Route::get('/deals', [DealController::class, 'index']);
    Route::get('/deals/{deal}', [DealController::class, 'show']);
    Route::put('/deals/{deal}', [DealController::class, 'update']);


    // Payments
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::put('/payments/{payment}', [PaymentController::class, 'update']);
    Route::get('/deals/{deal}/payments', [PaymentController::class, 'index']);
    Route::get('/payments/{payment}', [PaymentController::class, 'show']);


    // Installments
    Route::get('/deals/{deal}/installments', [InstallmentController::class, 'index']);
    Route::get('/installments/{installment}', [InstallmentController::class, 'show']);

    // Discount
    Route::post(
        '/vehicles/{vehicle}/calculate-price',
        [VehicleController::class, 'calculatePrice']
    );
});


/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware(['auth:sanctum', 'admin'])
    ->group(function () {

        // Catalog
        Route::apiResource('brands', AdminBrandController::class);
        Route::apiResource('categories', AdminCategoryController::class);
        Route::apiResource('vehicle-models', AdminVehicleModelController::class);
        Route::apiResource('vehicles', AdminVehicleController::class);



        // Vehicle Details
        Route::post('/vehicles/{vehicle}/car-detail', [
            AdminCarDetailController::class,
            'store',
        ]);

        Route::put('/vehicles/{vehicle}/car-detail', [
            AdminCarDetailController::class,
            'update',
        ]);

        Route::delete('/vehicles/{vehicle}/car-detail', [
            AdminCarDetailController::class,
            'destroy',
        ]);

        Route::post('/vehicles/{vehicle}/motorcycle-detail', [
            AdminMotorcycleDetailController::class,
            'store',
        ]);

        Route::put('/vehicles/{vehicle}/motorcycle-detail', [
            AdminMotorcycleDetailController::class,
            'update',
        ]);

        Route::delete('/vehicles/{vehicle}/motorcycle-detail', [
            AdminMotorcycleDetailController::class,
            'destroy',
        ]);

        // Vehicle Images
        Route::post('/vehicles/{vehicle}/images', [
            AdminVehicleImageController::class,
            'store',
        ]);

        Route::put('/vehicle-images/{vehicleImage}', [
            AdminVehicleImageController::class,
            'update',
        ]);

        Route::delete('/vehicle-images/{vehicleImage}', [
            AdminVehicleImageController::class,
            'destroy',
        ]);

        // Discounts
        Route::apiResource('discount-codes', AdminDiscountCodeController::class);


        // Reservations
        Route::get('/reservations', [AdminReservationController::class, 'index']);
        Route::get('/reservations/{reservation}', [AdminReservationController::class, 'show']);
        Route::post('/reservations/{reservation}/approve', [AdminReservationController::class, 'approve']);
        Route::post('/reservations/{reservation}/reject', [AdminReservationController::class, 'reject']);
        Route::post('/reservations/{reservation}/cancel', [AdminReservationController::class, 'cancel']);


        // Deals
        Route::get('/deals', [AdminDealController::class, 'index']);
        Route::get('/deals/{deal}', [AdminDealController::class, 'show']);
        Route::post('/deals/{deal}/approve', [AdminDealController::class, 'approve']);
        Route::post('/deals/{deal}/reject', [AdminDealController::class, 'reject']);
        Route::post('/deals/{deal}/finalize', [AdminDealController::class, 'finalize']);
        Route::post('/deals/{deal}/cancel', [AdminDealController::class, 'cancel']);

        // Deal change requests
        Route::post(
            '/deal-change-requests/{changeRequest}/approve',
            [AdminDealController::class, 'approveChangeRequest']
        );

        Route::post(
            '/deal-change-requests/{changeRequest}/reject',
            [AdminDealController::class, 'rejectChangeRequest']
        );

        // Payments
        Route::get('/deals/{deal}/payments', [AdminPaymentController::class, 'index']);
        Route::get('/payments/{payment}', [AdminPaymentController::class, 'show']);
        Route::post('/payments/{payment}/confirm', [AdminPaymentController::class, 'confirm']);
        Route::post('/payments/{payment}/approve', [AdminPaymentController::class, 'approve']);
        Route::post('/payments/{payment}/reject', [AdminPaymentController::class, 'reject']);


        // Installments
        Route::get('/deals/{deal}/installments', [AdminInstallmentController::class, 'index']);
        Route::get('/installments/{installment}', [AdminInstallmentController::class, 'show']);

        // Pre-order
        Route::get('pre-orders', [AdminPreOrderController::class, 'index']);
        Route::patch('pre-orders/{preOrder}/approve', [AdminPreOrderController::class, 'approve']);
        Route::patch('pre-orders/{preOrder}/reject', [AdminPreOrderController::class, 'reject']);
    });
