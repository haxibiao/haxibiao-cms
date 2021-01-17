<?php

namespace Haxibiao\Cms;

use Haxibiao\Cms\Traits\PlayWithCms;
use Haxibiao\Cms\Traits\StickableItem;
use Haxibiao\Media\Movie as BaseMovie;

class Movie extends BaseMovie
{
    use PlayWithCms;
    use StickableItem;
}
