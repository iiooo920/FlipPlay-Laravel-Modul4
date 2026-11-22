<?php

use App\Http\Controllers\MediaController;
use App\Http\Controllers\UserCollectionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// CRUD lengkap: GET list/detail, POST, PATCH/PUT, DELETE.
Route::apiResource('media', MediaController::class)->parameters(['media' => 'media']);

// Dua entitas dengan relasi Eloquent: Media <-> User (belongsToMany).
Route::get('users', [UserController::class, 'index']);
Route::get('users/{user}', [UserController::class, 'show']);

// Custom action di luar CRUD, menggunakan attach/detach pada pivot media_user.
Route::get('users/{user}/collection', [UserCollectionController::class, 'index']);
Route::post('users/{user}/collection', [UserCollectionController::class, 'store']);
Route::delete('users/{user}/collection/{media}', [UserCollectionController::class, 'destroy']);
