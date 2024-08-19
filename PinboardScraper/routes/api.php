<?php

use App\Http\Controllers\LinkController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/links', [LinkController::class, 'index']);