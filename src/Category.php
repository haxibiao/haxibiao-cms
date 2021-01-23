<?php

namespace Haxibiao\Cms;

use Haxibiao\Cms\Traits\Stickable;
use Haxibiao\Cms\Traits\WithCms;
use Haxibiao\Content\Category as BaseCategory;

class Category extends BaseCategory
{
    use WithCms;
    use Stickable;
}
