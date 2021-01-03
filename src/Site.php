<?php

namespace Haxibiao\Cms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    //专题
    public function categories($userAppModel = false)
    {
        //允许App层重写覆盖属性
        if ($userAppModel) {
            return $this->siteable(\App\Category::class);
        }
        return $this->siteable(Category::class);
    }

    //文章
    public function articles($userAppModel = false)
    {
        //允许App层重写覆盖属性
        if ($userAppModel) {
            return $this->siteable(\App\Article::class);
        }
        return $this->siteable(Article::class);
    }

    //动态
    public function posts($userAppModel = false)
    {
        //允许App层重写覆盖属性
        if ($userAppModel) {
            return $this->siteable(\App\Post::class);
        }
        return $this->siteable(Post::class);
    }

    //电影
    public function movies($userAppModel = false)
    {
        //允许App层重写覆盖属性
        if ($userAppModel) {
            return $this->siteable(\App\Movie::class);
        }
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
