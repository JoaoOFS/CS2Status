<?php

use Illuminate\Support\Facades\Route;

Route::prefix('cs2')
    ->middleware(['api', 'valid.api.consumer'])
    ->group(base_path('routes/api/cs2.php'));
