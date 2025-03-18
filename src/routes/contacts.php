<?php

use Illuminate\Support\Facades\Route;
use Vitaliiriabyshenko\Contacts\Http\Controllers\ContactController;

Route::group(['middleware' => ['web']], function () {
    Route::resource('/contacts', ContactController::class)->except(['create', 'show', 'edit']);
    Route::post('phone-unique', [ContactController::class, 'checkUniquePhone'])->name('phone-unique');
});