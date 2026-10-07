<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/animaux');

Route::view('/composants', 'styleguide')->name('styleguide');
