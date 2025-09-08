<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganizationController;

// Auth routes
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::get('/', [OrganizationController::class, 'dashboard'])
    ->middleware('auth.check')
    ->name('dashboard');

// Test route to check authentication
Route::get('/test-auth', function() {
    if (auth()->check()) {
        $user = auth()->user();
        return response()->json([
            'authenticated' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'permissions' => $user->getAllPermissions()
            ]
        ]);
    } else {
        return response()->json(['authenticated' => false]);
    }
})->name('test.auth');



// Organization routes - public access
Route::get('/organization', [OrganizationController::class, 'index'])->name('organization.index');

// Business Logic Demo routes
Route::get('/business-logic', [OrganizationController::class, 'businessLogicDemo'])->name('business.logic.demo');
Route::get('/api/workflows', [OrganizationController::class, 'getWorkflows'])->name('api.workflows');
Route::post('/api/workflows/{workflow}/create-request', [OrganizationController::class, 'createRequest'])->name('api.workflows.create-request');

// Approval Management routes - require authentication
Route::prefix('approval')->name('approval.')->group(function () {
    // Dashboard - view requests permission
    Route::get('/', [\App\Http\Controllers\ApprovalController::class, 'index'])
        ->name('index');
    
    // View all requests - view requests permission  
    Route::get('/requests', [\App\Http\Controllers\ApprovalController::class, 'requests'])
        ->middleware('permission:view_requests')
        ->name('requests');
    
    // View specific request - view requests permission
    Route::get('/requests/{request}', [\App\Http\Controllers\ApprovalController::class, 'show'])
        ->middleware('permission:view_requests')
        ->name('show');
    
    // Create generic request - create requests permission
    Route::get('/create', [\App\Http\Controllers\ApprovalController::class, 'create'])
        ->middleware('permission:create_requests')
        ->name('create');
    Route::post('/store', [\App\Http\Controllers\ApprovalController::class, 'store'])
        ->middleware('permission:create_requests')
        ->name('store');
    
    // Submit request - create requests permission
    Route::post('/requests/{request}/submit', [\App\Http\Controllers\ApprovalController::class, 'submit'])
        ->middleware('permission:create_requests')
        ->name('submit');
    
    // My tasks - approve requests permission
    Route::get('/my-tasks', [\App\Http\Controllers\ApprovalController::class, 'myTasks'])
        ->middleware('permission:approve_requests')
        ->name('my-tasks');
    
    // Process step - approve requests permission  
    Route::post('/steps/{step}/process', [\App\Http\Controllers\ApprovalController::class, 'processStep'])
        ->middleware('permission:approve_requests')
        ->name('process-step');
    
    // Create school - create schools permission
    Route::get('/create-school', [\App\Http\Controllers\ApprovalController::class, 'createSchool'])
        ->middleware('permission:create_schools')
        ->name('create-school');
    Route::post('/create-school', [\App\Http\Controllers\ApprovalController::class, 'storeSchool'])
        ->middleware('permission:create_schools')
        ->name('store-school');
    
    // Create department - propose departments permission
    Route::get('/create-department', [\App\Http\Controllers\ApprovalController::class, 'createDepartment'])
        ->middleware('permission:propose_departments')
        ->name('create-department');
    Route::post('/create-department', [\App\Http\Controllers\ApprovalController::class, 'storeDepartment'])
        ->middleware('permission:propose_departments')
        ->name('store-department');
    
    // API routes
    Route::get('/api/universities/{university}/schools', [\App\Http\Controllers\ApprovalController::class, 'getSchoolsByUniversity'])->name('api.schools');
    Route::get('/api/schools/{school}/departments', [\App\Http\Controllers\ApprovalController::class, 'getDepartmentsBySchool'])->name('api.departments');
});
