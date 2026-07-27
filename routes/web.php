<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\PolicyVerificationController;
use App\Http\Controllers\MswdoAssessmentController;
use App\Http\Controllers\ExamResultController;
use App\Http\Controllers\OrientationController;
use App\Http\Controllers\WasteComplianceController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\AppealController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EditProfileController;
use App\Http\Controllers\RequirementUploadController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\StudentAccountController;
use App\Http\Controllers\RequirementReviewController;
use App\Http\Controllers\AuditLogController;




Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [EditProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [EditProfileController::class, 'update'])->name('profile.update');
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/guides', [HomeController::class, 'guides'])->name('guides');


Route::post('/requirements/{requirement}/approve', [RequirementReviewController::class, 'approve'])
    ->name('admin.requirements.approve');

Route::post('/requirements/{requirement}/reject', [RequirementReviewController::class, 'reject'])
    ->name('admin.requirements.reject');

Route::post('/requirements/{requirement}/upload', [RequirementUploadController::class, 'store'])->name('requirements.upload');
    Route::delete('/requirements/{requirement}', [RequirementUploadController::class, 'destroy'])->name('requirements.destroy');

// -----------------------------
// Authentication
// -----------------------------
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);


Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// NOTE: Self-service registration has been removed.
// Student accounts are now created only by an admin — see admin.students.* routes below.

// -----------------------------
// Public-facing (applicant side) — no login required
// -----------------------------

Route::get('/applicants/{applicant}', [ApplicantController::class, 'show'])->name('applicants.show');
Route::post('/appeals', [AppealController::class, 'store'])->name('appeals.store');

// -----------------------------
// Complete Profile — requires login (Step 2, after admin creates the account)
// -----------------------------
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard', [DashboardController::class, 'store'])->name('applicants.store');
});

// -----------------------------
// Admin side — requires login
// -----------------------------
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [ApplicantController::class, 'index'])->name('admin.dashboard');

    Route::get('/students/create', [StudentAccountController::class, 'create'])->name('admin.students.create');
    Route::post('/students', [StudentAccountController::class, 'store'])->name('admin.students.store');

    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('admin.announcements.index');
    Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('admin.announcements.create');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('admin.announcements.store');
    Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('admin.announcements.edit');
    Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('admin.announcements.update');
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');

    Route::post('/applicants/{applicant}/verify-policy', [PolicyVerificationController::class, 'verify'])
        ->name('admin.verify-policy');

    Route::post('/applicants/{applicant}/mswdo-assess', [MswdoAssessmentController::class, 'assess'])
        ->name('admin.mswdo-assess');

    Route::post('/applicants/{applicant}/exam-result', [ExamResultController::class, 'store'])
        ->name('admin.exam-result');

    Route::post('/applicants/{applicant}/orientation', [OrientationController::class, 'complete'])
        ->name('admin.orientation');

    Route::post('/applicants/{applicant}/waste-compliance', [WasteComplianceController::class, 'store'])
        ->name('admin.waste-compliance');

    Route::post('/applicants/{applicant}/payout', [PayoutController::class, 'release'])
        ->name('admin.payout');

    // Appeal resolution
    Route::post('/appeals/{appeal}/approve', [AppealController::class, 'approve'])
        ->name('admin.appeals.approve');

    Route::post('/appeals/{appeal}/reject', [AppealController::class, 'reject'])
        ->name('admin.appeals.reject');

    // Audit log
    Route::get('/audit-log', [AuditLogController::class, 'index'])
        ->name('admin.audit-log.index');
});