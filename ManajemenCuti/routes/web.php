<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\ManajemenUserController;
use App\Http\Controllers\Admin\CutiController;
use App\Http\Controllers\Admin\LaporanMasalahController;
use App\Http\Controllers\User\LeaveRequestController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\Leader\LeaderDashboardController;
use App\Http\Controllers\Leader\LeaderLeaveVerificationController;
use App\Http\Controllers\Leader\LeaderLeaveHistoryController;
use App\Http\Controllers\Hrd\HrdController;


// =======================
// ROOT & DASHBOARD UTAMA
// =======================

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if (! $user) {
        return redirect()->route('login');
    }

    return match ($user->role) {
        'Admin'  => redirect()->route('admin.dashboard'),
        'HRD'    => redirect()->route('hrd.dashboard'),
        'Leader' => redirect()->route('leader.dashboard'),
        'User'   => redirect()->route('user.dashboard'),
        default  => view('dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');


// =======================
// PROFILE
// =======================

Route::middleware('auth')->group(function () {

    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');

});


// =======================
// ADMIN (prefix /admin, name admin.)
// =======================

Route::middleware(['auth', 'role:Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        Route::resource('users', ManajemenUserController::class)
            ->names('manajemen_user')
            ->parameters(['users' => 'manajemen_user']);

        Route::patch('/users/{manajemen_user}/toggle-active',
            [ManajemenUserController::class, 'toggleActive']
        )->name('manajemen_user.toggle_active');

        Route::get('/division',                 [DivisionController::class, 'index'])->name('division.index');
        Route::get('/division/create',          [DivisionController::class, 'create'])->name('division.create');
        Route::post('/division',                [DivisionController::class, 'store'])->name('division.store');
        Route::get('/division/{division}/edit', [DivisionController::class, 'edit'])->name('division.edit');
        Route::put('/division/{division}',      [DivisionController::class, 'update'])->name('division.update');
        Route::delete('/division/{division}',   [DivisionController::class, 'destroy'])->name('division.destroy');
        Route::get('/division/{division}/members', [DivisionController::class, 'members'])->name('division.members');
        Route::post('/division/{division}/members', [DivisionController::class, 'addMember'])->name('division.members.add');
        Route::delete('/division/{division}/members/{user}', [DivisionController::class, 'removeMember']) ->name('division.members.remove');

        Route::get('/cuti',        [CutiController::class, 'index'])->name('cuti.index');
        Route::get('/cuti/{cuti}', [CutiController::class, 'show'])->name('cuti.show');

        Route::get('/laporan_masalah', [LaporanMasalahController::class, 'index'])->name('laporan_masalah.index');
    });


// =======================
// HRD
// =======================

Route::middleware(['auth', 'verified', 'role:HRD'])->group(function () {

    Route::get('/hrd/dashboard', [HrdController::class, 'index'])->name('hrd.dashboard');

    Route::get('/hrd/approvals', [HrdController::class, 'approvals'])->name('approvals.index');
    Route::post('/hrd/approvals/{leaveRequest}/process', [HrdController::class, 'process'])->name('approvals.process');
    Route::post('/hrd/approvals/bulk', [HrdController::class, 'bulkProcess'])->name('approvals.bulk');

    Route::get('/hrd/history', [HrdController::class, 'history'])->name('history.index');
    Route::get('/hrd/reports', [HrdController::class, 'reports'])->name('hrd.reports.index');
    Route::get('/hrd/employees', [HrdController::class, 'employees'])->name('hrd.employees.index');
    Route::get('/hrd/divisions', [HrdController::class, 'divisions'])->name('hrd.divisions.index');

});


// =======================
// LEADER
// =======================

Route::middleware(['auth', 'role:Leader'])->group(function () {

    Route::get('/leader/dashboard', [LeaderDashboardController::class, 'index'])->name('leader.dashboard');

    Route::get('/verifications', [LeaderLeaveVerificationController::class, 'index'])->name('verifications.index');
    Route::post('/verifications/{leaveRequest}/approve', [LeaderLeaveVerificationController::class, 'approve'])->name('verifications.approve');
    Route::post('/verifications/{leaveRequest}/reject', [LeaderLeaveVerificationController::class, 'reject'])->name('verifications.reject');

    Route::get('/verifications/{leaveRequest}/reject', function ($leaveRequest) {
        return redirect()->route('verifications.index');
    })->name('verifications.reject.get');

    Route::get('/verifications/{leaveRequest}', [LeaderLeaveVerificationController::class, 'show'])->name('verifications.show');

    // Riwayat cuti pribadi Leader (dipakai di layouts: route('leave-history'))
    Route::get('/leader/leave/history', [LeaveRequestController::class, 'history'])->name('leave-history');

    // Ini riwayat cuti anggota divisi (boleh tetap, sesuai controller kamu)
    Route::get('/leader/cuti-saya', [LeaderLeaveHistoryController::class, 'index'])->name('leader.leave.history');

    Route::get('/leader/leave/create', [LeaveRequestController::class, 'create'])->name('leader.leave.create');
    Route::post('/leader/leave', [LeaveRequestController::class, 'store'])->name('leader.leave.store');
    Route::post('/leader/leave/{leave}/cancel', [LeaveRequestController::class, 'cancel'])->name('leader.leave.cancel');

});


// =======================
// USER
// =======================

Route::middleware(['auth'])->group(function () {

    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

    Route::get('/user/leave/create',           [LeaveRequestController::class, 'create'])->name('user.leave.create');
    Route::post('/leave',                      [LeaveRequestController::class, 'store'])->name('leave.store');
    Route::get('/user/leave/history',          [LeaveRequestController::class, 'history'])->name('user.leave.history');

    // Detail cuti (nama lama, kalau kepakai di tempat lain)
    Route::get('/user/leave/{leaveRequest}',   [LeaveRequestController::class, 'show'])->name('user.leave.show');

    // Alias route untuk tombol Detail di riwayat cuti: route('leave.show', $leave->id)
    Route::get('/leave/{leaveRequest}',        [LeaveRequestController::class, 'show'])->name('leave.show');

    Route::post('/leave/{leaveRequest}/cancel',[LeaveRequestController::class, 'cancel'])->name('leave.cancel');

});


// Auth route
require __DIR__.'/auth.php';
