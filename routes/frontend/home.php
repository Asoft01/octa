<?php
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\AC\AssetController;
use App\Http\Controllers\Frontend\AC\ContentController;
use App\Http\Controllers\Frontend\AC\ContentFilterController;
use App\Http\Controllers\Frontend\AC\ContentUserMetricController;
use App\Http\Controllers\Frontend\AC\ContentUserVoteController;
use App\Http\Controllers\Frontend\AC\DiscordController;
use App\Http\Controllers\Frontend\AC\FavoriteController;
use App\Http\Controllers\Frontend\AC\LearnController;
use App\Http\Controllers\Frontend\AC\MentorController;
use App\Http\Controllers\Frontend\AC\OrderController;
use App\Http\Controllers\Frontend\AC\PlaylistController;
use App\Http\Controllers\Frontend\AC\TagController;
use App\Http\Controllers\Frontend\AC\WatchlistItemController;
use App\Http\Controllers\Frontend\User\AccountController;
use App\Http\Controllers\Frontend\User\ManageReviewController;
use App\Http\Controllers\Frontend\User\ProfileController;
use App\Http\Controllers\Frontend\AC\LiveScheduleController;
use App\Http\Controllers\Frontend\AC\ChallengeController;

// use App\Http\Controllers\Frontend\AC\LiveScheduleController;


/*
 * These routes are accessible to guests and users alike.
 * The route names are prefixed with "frontend."
 */

// Home
Route::get('/', [LearnController::class, 'home'])->name('home');
Route::permanentRedirect('/home', '/')->name('index');

// Unsubscribe
Route::get('/unsubscribe/{subscriberID}', [HomeController::class, 'unsubscribe'])->name('unsubscribe');
Route::post('/unsubscribe/{subscriberID}', [HomeController::class, 'unsubscribePost'])->name('unsubscribePost');

// About
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Challenge 
Route::get('/challenge', [ChallengeController::class, 'challenge'])->name('challenge');

// Terms
Route::get('terms', [HomeController::class, 'terms'])->name('terms');

// Library
Route::get('library', [LearnController::class, 'library'])->name('learn');
Route::permanentRedirect('/learn', '/library');

// Category
Route::get('library/{category}', [LearnController::class, 'category'])->name('category');
Route::permanentRedirect('/learn/{category}', '/library/{category}');

// Tag
Route::get('tag/{slug}', [TagController::class, 'index'])->name('tag');

// Assets
Route::get('assets', [AssetController::class, 'index'])->name('assets');

// All Contents
Route::get('all', [LearnController::class, 'all'])->name('all');

// Search
Route::get('search', [LearnController::class, 'search'])->name('search');

// Reviewers
Route::get('reviewers', [MentorController::class, 'index'])->name('mentors');

// Discord
Route::get('discord', [DiscordController::class, 'index'])->name('discord');

// Content
Route::get('/content/{slug}', [ContentController::class, 'show'])->name('content');

// Playlist
Route::get('playlist/{slug}', [ContentController::class, 'showPlaylist'])->name('playlist.show');

// Live
Route::match(['get', 'post'], '/live', [HomeController::class, 'live'])->name('live');
Route::get('/live/infinite-scrolling', [HomeController::class, 'liveInfiniteScrolling'])->name('live.infinite'); // AJAX
Route::get('/live/{slug}/{format}', [LiveScheduleController::class, 'invite'])->name('live.invite');

// Contact Us
Route::get('contact', [ContactController::class, 'index'])->name('contact');
Route::post('contact/send', [ContactController::class, 'send'])->name('contact.send');

