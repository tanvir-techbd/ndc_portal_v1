<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\NoticeController as AdminNoticeController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PricingController as AdminPricingController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\TeamController as AdminTeamController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\AccountAccessController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\ContactInquiryController;
use App\Http\Controllers\Public\FormsController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\NoticeController;
use App\Http\Controllers\Public\PolicyController;
use App\Http\Controllers\Public\PricingController;
use App\Http\Controllers\Public\ServicesController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Public site — advertising-only, no accounts. See LARAVEL-DYNAMIZATION-PLAN.md.
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [AccountAccessController::class, 'show'])->name('login');
Route::post('/contact-inquiries', [ContactInquiryController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact-inquiries.store');

Route::get('/services', [ServicesController::class, 'index'])->name('services');
Route::get('/pricing/cloud', [PricingController::class, 'cloud'])->name('pricing.cloud');
Route::get('/pricing/request', [PricingController::class, 'request'])->name('pricing.request');
Route::get('/notices', [NoticeController::class, 'index'])->name('notices');
Route::get('/notices/{notice}/download', [NoticeController::class, 'download'])->name('notices.download');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/policies', [PolicyController::class, 'index'])->name('policies');
Route::get('/forms', [FormsController::class, 'index'])->name('forms');

/*
|--------------------------------------------------------------------------
| Admin routes — separate auth flow from Jetstream's public /login.
| See LARAVEL-DYNAMIZATION-PLAN.md Part 6.
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.store');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::get('invite/{token}', [AdminAuthController::class, 'showAcceptInvite'])->name('invite.accept');
    Route::post('invite/accept', [AdminAuthController::class, 'acceptInvite'])->name('invite.accept.store');

    Route::middleware('admin')->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('pricing/{type}', [AdminPricingController::class, 'index'])->name('pricing.index');
        Route::get('pricing/{type}/create', [AdminPricingController::class, 'create'])->name('pricing.create');
        Route::post('pricing/{type}', [AdminPricingController::class, 'store'])->name('pricing.store');
        Route::get('pricing/{type}/{tierKey}/edit', [AdminPricingController::class, 'edit'])->name('pricing.edit');
        Route::put('pricing/{tierKey}', [AdminPricingController::class, 'update'])->name('pricing.update');
        Route::put('pricing/{tierKey}/visibility', [AdminPricingController::class, 'toggleVisibility'])->name('pricing.toggle-visibility');
        Route::delete('pricing/{tierKey}', [AdminPricingController::class, 'destroy'])->name('pricing.destroy');

        Route::get('notices', [AdminNoticeController::class, 'index'])->name('notices.index');
        Route::get('notices/create', [AdminNoticeController::class, 'create'])->name('notices.create');
        Route::post('notices', [AdminNoticeController::class, 'store'])->name('notices.store');
        Route::get('notices/{notice}/edit', [AdminNoticeController::class, 'edit'])->name('notices.edit');
        Route::put('notices/{notice}', [AdminNoticeController::class, 'update'])->name('notices.update');
        Route::post('notices/{notice}/publish', [AdminNoticeController::class, 'publish'])->name('notices.publish');
        Route::delete('notices/{notice}', [AdminNoticeController::class, 'destroy'])->name('notices.destroy');
        Route::post('notices/bulk-action', [AdminNoticeController::class, 'bulkAction'])->name('notices.bulk-action');

        Route::get('pages', [AdminPageController::class, 'index'])->name('pages.index');
        Route::get('pages/{slug}', [AdminPageController::class, 'edit'])->name('pages.edit');
        Route::put('pages/{slug}', [AdminPageController::class, 'update'])->name('pages.update');

        Route::get('team', [AdminTeamController::class, 'index'])->name('team.index');
        Route::get('team/create', [AdminTeamController::class, 'create'])->name('team.create');
        Route::post('team', [AdminTeamController::class, 'store'])->name('team.store');
        Route::get('team/{team}/edit', [AdminTeamController::class, 'edit'])->name('team.edit');
        Route::put('team/{team}', [AdminTeamController::class, 'update'])->name('team.update');
        Route::delete('team/{team}', [AdminTeamController::class, 'destroy'])->name('team.destroy');

        Route::get('services', [AdminServiceController::class, 'index'])->name('services.index');
        Route::get('services/create', [AdminServiceController::class, 'create'])->name('services.create');
        Route::post('services', [AdminServiceController::class, 'store'])->name('services.store');
        Route::get('services/{service}/edit', [AdminServiceController::class, 'edit'])->name('services.edit');
        Route::put('services/{service}', [AdminServiceController::class, 'update'])->name('services.update');
        Route::delete('services/{service}', [AdminServiceController::class, 'destroy'])->name('services.destroy');
        Route::put('services/{service}/featured', [AdminServiceController::class, 'toggleFeatured'])->name('services.toggle-featured');
        Route::put('services/{service}/visible', [AdminServiceController::class, 'toggleVisible'])->name('services.toggle-visible');

        Route::get('media', [AdminMediaController::class, 'index'])->name('media.index');
        Route::post('media', [AdminMediaController::class, 'store'])->name('media.store');
        Route::delete('media/{asset}', [AdminMediaController::class, 'destroy'])->name('media.destroy');

        Route::get('messages', [AdminMessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{inquiry}', [AdminMessageController::class, 'show'])->name('messages.show');
        Route::put('messages/{inquiry}/status', [AdminMessageController::class, 'updateStatus'])->name('messages.update-status');
        Route::delete('messages/{inquiry}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');

        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('users/invite', [AdminUserController::class, 'invite'])->name('users.invite');
        Route::put('users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
        Route::put('users/{user}/reactivate', [AdminUserController::class, 'reactivate'])->name('users.reactivate');
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    });
});
