<?php

use Illuminate\Support\Facades\Route;
use Modules\Batch\Http\Controllers\BatchController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('batches', BatchController::class)->names('batch');
});
