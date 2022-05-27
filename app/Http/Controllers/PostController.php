<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// --model=Postとモデル名をつけてコントローラを作成したため、ファイル上部にPostモデルのuse宣言が入る
use App\Models\Post;
// PostControllerとCommentモデルを紐づける
use App\Models\Comment;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // 投稿一覧表示のためのメソッド
    public function index()
    {
        // Postというテーブルからデータを取得して、$postsという変数に代入し、データの並び順を作成した日の降順に表示してという命令。その際にログイン中のユーザーを$userに代入する。compact関数を用いて、表示する画面に変数を引き渡す。
        $posts=Post::orderBy('created_at','desc')->get();
        $user=auth()->user();
        return view('post.index', compact('posts', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs=$request->validate([
            'title'=>'required|max:255',
            'body'=>'required|max:1000',
            'image'=>'image|max:1024'
        ]);

        $post=new Post();
        $post->title=$request->title;
        $post->body=$request->body;
        $post->user_id=auth()->user()->id;

        // 画像ファイル保存のための処理（シンボリックリンク使用）
        if (request('image')){
            $original = request()->file('image')->getClientOriginalName();
             // 日時追加
            $name = date('Ymd_His').'_'.$original;
            request()->file('image')->move('storage/images', $name);
            $post->image = $name;
        }

        $post->save();
        return redirect()->route('post.create')->with('message', '投稿を作成しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    // showメソッドを用いて、投稿を表示する。()の中にどの情報を受け取るかを指定する。
    public function show(Post $post)
    {
        // ビューから受け取った情報をビューに受け渡して表示する
        return view('post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('post.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Post $post)
    {
        $inputs=$request->validate([
            'title'=>'required|max:255',
            'body'=>'required|max:1000',
            'image'=>'image|max:1024'
        ]);

        $post->title=$request->title;
        $post->body=$request->body;

        if(request('image')){
            $original=request()->file('image')->getClientOriginalName();
            $name=date('Ymd_His').'_'.$original;
            $file=request()->file('image')->move('storage/images', $name);
            $post->image=$name;
        }

        $post->save();

        return redirect()->route('post.show', $post)->with('message', '投稿を更新しました');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        // 投稿を削除する際には、コメントも削除するように設定
        $post->comments()->delete();
        $post->delete();
        return redirect()->route('post.index')->with('message', '投稿を削除しました');
    }

    // リソースコントローラに元々ある7つのメソッド以外にもメソッドを追加可能。今回は、mypostメソッド（自分の投稿一覧を表示する）を追加する。
    public function mypost() {
        // 下記の記載によってpostsテーブルの中の user_idカラムが、現在ログイン中のユーザーと同じデータを取得できる。
        $user=auth()->user()->id;
        $posts=Post::where('user_id', $user)->orderBy('created_at', 'desc')->get();
        return view('post.mypost', compact('posts'));
    }

    // リソースコントローラに元々ある7つのメソッド以外にもメソッドを追加可能。今回は、mycommnetメソッド（自分の投稿一覧を表示する）を追加する。
    public function mycomment() {
        $user=auth()->user()->id;
        $comments=Comment::where('user_id', $user)->orderBy('created_at', 'desc')->get();
        return view('post.mycomment', compact('comments'));
    }
}
