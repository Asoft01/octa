<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\Order\OrderController;
use App\Http\Controllers\Backend\Library\VideoController;
use App\Http\Controllers\Backend\Library\CategoryController;
use App\Http\Controllers\Backend\Library\CotdController;
use App\Http\Controllers\Backend\Library\TagController;
use App\Http\Controllers\Backend\Library\ReviewController;
use App\Http\Controllers\Backend\Library\AssetController;
use App\Http\Controllers\Backend\Library\ContributorController;
use App\Http\Controllers\Backend\Library\ExpertController;
use App\Http\Controllers\Backend\Library\LiveScheduleController;
use App\Http\Controllers\Backend\Library\PlaylistController;

// All route names are prefixed with 'admin.'.
Route::redirect('/', '/admin/dashboard', 301);
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::get('order', [OrderController::class, 'index'])->name('order');
Route::get('order/todo', [OrderController::class, 'todo'])->name('order.todo');
Route::get('order/summary', [OrderController::class, 'summary'])->name('order.summary');

// CONTRIBUTORS
Route::get('library/contributors', [ContributorController::class, 'index'])->name('library.contributors');
Route::post('library/contributors', [ContributorController::class, 'store'])->name('library.contributors.store');
Route::get('library/contributors/create', [ContributorController::class, 'create'])->name('library.contributors.create');
Route::get('library/contributors/edit/{id?}', [ContributorController::class, 'edit'])->name('library.contributors.edit');
Route::get('library/contributors/delete/{id?}', [ContributorController::class, 'delete'])->name('library.contributors.delete');
Route::post('library/contributors/slug', [ContributorController::class, 'slug'])->name('library.contributors.slug');

// MENTORS
Route::get('library/experts', [ExpertController::class, 'index'])->name('library.experts');
Route::post('library/experts', [ExpertController::class, 'store'])->name('library.experts.store');
Route::get('library/experts/create', [ExpertController::class, 'create'])->name('library.experts.create');
Route::get('library/experts/edit/{id?}', [ExpertController::class, 'edit'])->name('library.experts.edit');
Route::get('library/experts/delete/{id?}', [ExpertController::class, 'delete'])->name('library.experts.delete');
Route::post('library/experts/slug', [ExpertController::class, 'slug'])->name('library.experts.slug');

// PLAYLISTS
Route::get('library/playlists', [PlaylistController::class, 'index'])->name('library.playlists');
Route::post('library/playlists', [PlaylistController::class, 'store'])->name('library.playlists.store');
Route::get('library/playlists/create', [PlaylistController::class, 'create'])->name('library.playlists.create');
Route::get('library/playlists/edit/{id?}', [PlaylistController::class, 'edit'])->name('library.playlists.edit');
Route::get('library/playlists/delete/{id?}', [PlaylistController::class, 'delete'])->name('library.playlists.delete');
Route::post('library/playlists/slug', [PlaylistController::class, 'slug'])->name('library.playlists.slug');
Route::get('library/playlists/dump/{id?}', [PlaylistController::class, 'dump'])->name('library.playlists.dump');
Route::get('library/playlists/contents', [PlaylistController::class, 'contents'])->name('library.playlists.contents');

// VIDEOS
Route::get('library/videos', [VideoController::class, 'index'])->name('library.videos');
Route::get('library/videosNoThumb', [VideoController::class, 'nothumb'])->name('library.videosnothumb');
Route::post('library/videos', [VideoController::class, 'store'])->name('library.videos.store');
Route::get('library/videos/create', [VideoController::class, 'create'])->name('library.videos.create');
Route::get('library/videos/edit/{id?}', [VideoController::class, 'edit'])->name('library.videos.edit');
Route::get('library/videos/delete/{id?}', [VideoController::class, 'delete'])->name('library.videos.delete');
Route::post('library/videos/slug', [VideoController::class, 'slug'])->name('library.videos.slug');

// REVIEWS
Route::get('library/reviews', [ReviewController::class, 'index'])->name('library.reviews');
Route::post('library/reviews', [ReviewController::class, 'store'])->name('library.reviews.store');
Route::get('library/reviews/create', [ReviewController::class, 'create'])->name('library.reviews.create');
Route::get('library/reviews/edit/{id?}', [ReviewController::class, 'edit'])->name('library.reviews.edit');
Route::get('library/reviews/delete/{id?}', [ReviewController::class, 'delete'])->name('library.reviews.delete');
Route::post('library/reviews/slug', [VideoController::class, 'slug'])->name('library.reviews.slug');

// ASSETS
Route::get('library/assets', [AssetController::class, 'index'])->name('library.assets');
Route::post('library/assets', [AssetController::class, 'store'])->name('library.assets.store');
Route::get('library/assets/create', [AssetController::class, 'create'])->name('library.assets.create');
Route::get('library/assets/edit/{id?}', [AssetController::class, 'edit'])->name('library.assets.edit');
Route::get('library/assets/delete/{id?}', [AssetController::class, 'delete'])->name('library.assets.delete');
Route::post('library/assets/slug', [VideoController::class, 'slug'])->name('library.assets.slug');

// COTD
Route::get('library/cotd', [CotdController::class, 'index'])->name('library.cotd');

// CATEGORIES
Route::get('library/categories', [CategoryController::class, 'index'])->name('library.categories');
Route::post('library/categories', [CategoryController::class, 'store'])->name('library.categories.store');
Route::get('library/categories/create', [CategoryController::class, 'create'])->name('library.categories.create');
Route::get('library/categories/edit/{id?}', [CategoryController::class, 'edit'])->name('library.categories.edit');
Route::get('library/categories/delete/{id?}', [CategoryController::class, 'delete'])->name('library.categories.delete');

// TAGS
Route::get('library/tags', [TagController::class, 'index'])->name('library.tags');
Route::post('library/tags', [TagController::class, 'store'])->name('library.tags.store');
Route::get('library/tags/edit/{id?}', [TagController::class, 'edit'])->name('library.tags.edit');
Route::get('library/tags/delete/{id?}', [TagController::class, 'delete'])->name('library.tags.delete');

// LIVE SCHEDULES
Route::get('library/schedules', [LiveScheduleController::class, 'index'])->name('library.schedules');
Route::post('library/schedules', [LiveScheduleController::class, 'store'])->name('library.schedules.store');
Route::get('library/schedules/create', [LiveScheduleController::class, 'create'])->name('library.schedules.create');
Route::get('library/schedules/edit/{id?}', [LiveScheduleController::class, 'edit'])->name('library.schedules.edit');
Route::get('library/schedules/delete/{id?}', [LiveScheduleController::class, 'delete'])->name('library.schedules.delete');
Route::post('library/schedules/slug', [LiveScheduleController::class, 'slug'])->name('library.schedules.slug');