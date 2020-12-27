<?php
namespace Haxibiao\Cms\Traits;

use App\Siteable;
use Haxibiao\Cms\Site;
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

    /**
     * 确保提交给百度的都有url属性
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        if (!empty($this->url)) {
            return $this->url;
        }

        //修复专题URL
        if (str_contains(get_class($this), 'Category')) {
            return url("/category/" . $this->id);
        }
    }
}
