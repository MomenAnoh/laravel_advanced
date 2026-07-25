
<?php
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderAndProduct\ProductController;
use App\Http\Controllers\Payment\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('payment/callback',[PaymentController::class,'successProcess']);

Route::post('register',[UserAuthController::class,'register']);
Route::post('login',[UserAuthController::class,'login']);
Route::middleware('auth:sanctum')->controller(UserAuthController::class)->group(function()
{
    Route::get('users',[UserAuthController::class,'users']);
    Route::post('logout',[UserAuthController::class,'logout']);
    Route::get('profile',[UserAuthController::class,'profile']);
});



Route::middleware('auth:sanctum')->group(function(){

    Route::post('image_store',[UserAuthController::class,'storeImage']);
    Route::post('update_store',[UserAuthController::class,'updateImage']);
    Route::delete('delete_store',[UserAuthController::class,'deleteImage']);

    Route::get('/all-notification', [FirebaseController::class, 'UserNotifications'])->middleware('permission:edit users');
    Route::post('/send-notification', [FirebaseController::class, 'send']);
    Route::post('fcm_token',[UserAuthController::class,'Save_FCM_TOKEN']);
    Route::prefix('product')->group(function()
    {
        Route::get('/',[ProductController::class,'index']);
        Route::post('/',[ProductController::class,'store']);
        Route::post('/{id}',[ProductController::class,'update']);
        Route::delete('/{id}',[ProductController::class,'destroy']);
    });
    Route::post('pay_order/{order_id}',[PaymentController::class,'payOrder']);

    Route::prefix('message')->group(function()
    {
        Route::get('/',[MessageController::class,'index']);
        Route::post('/{id}',[MessageController::class,'store']);
        Route::get('/{id}',[MessageController::class,'userMessages']);
    });

});
Route::post('updateProductDiscount',[ProductController::class,'updateProductDiscount']);

Route::post('test-pay',[ProductController::class,'payWithJazzCash']);




// test 


Route::prefix('v1')->middleware('api')->group(base_path('routes/api_v1.php'));
Route::prefix('v2')->middleware('api')->group(base_path('routes/api_v2.php'));
