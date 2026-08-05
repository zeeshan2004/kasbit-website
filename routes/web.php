<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController as FeedbackDepartmentController;
use App\Http\Controllers\Admin\HeaderMenuPageController;
use App\Http\Controllers\Admin\HeaderMenuPageSlideController;
use App\Http\Controllers\Admin\ProgramSchemaController;
use App\Http\Controllers\Admin\HomeCmsController;
use App\Http\Controllers\Admin\HeaderMenuController;
use App\Http\Controllers\Admin\FooterCmsController;
use App\Http\Controllers\Admin\NewsItemController;
use App\Http\Controllers\Admin\AcademicCalendarController;
use App\Http\Controllers\Admin\AcademicDepartmentController;
use App\Http\Controllers\Admin\PageGalleryController;
use App\Http\Controllers\Admin\EventAlbumController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\ElibraryResourceController;
use App\Http\Controllers\Admin\QueryController as AdminQueryController;
use App\Http\Controllers\Admin\RegistrationProgramController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Admin\AiProviderController;
use App\Http\Controllers\Admin\ChatbotDashboardController;
use App\Http\Controllers\Admin\ChatbotHistoryController;
use App\Http\Controllers\Admin\ChatbotKnowledgeController;
use App\Http\Controllers\Admin\ChatbotSettingsController;
use App\Http\Controllers\Admin\ChatbotSuggestionController;
use App\Http\Controllers\Admin\ChatbotUnansweredController;



Route::get('/clear-cache', function () {

    Artisan::call('optimize:clear');
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');

    return response()->json([
        'status' => true,
        'message' => 'Cache cleared successfully.'
    ]);
});


Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/pages/{page:slug}', [PageController::class, 'show'])->name('pages.show');
Route::get('/pages/{page:slug}/download-pdf', [PageController::class, 'downloadPdf'])->name('pages.pdf.download');
Route::get('/event-gallery/{album:slug}', [PageController::class, 'eventAlbum'])->name('event-gallery.album');

Route::prefix('chatbot')->name('chatbot.')->group(function () {
    Route::get('/bootstrap', [ChatbotController::class, 'bootstrap'])->name('bootstrap');
    Route::post('/profile', [ChatbotController::class, 'profile'])->name('profile');
    Route::post('/login', [ChatbotController::class, 'login'])->name('login');
    Route::post('/guest', [ChatbotController::class, 'guest'])->name('guest');
    Route::post('/message', [ChatbotController::class, 'message'])->name('message');
    Route::delete('/conversation', [ChatbotController::class, 'clear'])->name('clear');
});





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

Route::middleware('guest:student')->group(function () {
    Route::get('/student/login', [StudentAuthController::class, 'showLogin'])
        ->name('student.login');
    Route::post('/student/login', [StudentAuthController::class, 'login'])
        ->name('student.login.store');
    Route::get('/student/register', [StudentAuthController::class, 'showRegistration'])
        ->name('student.register');
    Route::post('/student/register', [StudentAuthController::class, 'register'])
        ->name('student.register.store');
});

Route::post('/student/logout', [StudentAuthController::class, 'logout'])
    ->middleware('auth:student')
    ->name('student.logout');

Route::middleware('student')->group(function () {
    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
});

