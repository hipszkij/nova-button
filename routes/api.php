<?php

use hipszkij\NovaButton\Http\Controllers\ButtonController;
use Illuminate\Support\Facades\Route;

Route::post('/{resource}/{resourceId}/{buttonKey}', [ButtonController::class, 'handle']);
Route::post('/action', [ButtonController::class, 'triggerAction']);
