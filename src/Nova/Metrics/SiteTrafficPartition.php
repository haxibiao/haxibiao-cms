<?php

namespace Haxibiao\Cms\Nova\Metrics;

use App\Traffic;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Partition;

class SiteTrafficPartition extends Partition
{
    public $name = '站点流量 (今日)';
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        $qb = Traffic::query()
            ->where('created_at', '>=', today()->toDateString());
        return $this->count($request, $qb, 'domain');
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'spider-partition';
    }
}
