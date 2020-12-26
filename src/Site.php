<?php

namespace Haxibiao\Cms;

use Haxibiao\Cms\Article;
use Haxibiao\Cms\Movie;
use Haxibiao\Cms\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $guarded = [];

    //专题
    public function categories()
    {
        return $this->morphedByMany(Category::class, 'siteable')->withPivot([
            'baidu_pushed_at',
        ]);
    }

    //文章
    public function articles()
    {
        return $this->morphedByMany(Article::class, 'siteable')->withPivot([
            'baidu_pushed_at',
        ]);
    }

    //动态
    public function posts()
    {
        return $this->morphedByMany(Post::class, 'siteable')->withPivot([
            'baidu_pushed_at',
        ]);
    }

    //电影
    public function movies()
    {
        return $this->morphedByMany(Movie::class, 'siteable')->withPivot([
            'baidu_pushed_at',
        ]);
    }
}
