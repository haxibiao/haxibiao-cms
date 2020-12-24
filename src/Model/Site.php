<?php

namespace Haxibiao\Cms\Model;

use App\Article;
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
}
