<?php

use App\Http\Controllers\ApplicationRequestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeePortalController;
use App\Http\Controllers\EmployeeRegistrationController;
use App\Http\Controllers\FundingDetailController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\NominationController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PartnerOptionController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TrainingHistoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');

    Route::get('/register', [EmployeeRegistrationController::class, 'create'])->name('register');
    Route::post('/register', [EmployeeRegistrationController::class, 'store'])->name('register.perform');

    Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::get('/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');

    Route::middleware('employee.portal')->prefix('portal')->group(function () {
        Route::get('/', [EmployeePortalController::class, 'dashboard'])->name('portal.dashboard');
        Route::get('/opportunities', [EmployeePortalController::class, 'opportunities'])->name('portal.opportunities');
        Route::post('/opportunities/{opportunity}/apply', [EmployeePortalController::class, 'apply'])->name('portal.opportunities.apply');
        Route::get('/applications', [EmployeePortalController::class, 'applications'])->name('portal.applications');
        Route::get('/profile', [EmployeePortalController::class, 'profile'])->name('portal.profile');
        Route::put('/profile', [EmployeePortalController::class, 'updateProfile'])->name('portal.profile.update');
        Route::get('/training-history', [EmployeePortalController::class, 'trainingHistory'])->name('portal.training-history');
        Route::get('/password', [EmployeePortalController::class, 'password'])->name('portal.password');
        Route::put('/password', [EmployeePortalController::class, 'updatePassword'])->name('portal.password.update');
    });

    Route::middleware('role:system_admin,training_manager,data_entry')->group(function () {
        Route::resource('opportunities', OpportunityController::class)->except(['show']);
        Route::resource('employees', EmployeeController::class)->except(['show']);
        Route::get('employees/{employee}/history', [TrainingHistoryController::class, 'index'])->name('employees.history');
        Route::resource('nominations', NominationController::class)->except(['show']);
        Route::resource('applications', ApplicationRequestController::class)->except(['show']);
        Route::get('nominations/import', [NominationController::class, 'importForm'])->name('nominations.import.form');
        Route::get('nominations/import/template', [NominationController::class, 'importTemplate'])->name('nominations.import.template');
        Route::post('nominations/import', [NominationController::class, 'import'])->name('nominations.import');
        Route::get('nominations/by-opportunity', [NominationController::class, 'byOpportunity'])->name('nominations.by-opportunity');
        Route::post('nominations/by-opportunity', [NominationController::class, 'updateByOpportunity'])->name('nominations.by-opportunity.update');
        Route::get('/reports/opportunities/{opportunity}', [ReportController::class, 'opportunityReport'])->name('reports.opportunity');
        Route::get('/reports/opportunities/{opportunity}/print', [ReportController::class, 'opportunityPrint'])->name('reports.opportunity.print');
    });

    Route::middleware('role:system_admin,training_manager')->group(function () {
        Route::resource('partners', PartnerController::class)->except(['show']);
        Route::get('/partner-options', [PartnerOptionController::class, 'index'])->name('partner-options.index');
        Route::post('/partner-options', [PartnerOptionController::class, 'store'])->name('partner-options.store');
        Route::put('/partner-options/{partnerOption}', [PartnerOptionController::class, 'update'])->name('partner-options.update');
        Route::delete('/partner-options/{partnerOption}', [PartnerOptionController::class, 'destroy'])->name('partner-options.destroy');
        Route::resource('funding', FundingDetailController::class)->except(['show']);
        Route::resource('departments', DepartmentController::class)->except(['show']);
        Route::resource('missions', MissionController::class)->except(['show']);
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/csv', [ReportController::class, 'exportCsv'])->name('reports.export.csv');
        Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
        Route::post('/reports/sync', [ReportController::class, 'syncTrainingHistory'])->name('reports.sync');
    });

    Route::middleware('role:system_admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::post('users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
        Route::post('users/{user}/mark-pending', [UserController::class, 'markPending'])->name('users.mark-pending');
        Route::resource('roles', RoleController::class)->except(['show']);
    });

    Route::middleware('role:viewer')->group(function () {
        Route::get('/reports/viewer', [ReportController::class, 'index'])->name('reports.viewer');
    });
});
