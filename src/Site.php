<?php

namespace Haxibiao\Cms;

use Haxibiao\Cms\Article;
use Haxibiao\Cms\Movie;
use Haxibiao\Cms\Post;
use Haxibiao\Cms\Video;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function articles()
    {
        return $this->morphedByMany(Article::class, 'siteable')->withPivot([
            'baidu_pushed_at',
        ]);
    }

    //视频动态
    public function posts()
    {
        return $this->morphedByMany(Post::class, 'siteable')->withPivot([
            'baidu_pushed_at',
        ]);
    }

    public function videos()
    {
        return $this->morphedByMany(Video::class, 'siteable')->withPivot([
            'baidu_pushed_at',
        ]);
    }

    public function movies()
    {
        return $this->morphedByMany(Movie::class, 'siteable')->withPivot([
            'baidu_pushed_at',
        ]);
    }
}
