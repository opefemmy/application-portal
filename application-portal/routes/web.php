<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ApplicationController as FrontendApplicationController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PageBuilderController;
use App\Http\Controllers\Admin\ProgrammesController;

// Storage link route - creates symlink if not exists
Route::get('/storage-link', function () {
    if (!file_exists(public_path('storage'))) {
        try {
            Artisan::call('storage:link');
            return response()->json(['success' => true, 'message' => 'Storage link created']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    return response()->json(['success' => true, 'message' => 'Storage link already exists']);
});

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/requirements', [HomeController::class, 'requirements'])->name('requirements');
Route::get('/how-to-apply', [HomeController::class, 'howToApply'])->name('how-to-apply');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/track', [HomeController::class, 'track'])->name('track');

Route::get('/apply', [FrontendApplicationController::class, 'showForm'])->name('apply');
Route::post('/apply', [FrontendApplicationController::class, 'submit'])->name('apply.submit');

// Test route to verify application number generation
Route::get('/test-app-number', function () {
    $numbers = [];
    for ($i = 0; $i < 3; $i++) {
        $numbers[] = \App\Models\Application::generateApplicationNumber();
    }
    return response()->json([
        'success' => true,
        'numbers' => $numbers,
        'unique' => count(array_unique($numbers)) === count($numbers)
    ]);
});
Route::get('/application/acknowledge/{application}', [FrontendApplicationController::class, 'acknowledge'])->name('application.acknowledge');
Route::get('/application/print/{application}', [FrontendApplicationController::class, 'publicPrint'])->name('application.print')->middleware('auth:admin');
Route::get('/application/download/{application}', [FrontendApplicationController::class, 'downloadAcknowledge'])->name('application.download');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Auth Routes - without CSRF middleware
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('guest:admin');

    // Other auth routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
        Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
        Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('reset-password');
        Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
        Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
        Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('reset-password');
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Applications
        Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::put('/applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.status');
        Route::post('/applications/bulk-status', [ApplicationController::class, 'bulkUpdateStatus'])->name('applications.bulk-status');
        Route::post('/applications/{application}/shortlist', [ApplicationController::class, 'sendShortlistEmail'])->name('applications.shortlist');
        Route::post('/applications/{application}/reject', [ApplicationController::class, 'sendRejectionEmail'])->name('applications.reject');
        Route::post('/applications/{application}/accept', [ApplicationController::class, 'sendAcceptanceEmail'])->name('applications.accept');
        Route::get('/applications/{application}/print', [ApplicationController::class, 'print'])->name('applications.print');
        Route::delete('/applications/{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');
        Route::post('/applications/bulk-delete', [ApplicationController::class, 'bulkDestroy'])->name('applications.bulk-delete');
        Route::get('/applications/export', [ApplicationController::class, 'export'])->name('applications.export');

        // Documents
        Route::get('/documents/{document}/download', [ApplicationController::class, 'downloadDocument'])->name('documents.download');
        Route::get('/documents/{document}/preview', [ApplicationController::class, 'previewDocument'])->name('documents.preview');

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings/general', [SettingsController::class, 'updateGeneral'])->name('settings.general');
        Route::post('/settings/portal', [SettingsController::class, 'updatePortal'])->name('settings.portal');
        Route::post('/settings/upload', [SettingsController::class, 'updateUpload'])->name('settings.upload');
        Route::post('/settings/maintenance', [SettingsController::class, 'updateMaintenance'])->name('settings.maintenance');

        // Branding Settings
        Route::get('/settings/branding', [SettingsController::class, 'branding'])->name('settings.branding');
        Route::post('/settings/branding', [SettingsController::class, 'updateBranding'])->name('settings.branding.update');

        // Programmes/Positions
        Route::get('/settings/programmes', [ProgrammesController::class, 'index'])->name('settings.programmes');
        Route::post('/settings/programmes', [ProgrammesController::class, 'store'])->name('settings.programmes.store');
        Route::put('/settings/programmes/{index}', [ProgrammesController::class, 'update'])->name('settings.programmes.update');
        Route::delete('/settings/programmes/{index}', [ProgrammesController::class, 'destroy'])->name('settings.programmes.destroy');

        // Page Builder
        Route::get('/settings/pages', [PageBuilderController::class, 'index'])->name('settings.pages');
        Route::get('/settings/pages/{page}/edit', [PageBuilderController::class, 'edit'])->name('settings.pages.edit');
        Route::put('/settings/pages/{page}', [PageBuilderController::class, 'update'])->name('settings.pages.update');
        Route::get('/settings/pages/{page}/preview', [PageBuilderController::class, 'preview'])->name('settings.pages.preview');

        Route::get('/settings/email-templates', [SettingsController::class, 'emailTemplates'])->name('settings.email-templates');
        Route::put('/settings/email-templates/{template}', [SettingsController::class, 'updateEmailTemplate'])->name('settings.email-templates.update');

        Route::get('/settings/form-builder', [SettingsController::class, 'formBuilder'])->name('settings.form-builder');
        Route::post('/settings/form-builder', [SettingsController::class, 'storeFormField'])->name('settings.form-builder.store');
        Route::put('/settings/form-builder/{field}', [SettingsController::class, 'updateFormField'])->name('settings.form-builder.update');
        Route::delete('/settings/form-builder/{field}', [SettingsController::class, 'destroyFormField'])->name('settings.form-builder.destroy');

        // Application Types
        Route::get('/settings/application-types', [SettingsController::class, 'applicationTypes'])->name('settings.application-types');
        Route::post('/settings/application-types', [SettingsController::class, 'storeApplicationType'])->name('settings.application-types.store');
        Route::put('/settings/application-types/{type}', [SettingsController::class, 'updateApplicationType'])->name('settings.application-types.update');
        Route::delete('/settings/application-types/{type}', [SettingsController::class, 'destroyApplicationType'])->name('settings.application-types.destroy');
        Route::get('/settings/application-types/{type}/fields', [SettingsController::class, 'editApplicationTypeFields'])->name('settings.application-types.fields');
        Route::put('/settings/application-types/{type}/fields', [SettingsController::class, 'updateApplicationTypeFields'])->name('settings.application-types.fields.update');

        Route::get('/settings/roles', [SettingsController::class, 'roles'])->name('settings.roles');
        Route::post('/settings/roles', [SettingsController::class, 'storeRole'])->name('settings.roles.store');
        Route::put('/settings/roles/{role}', [SettingsController::class, 'updateRole'])->name('settings.roles.update');
        Route::delete('/settings/roles/{role}', [SettingsController::class, 'destroyRole'])->name('settings.roles.destroy');

        Route::get('/settings/users', [SettingsController::class, 'users'])->name('settings.users');
        Route::post('/settings/users', [SettingsController::class, 'storeUser'])->name('settings.users.store');
        Route::put('/settings/users/{user}', [SettingsController::class, 'updateUser'])->name('settings.users.update');
        Route::delete('/settings/users/{user}', [SettingsController::class, 'destroyUser'])->name('settings.users.destroy');
    });
});