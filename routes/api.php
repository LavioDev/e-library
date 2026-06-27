<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\AssignmentGradingController;
use App\Http\Controllers\Api\AssignmentSubmissionController;
use App\Http\Controllers\Api\TextController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->as('api.auth.')
    ->group(function (): void {
        Route::post('/login', [AuthController::class, 'login'])->name('login');

        Route::middleware('auth:sanctum')->group(function (): void {
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
            Route::get('/me', [AuthController::class, 'me'])->name('me');
        });
    });

Route::middleware(['auth:sanctum', 'teacher'])
    ->prefix('texts')
    ->as('api.texts.')
    ->group(function (): void {
        Route::get('/', [TextController::class, 'index'])->name('index');
        Route::post('/', [TextController::class, 'store'])->name('store');
        Route::get('/{text}', [TextController::class, 'show'])->name('show');
        Route::put('/{text}', [TextController::class, 'update'])->name('update');
        Route::delete('/{text}', [TextController::class, 'destroy'])->name('destroy');
    });

Route::middleware(['auth:sanctum', 'teacher'])
    ->prefix('assignments')
    ->as('api.assignments.')
    ->group(function (): void {
        Route::get('/', [AssignmentController::class, 'index'])->name('index');
        Route::post('/', [AssignmentController::class, 'store'])->name('store');
        Route::get('/{assignment}', [AssignmentController::class, 'show'])->name('show');
        Route::put('/{assignment}', [AssignmentController::class, 'update'])->name('update');
        Route::delete('/{assignment}', [AssignmentController::class, 'destroy'])->name('destroy');
    });

Route::middleware(['auth:sanctum', 'user'])
    ->as('api.assignment-submissions.')
    ->group(function (): void {
        Route::post('/assignments/{assignment}/submissions/draft', [AssignmentSubmissionController::class, 'createDraft'])
            ->name('create-draft');
        Route::put('/assignment-submissions/{submission}/answers', [AssignmentSubmissionController::class, 'saveAnswers'])
            ->name('save-answers');
        Route::post('/assignment-submissions/{submission}/submit', [AssignmentSubmissionController::class, 'submit'])
            ->name('submit');
    });

Route::middleware(['auth:sanctum', 'teacher'])
    ->as('api.assignment-grading.')
    ->group(function (): void {
        Route::post('/assignment-submissions/{submission}/grade', [AssignmentGradingController::class, 'grade'])
            ->name('grade');
    });
