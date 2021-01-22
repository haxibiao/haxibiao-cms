<?php

namespace Haxibiao\Cms;

use Haxibiao\Cms\Traits\StickableItem;
use Haxibiao\Cms\Traits\WithCms;
use Haxibiao\Content\Post as BasePost;

class Post extends BasePost
{
    use WithCms;
    use StickableItem;
}
