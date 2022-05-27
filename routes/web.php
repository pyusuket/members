<?php

use Illuminate\Support\Facades\Route;

// PostControllerを使用するためのuse宣言
use App\Http\Controllers\PostController;

// ComementControllerを使用するためのuse宣言
use App\Http\Controllers\CommentController;

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
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

// PostControllerの中に新しくmypostメソッドを作成し、それを呼び出すためのルーティング➡順番に注意が必要。
Route::get('post/mypost', [PostController::class, 'mypost'])->name('post.mypost');

// PostControllerの中に新しくmycommentメソッドを作成し、それを呼び出すためのルーティング➡順番に注意が必要。
Route::get('post/mycomment', [PostController::class, 'mycomment'])->name('post.mycomment');

// Postリソースコントローラのルーティング
Route::resource('post', PostController::class);

// Commentコントローラのルーティング➡リソースコントローラとは違い、1つのコントローラへ処理を誘導する。
Route::post('post/comment/store', [CommentController::class, 'store'])->name('comment.store');

