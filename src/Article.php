<?php

namespace Haxibiao\Cms;

use Haxibiao\Cms\Traits\Stickable;
use Haxibiao\Cms\Traits\WithCms;
use Haxibiao\Content\Article as BaseArticle;

class Article extends BaseArticle
{
    use WithCms;
    use Stickable;
}
