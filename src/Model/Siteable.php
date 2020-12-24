<?php

namespace Haxibiao\Cms\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siteable extends Model
{
    use HasFactory;

    protected $table = 'siteables';
    public $guarded  = [];

    public function siteable()
    {
        return $this->morphTo();
    }
}
