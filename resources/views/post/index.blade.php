<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            投稿の一覧
        </h2>

        <x-message :message="session('message')" />

    </x-slot>

    {{-- 投稿一覧表示用のコード --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 ">
        {{$user->name}}さん、こんにちは！
        @foreach ($posts as $post)
            <div class="mx-4 sm:p-8">
                <div class="mt-4">
                    <div class="bg-white w-full  rounded-2xl px-10 py-8 shadow-lg hover:shadow-2xl transition duration-500">
                        <div class="mt-4">
                            <h1 class="text-lg text-gray-700 font-semibold hover:underline cursor-pointer">
                                {{-- タイトルにリンクを追加する --}}
                                <a href="{{route('post.show', $post)}}">{{ $post->title }}</a>
                            </h1>
                            <hr class="w-full">
                            {{-- 投稿の一覧表示をする際に、長い本文を省略して表示する --}}
                            <p class="mt-4 text-gray-600 py-4">{{Str::limit($post->body, 100, '...')}}</p>
                            <div class="text-sm font-semibold flex flex-row-reverse">
                                <p> {{ $post->user->name }} • {{$post->created_at->diffForHumans()}}</p>
                            </div>
                             {{-- 追加部分（コメントがあった場合に左下に表示されるよう設定） --}}
                            <hr class="w-full mb-2">
                            @if ($post->comments->count())
                            <span class="badge">
                                返信 {{$post->comments->count()}}件
                            </span>
                            @else
                            <span>コメントはまだありません。</span>
                            @endif
                            <a href="{{route('post.show', $post)}}" style="color:white;">
                                <x-button class="float-right">コメントする</x-button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
