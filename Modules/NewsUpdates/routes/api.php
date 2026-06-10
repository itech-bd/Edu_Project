<?php

use Illuminate\Support\Facades\Route;
use Modules\NewsUpdates\Http\Controllers\NewsUpdatesController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('newsupdates', NewsUpdatesController::class)->names('newsupdates');
});
