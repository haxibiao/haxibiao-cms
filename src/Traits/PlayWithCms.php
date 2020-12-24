<?php
namespace Haxibiao\Cms\Traits;

use App\Siteable;
use Haxibiao\Cms\Model\Site;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait PlayWithCms
{
    public function sites(): MorphToMany
    {
        return $this->morphToMany(Site::class, 'siteable')
            ->withTimestamps();
    }

    public function siteable()
    {
        return $this->morphMany(Siteable::class, 'siteable');
    }

    /**
     * 分配内容到站点
     */
    public function assignToSite($site_id)
    {
        $this->sites()->syncWithoutDetaching([$site_id]);
    }

    //attrs
    public function getBaiduPushedAtAttribute()
    {
        if ($this->pivot) {
            return $this->pivot->baidu_pushed_at;
        }
        return null;
    }
}
