<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are typically stateless and prefixed with "api".
| For example: GET /api/user
|
*/

Route::middleware('api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('posts', PostController::class);
