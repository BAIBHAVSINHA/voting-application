<?php

use App\Http\Controllers\CandidatesContoller;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [CandidatesContoller::class, 'home'])->name("home");



Route::get('/createCandidateForm', [CandidatesContoller::class, 'createCandidateForm'])->name('createCandidateForm');

Route::get('/voting', [CandidatesContoller::class, 'votingPage'])->name('votingPage');


Route::post('/createCandidate',[CandidatesContoller::class, 'createCandidate'])->name('createCandidate');
Route::post('/castYourVote',[CandidatesContoller::class, 'castYourVote'])->name('castYourVote')->middleware('auth');


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
