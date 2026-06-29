<?php

use App\Http\Controllers\Web\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Web\Auth\RegisteredUserController;
use App\Http\Controllers\Web\AccountController;
use App\Http\Controllers\Web\AssignmentResultController;
use App\Http\Controllers\Web\AssignmentManagementController;
use App\Http\Controllers\Web\AssignmentQuestionManagementController;
use App\Http\Controllers\Web\LibraryController;
use App\Http\Controllers\Web\ModalSearchController;
use App\Http\Controllers\Web\ReadingClassManagementController;
use App\Http\Controllers\Web\TextContentController;
use App\Http\Controllers\Web\TextCommentController;
use App\Http\Controllers\Web\TextManagementController;
use App\Http\Controllers\Web\TextTopicManagementController;
use App\Http\Controllers\Web\TextWriterController;
use App\Http\Controllers\Web\UserManagementController;
use App\Http\Controllers\Web\UserReadingClassController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LibraryController::class, 'index'])->name('home');
Route::get('/texts/{text}/content', [TextContentController::class, 'show'])->name('texts.content.show');
Route::get('/texts/{text}/content/preview-images/{filename}', [TextContentController::class, 'previewImage'])
    ->where('filename', 'img-[0-9]+\.(png|jpg|jpeg|gif|webp|svg)')
    ->name('texts.content.preview-image');
Route::get('/texts/{text}/files/{filename}', [TextContentController::class, 'serveFile'])
    ->name('texts.files.serve');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/account', [AccountController::class, 'show'])->name('account.show');
    Route::put('/account', [AccountController::class, 'update'])->name('account.update');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::post('/texts/{text}/comments', [TextCommentController::class, 'store'])->name('texts.comments.store');
    Route::delete('/texts/{text}/comments/{comment}', [TextCommentController::class, 'destroy'])->name('texts.comments.destroy');
});

Route::get('/search/modal', ModalSearchController::class)->name('search.modal');


Route::middleware(['auth', 'user'])->group(function (): void {
    Route::get('/texts/{text}/download', [TextContentController::class, 'download'])->name('texts.content.download');
    Route::get('/my-classes', [UserReadingClassController::class, 'index'])->name('user.reading-classes.index');
    Route::get('/my-classes/{readingClass}', [UserReadingClassController::class, 'show'])->name('user.reading-classes.show');
    Route::get('/my-classes/{readingClass}/assignments/{assignment}/take', [UserReadingClassController::class, 'takeAssignment'])->name('user.reading-classes.assignments.take');
    Route::post('/my-classes/{readingClass}/assignments/{assignment}/save', [UserReadingClassController::class, 'saveAssignmentAnswers'])->name('user.reading-classes.assignments.save');
});

