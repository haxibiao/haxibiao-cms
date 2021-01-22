<?php

namespace Haxibiao\Cms\Traits;

use Haxibiao\Cms\Site;

trait StickableItem
{

    public static function bootStickable()
    {
        // 资源移除时候，自动移除置顶逻辑
        static::deleted(function ($model) {
            foreach ($model->related as $stickable) {
                $stickable->delete();
            }
        });
    }

    public function stickSites()
    {
        return $this->morphToMany(\App\Site::class, 'item', 'stickables')
            ->withPivot(['name', 'page', 'area'])
            ->withTimestamps();
    }

    public function related()
    {
        return $this->morphMany(\App\Stickable::class, 'item');
    }

    /**
     * 置顶顶站群下
     */
    public function stickByIds($site_ids = null, $name = null, $page = null, $area = null)
    {
        $sites = Site::bySiteIds($site_ids)->get();
        foreach ($sites as $site) {
            $count = $this->stickSites()->when($name, function ($q) use ($name) {
                $q->where('stickables.name', $name);
            })->where('site_id', $site->id)->count();

            if ($count >= 1) {
                continue;
            } else {
                $this->stickSites()->attach([
                    $site->id => [
                        'name' => $name,
                        'page' => $page,
                        'area' => $area,
                    ],
                ]);
            }
        }
        return $this;
    }

    public function unStickByIds($site_ids)
    {
        $this->stickSites()->detach($site_ids);
        return $this;
    }

    public function scopeByStickablePage($query, $page)
    {
        return $query->where('stickables.page', $page);
    }

    public function scopeByStickableName($query, $name)
    {
        return $query->where('stickables.name', $name);
    }

    public function scopeByStickableArea($query, $area)
    {
        return $query->where('stickables.area', $area);
    }
}