// Protected Admin Layout Sections
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::prefix('ai-chatbot')->name('admin.chatbot.')->group(function () {
        Route::get('/', [ChatbotDashboardController::class, 'index'])->name('dashboard');

        Route::get('/providers', [AiProviderController::class, 'index'])->name('providers.index');
        Route::post('/providers', [AiProviderController::class, 'store'])->name('providers.store');
        Route::put('/providers/{provider}', [AiProviderController::class, 'update'])->name('providers.update');
        Route::delete('/providers/{provider}', [AiProviderController::class, 'destroy'])->name('providers.destroy');
        Route::post('/providers/{provider}/default', [AiProviderController::class, 'makeDefault'])->name('providers.default');
        Route::post('/providers/{provider}/test', [AiProviderController::class, 'test'])->name('providers.test');

        Route::get('/knowledge', [ChatbotKnowledgeController::class, 'index'])->name('knowledge.index');
        Route::post('/knowledge', [ChatbotKnowledgeController::class, 'store'])->name('knowledge.store');
        Route::post('/knowledge/import', [ChatbotKnowledgeController::class, 'import'])->name('knowledge.import');
        Route::delete('/knowledge/import/{document}', [ChatbotKnowledgeController::class, 'deleteDocument'])->name('knowledge.import.delete');
        Route::put('/knowledge/{knowledge}', [ChatbotKnowledgeController::class, 'update'])->name('knowledge.update');
        Route::delete('/knowledge/{knowledge}', [ChatbotKnowledgeController::class, 'destroy'])->name('knowledge.destroy');
        Route::post('/categories', [ChatbotKnowledgeController::class, 'storeCategory'])->name('categories.store');
        Route::delete('/categories/{category}', [ChatbotKnowledgeController::class, 'destroyCategory'])->name('categories.destroy');

        Route::get('/data', [\App\Http\Controllers\Admin\ChatbotKnowledgeDataController::class, 'index'])->name('data.index');
        Route::post('/data', [\App\Http\Controllers\Admin\ChatbotKnowledgeDataController::class, 'store'])->name('data.store');
        Route::put('/data/{knowledgeData}', [\App\Http\Controllers\Admin\ChatbotKnowledgeDataController::class, 'update'])->name('data.update');
        Route::delete('/data/{knowledgeData}', [\App\Http\Controllers\Admin\ChatbotKnowledgeDataController::class, 'destroy'])->name('data.destroy');
        Route::post('/data/import', [\App\Http\Controllers\Admin\ChatbotKnowledgeDataController::class, 'importCsv'])->name('data.import');

        Route::get('/unanswered', [ChatbotUnansweredController::class, 'index'])->name('unanswered.index');
        Route::put('/unanswered/{unanswered}', [ChatbotUnansweredController::class, 'update'])->name('unanswered.update');
        Route::post('/unanswered/{unanswered}/promote', [ChatbotUnansweredController::class, 'promote'])->name('unanswered.promote');

        Route::get('/history', [ChatbotHistoryController::class, 'index'])->name('history.index');
        Route::get('/history/{message}/correct', [ChatbotHistoryController::class, 'correct'])->name('history.correct');

        Route::get('/suggestions', [ChatbotSuggestionController::class, 'index'])->name('suggestions.index');
        Route::post('/suggestions', [ChatbotSuggestionController::class, 'store'])->name('suggestions.store');
        Route::put('/suggestions/{suggestion}', [ChatbotSuggestionController::class, 'update'])->name('suggestions.update');
        Route::delete('/suggestions/{suggestion}', [ChatbotSuggestionController::class, 'destroy'])->name('suggestions.destroy');

        Route::get('/settings', [ChatbotSettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [ChatbotSettingsController::class, 'update'])->name('settings.update');
    });

    Route::resource('users', AdminUserController::class)
        ->only(['index', 'edit', 'update', 'destroy'])
        ->parameters(['users' => 'managedUser'])
        ->names('admin.users');

    Route::patch('/feedback-departments/{department}/toggle', [FeedbackDepartmentController::class, 'toggle'])
        ->name('admin.departments.toggle');
    Route::resource('feedback-departments', FeedbackDepartmentController::class)
        ->except(['create', 'show'])
        ->parameters(['feedback-departments' => 'department'])
        ->names('admin.departments');

    Route::resource('queries', AdminQueryController::class)
        ->only(['index', 'show', 'update', 'destroy'])
        ->names('admin.queries');

    Route::patch('/registration-programs/{program}/toggle', [RegistrationProgramController::class, 'toggle'])
        ->name('admin.programs.toggle');
    Route::resource('registration-programs', RegistrationProgramController::class)
        ->only(['index', 'store', 'edit', 'update', 'destroy'])
        ->parameters(['registration-programs' => 'program'])
        ->names('admin.programs');

    Route::get('/profile', [AdminProfileController::class, 'edit'])
        ->name('admin.profile.edit');
    Route::put('/profile', [AdminProfileController::class, 'update'])
        ->name('admin.profile.update');
    
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
    Route::post('/header-menu/loader-settings', [HeaderMenuController::class, 'updateLoaderSettings'])
        ->name('header-menu.loader-settings.update');
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
    Route::post('/header-menu-pages/{page}/academic-calendar-tables', [AcademicCalendarController::class, 'store'])
        ->name('academic-calendar-tables.store');
    Route::put('/academic-calendar-tables/{calendarTable}', [AcademicCalendarController::class, 'update'])
        ->name('academic-calendar-tables.update');
    Route::delete('/academic-calendar-tables/{calendarTable}', [AcademicCalendarController::class, 'destroy'])
        ->name('academic-calendar-tables.destroy');

    Route::post('/header-menu-pages/{page}/departments', [AcademicDepartmentController::class, 'store'])
        ->name('departments.store');
    Route::put('/departments/{department}', [AcademicDepartmentController::class, 'update'])
        ->name('departments.update');
    Route::delete('/departments/{department}', [AcademicDepartmentController::class, 'destroy'])
        ->name('departments.destroy');

    Route::post('/header-menu-pages/{page}/gallery', [PageGalleryController::class, 'store'])
        ->name('page-gallery.store');
    Route::put('/page-gallery/{galleryImage}', [PageGalleryController::class, 'update'])
        ->name('page-gallery.update');
    Route::delete('/page-gallery/{galleryImage}', [PageGalleryController::class, 'destroy'])
        ->name('page-gallery.destroy');

    Route::post('/header-menu-pages/{page}/event-albums', [EventAlbumController::class, 'store'])
        ->name('event-albums.store');
    Route::put('/event-albums/{album}', [EventAlbumController::class, 'update'])
        ->name('event-albums.update');
    Route::delete('/event-albums/{album}', [EventAlbumController::class, 'destroy'])
        ->name('event-albums.destroy');
    Route::get('/event-albums/{album}/photos', [EventAlbumController::class, 'photos'])
        ->name('event-albums.photos');
    Route::post('/event-albums/{album}/photos', [EventAlbumController::class, 'storePhotos'])
        ->name('event-albums.photos.store');
    Route::put('/event-album-images/{image}', [EventAlbumController::class, 'updatePhoto'])
        ->name('event-album-images.update');
    Route::delete('/event-album-images/{image}', [EventAlbumController::class, 'destroyPhoto'])
        ->name('event-album-images.destroy');

    Route::post('/header-menu-pages/{page}/elibrary-resources', [ElibraryResourceController::class, 'store'])
        ->name('elibrary-resources.store');
    Route::put('/elibrary-resources/{resource}', [ElibraryResourceController::class, 'update'])
        ->name('elibrary-resources.update');
    Route::delete('/elibrary-resources/{resource}', [ElibraryResourceController::class, 'destroy'])
        ->name('elibrary-resources.destroy');

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
