<?php

namespace Haxibiao\Cms;

use App\Article;
use App\Movie;
use App\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $guarded = [];

    //专题
    public function categories()
    {
        return $this->siteable(Category::class);
    }

    //文章
    public function articles()
    {
        return $this->siteable(Article::class);
    }

    //动态
    public function posts()
    {
        return $this->siteable(Post::class);
    }

    //电影
    public function movies()
    {
        return $this->siteable(Movie::class);
    }

    public function siteable($related)
    {
        return $this->morphedByMany($related, 'siteable')->withPivot([
            'baidu_pushed_at',
        ]);
    }

    public function related()
    {
        return $this->hasMany(Siteable::class);
    }
}