Route::middleware(['auth', 'teacher'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function (): void {
        Route::get('users', [UserManagementController::class, 'index'])->name('users.index');
        Route::post('users', [UserManagementController::class, 'store'])->name('users.store');
        Route::put('users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::patch('users/{user}', [UserManagementController::class, 'update']);
        Route::delete('users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');

        Route::get('text-topics', [TextTopicManagementController::class, 'index'])->name('text-topics.index');
        Route::post('text-topics', [TextTopicManagementController::class, 'store'])->name('text-topics.store');
        Route::get('text-topics/export', [TextTopicManagementController::class, 'export'])->name('text-topics.export');
        Route::get('text-topics/template', [TextTopicManagementController::class, 'downloadTemplate'])->name('text-topics.template');
        Route::post('text-topics/import', [TextTopicManagementController::class, 'import'])->name('text-topics.import');
        Route::put('text-topics/{textTopic}', [TextTopicManagementController::class, 'update'])->name('text-topics.update');
        Route::patch('text-topics/{textTopic}', [TextTopicManagementController::class, 'update']);
        Route::delete('text-topics/{textTopic}', [TextTopicManagementController::class, 'destroy'])->name('text-topics.destroy');

        Route::get('texts', [TextManagementController::class, 'index'])->name('texts.index');
        Route::get('texts/export', [TextManagementController::class, 'export'])->name('texts.export');
        Route::get('texts/template', [TextManagementController::class, 'downloadTemplate'])->name('texts.template');
        Route::post('texts/import', [TextManagementController::class, 'import'])->name('texts.import');
        Route::post('texts', [TextManagementController::class, 'store'])->name('texts.store');
        Route::put('texts/{text}', [TextManagementController::class, 'update'])->name('texts.update');
        Route::patch('texts/{text}', [TextManagementController::class, 'update']);
        Route::delete('texts/{text}', [TextManagementController::class, 'destroy'])->name('texts.destroy');
        Route::get('texts/{text}/writer', [TextWriterController::class, 'edit'])->name('texts.writer.edit');
        Route::put('texts/{text}/writer', [TextWriterController::class, 'update'])->name('texts.writer.update');
        Route::post('texts/{text}/writer/import-docx', [TextWriterController::class, 'importDocx'])->name('texts.writer.import-docx');
        Route::get('texts/{text}/writer/export-docx', [TextWriterController::class, 'exportDocx'])->name('texts.writer.export-docx');
        Route::get('texts/{text}/writer/preview-images/{filename}', [TextWriterController::class, 'previewImage'])
            ->where('filename', 'img-[0-9]+\.(png|jpg|jpeg|gif|webp|svg)')
            ->name('texts.writer.preview-image');
        Route::post('texts/{text}/writer/files', [TextWriterController::class, 'storeFiles'])->name('texts.writer.store-files');
        Route::delete('texts/{text}/writer/files/{file}', [TextWriterController::class, 'destroyFile'])->name('texts.writer.destroy-file');
        Route::post('texts/{text}/writer/links', [TextWriterController::class, 'storeLink'])->name('texts.writer.store-link');
        Route::delete('texts/{text}/writer/links/{link}', [TextWriterController::class, 'destroyLink'])->name('texts.writer.destroy-link');

        Route::get('reading-classes', [ReadingClassManagementController::class, 'index'])->name('reading-classes.index');
        Route::get('reading-classes/{readingClass}/results', [AssignmentResultController::class, 'index'])->name('reading-classes.results');
        Route::post('reading-classes/{readingClass}/submissions/{submission}/grade', [AssignmentResultController::class, 'grade'])->name('reading-classes.results.grade');
        Route::get('reading-classes/{readingClass}/answer-files/{file}/download', [AssignmentResultController::class, 'downloadFile'])->name('reading-classes.results.files.download');
        Route::post('reading-classes', [ReadingClassManagementController::class, 'store'])->name('reading-classes.store');
        Route::put('reading-classes/{readingClass}', [ReadingClassManagementController::class, 'update'])->name('reading-classes.update');
        Route::patch('reading-classes/{readingClass}', [ReadingClassManagementController::class, 'update']);
        Route::delete('reading-classes/{readingClass}', [ReadingClassManagementController::class, 'destroy'])->name('reading-classes.destroy');

        Route::get('assignments', [AssignmentManagementController::class, 'index'])->name('assignments.index');
        Route::post('assignments', [AssignmentManagementController::class, 'store'])->name('assignments.store');
        Route::put('assignments/{assignment}', [AssignmentManagementController::class, 'update'])->name('assignments.update');
        Route::patch('assignments/{assignment}', [AssignmentManagementController::class, 'update']);
        Route::delete('assignments/{assignment}', [AssignmentManagementController::class, 'destroy'])->name('assignments.destroy');

        Route::get('assignments/{assignment}/questions', [AssignmentQuestionManagementController::class, 'index'])
            ->name('assignments.questions.index');
        Route::post('assignments/{assignment}/questions', [AssignmentQuestionManagementController::class, 'store'])
            ->name('assignments.questions.store');
        Route::put('assignments/{assignment}/questions/{question}', [AssignmentQuestionManagementController::class, 'update'])
            ->name('assignments.questions.update');
        Route::patch('assignments/{assignment}/questions/{question}', [AssignmentQuestionManagementController::class, 'update']);
        Route::delete('assignments/{assignment}/questions/{question}', [AssignmentQuestionManagementController::class, 'destroy'])
            ->name('assignments.questions.destroy');
    });
