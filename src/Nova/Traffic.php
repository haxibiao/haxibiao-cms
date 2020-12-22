<?php

namespace Haxibiao\Cms\Nova;

use App\Nova\Resource;
use Haxibiao\Cms\Nova\Metrics\SpiderPartition;
use Haxibiao\Cms\Nova\Metrics\SpiderTrend;
use Haxibiao\Cms\Nova\Metrics\TrafficPartition;
use Haxibiao\Cms\Nova\Metrics\TrafficTrend;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class Traffic extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Traffic';
    public static function label()
    {
        return "流量";
    }
    public static function singularLabel()
    {
        return "流量";
    }
    public static $group = '站群SEO';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'url', 'bot', 'engine',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('URL', 'url'),
            Text::make('蜘蛛', 'bot'),
            Text::make('引擎', 'engine'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
            (new SpiderPartition)->width('1/4'),
            (new SpiderTrend)->width('1/4'),
            (new TrafficPartition)->width('1/4'),
            (new TrafficTrend)->width('1/4'),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
