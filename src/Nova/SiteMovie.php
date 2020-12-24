<?php

namespace Haxibiao\Cms\Nova;

use Haxibiao\Cms\Nova\Actions\AssignToSite;
use Haxibiao\Media\Nova\Movie as NovaMovie;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;

class SiteMovie extends NovaMovie
{
    public static $group = "CMS站群";
    public static $model = 'Haxibiao\Cms\Movie';

    //过滤草稿状态的
    public static function indexQuery(NovaRequest $request, $query)
    {
        // return $query->whereStatus(1);
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new AssignToSite,
        ];
    }
}
