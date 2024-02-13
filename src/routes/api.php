<?php

use Illuminate\Support\Facades\Route;
// データの全タイプ
use App\Http\Controllers\API\DataEntities\IndexController as DataEntitiesIndex;

use App\Http\Controllers\API\Zentra\IndexController as ZentraDataIndex;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/data_entities', DataEntitiesIndex::class);
Route::get('/zentra', ZentraDataIndex::class);
