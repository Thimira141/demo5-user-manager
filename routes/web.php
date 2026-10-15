<?php

use Illuminate\Support\Facades\Route;
use App\Livewire as AppLivewire;

Route::get('/', AppLivewire\Auth\Login::class);
Route::get('/login', AppLivewire\Auth\Login::class)->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', AppLivewire\Dashboard::class)->name('dashboard');

    Route::prefix('/accounts')->group(function () {
        Route::get('/', AppLivewire\Accounts\Table::class)->name('accounts.table')->middleware('multiOrGates:DELETE_USER|UPDATE_USER');
        Route::get('/new', AppLivewire\Accounts\Form::class)->name('accounts.new')->middleware('can:CREATE_USER');
        Route::get('/{user}/edit', AppLivewire\Accounts\Form::class)->name('accounts.update')->middleware('can:UPDATE_USER');
        Route::get('/{user}/view', AppLivewire\Accounts\View::class)->name('accounts.view');
    });

    Route::group(['prefix'=> '/profile'], function () {
        Route::get('/', AppLivewire\Auth\My\View::class)->name('profile.view');
        Route::get('/edit', AppLivewire\Auth\My\EditForm::class)->name('profile.edit');
    });

    Route::prefix('/roles')->group(function () {
        Route::get('/', AppLivewire\Roles\Table::class)->name('roles.table')->middleware('multiOrGates:DELETE_ROLE|UPDATE_ROLE');
        Route::get('/new', AppLivewire\Roles\EditForm::class)->name('roles.new')->middleware('can:CREATE_ROLE');
        Route::get('/{role}/edit', AppLivewire\Roles\EditForm::class)->name('roles.update')->middleware('can:UPDATE_ROLE');
    });
});

// require __DIR__.'/auth.php'; // not using original routes!
