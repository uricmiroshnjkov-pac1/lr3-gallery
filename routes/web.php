<?php
use App\Http\Controllers\PaintingController;
use Illuminate\Support\Facades\Route;
Route::redirect('/', '/paintings');
Route::get('/paintings/{painting}/image', [PaintingController::class, 'image'])->name('paintings.image');
Route::resource('paintings', PaintingController::class);
