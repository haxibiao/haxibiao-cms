<?php

namespace Haxibiao\Cms\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 专注seo有效流量分析，不关心普通流量数据
 */
class Traffic extends Model
{
    use HasFactory;

    protected $guarded = [];
}
