<?php

namespace Haxibiao\Cms;

use Haxibiao\Cms\Traits\PlayWithCms;
use Haxibiao\Cms\Traits\StickableItem;
use Haxibiao\Content\Article as BaseArticle;

class Article extends BaseArticle
{
    use PlayWithCms;
    use StickableItem;
}
