<?php

namespace Haxibiao\Cms\Traits;

use Haxibiao\Cms\Article;
use Haxibiao\Cms\Category;
use Haxibiao\Cms\Movie;
use Haxibiao\Cms\Post;
use Haxibiao\Cms\Stickable;

trait StickableSite
{
    public function stickyArticles()
    {
        return $this->stickable(Article::class);
    }

    public function stickyMovies()
    {
        return $this->stickable(Movie::class);
    }

    public function stickyPosts()
    {
        return $this->stickable(Post::class);
    }

    public function stickyCategories()
    {
        return $this->stickable(Category::class);
    }

    public function stickables()
    {
        return $this->hasMany(Stickable::class);
    }

    public function stickable($related)
    {
        return $this->morphedByMany($related, 'item', 'stickables')
            ->withPivot(['name', 'page', 'area'])
            ->withTimestamps();
    }
}
