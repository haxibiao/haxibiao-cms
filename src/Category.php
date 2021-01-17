<?php

namespace Haxibiao\Cms;

use Haxibiao\Cms\Traits\PlayWithCms;
use Haxibiao\Cms\Traits\StickableItem;
use Haxibiao\Content\Category as BaseCategory;

class Category extends BaseCategory
{
    use PlayWithCms;
    use StickableItem;
}
