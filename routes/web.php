<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\HeaderMenuPageController;
use App\Http\Controllers\Admin\HeaderMenuPageSlideController;
use App\Http\Controllers\Admin\ProgramSchemaController;
use App\Http\Controllers\Admin\HomeCmsController;
use App\Http\Controllers\Admin\HeaderMenuController;
use App\Http\Controllers\Admin\FooterCmsController;
use App\Http\Controllers\Admin\NewsItemController;
Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/pages/{page:slug}', [PageController::class, 'show'])->name('pages.show');
Route::get('/pages/{page:slug}/download-pdf', [PageController::class, 'downloadPdf'])->name('pages.pdf.download');

// Location Routes
Route::get('/location/{id}', function ($id) {

    
    $locations = [
        1 => ['name' => 'SMCHS', 'address' => 'Saddar, Karachi'],
        2 => ['name' => 'Hyderi', 'address' => 'Hyderi, Karachi'],
        3 => ['name' => 'Gulshan', 'address' => 'Gulshan-e-Iqbal, Karachi']
    ];
    
    $location = $locations[$id] ?? null;
    if(!$location) abort(404);
    
    $home = \App\Models\HomePage::first();
    return view('frontend.location', ['location' => $location, 'id' => $id, 'home' => $home]);
})->name('location.show');

// Auth Routes managed via LoginController
Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Admin Layout Sections
Route::prefix('admin')->middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // Life @ Kasbit Sub-Pages Routes
    Route::get('/life-at-kasbit/facilities', fn() => view('admin.life.facilities'))->name('admin.facilities');
    Route::get('/life-at-kasbit/premises', fn() => view('admin.life.premises'))->name('admin.premises');
    Route::get('/life-at-kasbit/societies', fn() => view('admin.life.societies'))->name('admin.societies');
    Route::get('/life-at-kasbit/gallery', fn() => view('admin.life.gallery'))->name('admin.gallery');

    Route::get('/home-cms', [HomeCmsController::class, 'index'])->name('home.cms.index');
    Route::post('/home-cms', [HomeCmsController::class, 'update'])->name('home.cms.update');
    Route::post('/home-cms/hero-slides', [HomeCmsController::class, 'storeHeroSlide'])->name('home.cms.hero-slides.store');
    Route::put('/home-cms/hero-slides/{heroSlide}', [HomeCmsController::class, 'updateHeroSlide'])->name('home.cms.hero-slides.update');
    Route::delete('/home-cms/hero-slides/{heroSlide}', [HomeCmsController::class, 'destroyHeroSlide'])->name('home.cms.hero-slides.destroy');
    Route::post('/home-cms/news-background', [HomeCmsController::class, 'updateNewsBackground'])->name('home.cms.news-background.update');
    Route::post('/home-cms/video-tour', [HomeCmsController::class, 'updateVideoTour'])->name('home.cms.video-tour.update');

    Route::post('/header-menu/settings', [HeaderMenuController::class, 'updateSettings'])
        ->name('header-menu.settings.update');
    Route::patch('/header-menu/{headerMenu}/toggle', [HeaderMenuController::class, 'toggle'])
        ->name('header-menu.toggle');
    Route::get('/header-menu/{headerMenu}/page', [HeaderMenuPageController::class, 'edit'])
        ->name('header-menu.page.edit');
    Route::put('/header-menu/{headerMenu}/page', [HeaderMenuPageController::class, 'update'])
        ->name('header-menu.page.update');
    Route::post('/header-menu-pages/{page}/slides', [HeaderMenuPageSlideController::class, 'store'])
        ->name('header-menu-page-slides.store');
    Route::put('/header-menu-page-slides/{slide}', [HeaderMenuPageSlideController::class, 'update'])
        ->name('header-menu-page-slides.update');
    Route::delete('/header-menu-page-slides/{slide}', [HeaderMenuPageSlideController::class, 'destroy'])
        ->name('header-menu-page-slides.destroy');
    Route::post('/header-menu-pages/{page}/program-schemas', [ProgramSchemaController::class, 'store'])
        ->name('program-schemas.store');
    Route::put('/program-schemas/{schemaTable}', [ProgramSchemaController::class, 'update'])
        ->name('program-schemas.update');
    Route::delete('/program-schemas/{schemaTable}', [ProgramSchemaController::class, 'destroy'])
        ->name('program-schemas.destroy');

    Route::resource('header-menu', HeaderMenuController::class)
        ->except(['create', 'show'])
        ->names('header-menu');

    Route::get('/footer-cms', [FooterCmsController::class, 'index'])->name('footer-cms.index');
    Route::post('/footer-cms', [FooterCmsController::class, 'update'])->name('footer-cms.update');

    Route::resource('news-items', NewsItemController::class)
        ->only(['store', 'update', 'destroy'])
        ->names('news-items');
});

// Cache Clearing Endpoint
Route::get('/clear-everything', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('optimize:clear');
    
    return "KASBIT system cache, routes, views, and configuration have been successfully cleared and optimized.";
});