// Contributor and Reviewer
Route::get('contributor/{slug}', [MentorController::class, 'contributor'])->name('contributor');
Route::get('contributor/{slug}/infinite-scrolling', [MentorController::class, 'contributorInfiniteScrolling'])->name('contributor.infinite'); // AJAX
Route::get('reviewer/{slug}', [MentorController::class, 'reviewer'])->name('reviewer');
Route::get('reviewer/{slug}/infinite-scrolling', [MentorController::class, 'reviewerInfiniteScrolling'])->name('reviewer.infinite'); // AJAX
Route::get('account/{slug}/infinite-scrolling', [MentorController::class, 'playlistsInfiniteScrolling'])->name('account.playlists.infinite'); // AJAX

// Filter
Route::match(['get', 'post'], 'filter', [ContentFilterController::class, 'query'])->name('filter'); // AJAX

// Generic
Route::get('currencyAjax/{currency}/{reviewer}', [HomeController::class, 'currencyAjax'])->name('currencyAjax'); // AJAX
Route::post('upload/file', [HomeController::class, 'uploadFile'])->name('upload.file'); // AJAX

// Hyvor Webhook
Route::post('hyvor', [HomeController::class, 'hyvor'])->name('hyvor');

/*
 * These routes require the user to be logged-in and cannot be hit if the password is expired.
 * The route names are prefixed with "frontend.user."
 */
Route::group(['middleware' => ['auth', 'password_expires']], function () {
    Route::group(['namespace' => 'User', 'as' => 'user.'], function () {

        // Content Preview for Administrators
        Route::get('preview/{slug}', [LearnController::class, 'preview'])->name('preview');
        
        // Order
        Route::get('order', [OrderController::class, 'index'])->name('order');
        Route::post('order', [OrderController::class, 'step1'])->name('order.post');
        Route::get('order/upload', [OrderController::class, 'upload'])->name('order.upload');
        Route::post('order/upload', [OrderController::class, 'step2'])->name('order.upload.post');
        Route::get('order/payment', [OrderController::class, 'payment'])->name('order.payment');
        Route::post('order/payment', [OrderController::class, 'stripe'])->name('order.payment.stripe');
        Route::get('order/confirmation', [OrderController::class, 'confirmation'])->name('order.confirmation');

        // User Account Specific
        Route::get('account', [AccountController::class, 'index'])->name('account');
        Route::get('public-info', [AccountController::class, 'editPublicInfo'])->name('publicinfo.edit');
		Route::post('public-info', [AccountController::class, 'updatePublicInfo'])->name('publicinfo.update');
		Route::get('availabilityAjax/{reviewer}', [ManageReviewController::class, 'availabilityAjax'])->name('availabilityAjax');
		Route::permanentRedirect('availability', 'public-info');

        // Manage
        Route::get('manage', [ManageReviewController::class, 'index'])->name('manage');
        Route::get('manage/{review}', [ManageReviewController::class, 'review'])->name('review');
        Route::get('wiki', [ManageReviewController::class, 'wiki'])->name('expertwiki');

        // User Profile Specific
        Route::patch('profile/update', [ProfileController::class, 'update'])->name('profile.update');

        // Favorites
		Route::get('favorites', [FavoriteController::class, 'index'])->name('favorites');
		Route::post('favorite/add/{content}', [FavoriteController::class, 'add'])->name('favorite.add');
		Route::post('favorite/remove/{content}', [FavoriteController::class, 'remove'])->name('favorite.remove');

        // Watchlist
		Route::get('watchlist', [WatchlistItemController::class, 'index'])->name('watchlist');
		Route::post('watchlist/add/{content}', [WatchlistItemController::class, 'add'])->name('watchlist.add');
		Route::post('watchlist/remove/{content}', [WatchlistItemController::class, 'remove'])->name('watchlist.remove');

        // Voting
		Route::post('vote', [ContentUserVoteController::class, 'createOrUpdate'])->name('vote.add');

        // Metrics
		Route::post('metrics/asset', [ContentUserMetricController::class, 'updateAssetDownloads'])->name('metrics.asset.update');
		Route::post('metrics/video', [ContentUserMetricController::class, 'updateVideoTimes'])->name('metrics.video.update');

    });
});
