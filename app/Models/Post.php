<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // 1人のユーザーに対しての投稿内容をリレーションする。（1対多の関係であるため、）
    use HasFactory;
    protected $fillable = [
        'title',
        'body',
        'user_id',
        'image',
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }



    public function comments() {
        return $this->hasMany(Comment::class);
    }

}
