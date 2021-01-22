<?php

namespace Haxibiao\Cms;

use Haxibiao\Cms\Traits\StickableItem;
use Haxibiao\Cms\Traits\WithCms;
use Haxibiao\Media\Movie as BaseMovie;

class Movie extends BaseMovie
{
    use WithCms;
    use StickableItem;
}
