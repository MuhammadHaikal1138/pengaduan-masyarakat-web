<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

use function Laravel\Prompts\progress;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('Auth.login');
})->name('Page.login');

// Login
Route::post('/Login', [AuthController::class, 'Login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/Regist', [AuthController::class, 'PageRegist'])->name('page.regist');
Route::post('/Register', [AuthController::class, 'Register'])->name('register');

// Report and Article
Route::get('/report/article', [ReportController::class, 'PageArticle'])->name('report.article');
Route::get('/report/article/info/{id}', [ReportController::class, 'PageArticleInfo'])->name('report.article.info');
Route::get('/report/create', [ReportController::class, 'ReportCreate'])->name('report.create');
Route::post('/report/store', [ReportController::class, 'ReportStore'])->name('report.store');
Route::delete('/report/delete/{report}', [ReportController::class, 'destroy'])->name('report.destroy');

Route::get('/report/me/', [ReportController::class, 'ReportMe'])->name('report.me');

// commment dan vote
Route::post('/reports/{id}/vote', [ReportController::class, 'vote'])->name('reports.vote');
Route::post('comment/{report}', [CommentController::class, 'store'])->name('comment.store');

// staff
Route::get('/report', [StaffController::class, 'report'])->name('report.staff');
Route::post('/response/report/{id}', [ResponseController::class, 'response'])->name('response.staff');
Route::get('/report/response/{ReportId}', [ResponseController::class, 'responseIndex'])->name('response.index');
Route::post('/response/add/{report}', [ResponseController::class, 'add'])->name('response.add');
Route::delete('/response/progress/delete/{id}', [ResponseController::class, 'destroyProgress'])->name('progress.delete');
Route::put('/response/progress/update/{id}', [ResponseController::class, 'update'])->name('progress.update');


// Export
Route::get('export-report', [ExportController::class, 'export'])->name('export');
