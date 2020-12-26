<?php

namespace Haxibiao\Cms\Nova;

use App\Nova\Resource;
use App\Nova\SitePost;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphedByMany;
use Laravel\Nova\Fields\Text;

class Site extends Resource
{
    public static $group = 'CMS站群';
    public static $model = 'App\Site';
    public static function label()
    {
        return "站点管理";
    }

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
        'id', 'name', 'domain',
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
            Text::make('名称', 'name'),
            Text::make('域名', function () {
                return '<a href="//' . $this->domain . '" target="_blank">' . $this->domain . '</a>';
            })->asHtml(),
            Text::make('模板主题', 'theme'),
            Text::make('资源Token', 'ziyuan_token')->hideFromIndex(),
            Text::make('Title', 'title')->hideFromIndex(),
            Text::make('Keywords', 'title')->hideFromIndex(),
            Text::make('Description', 'description')->hideFromIndex(),
            Text::make('统计JS', 'footer_js')->hideFromIndex(),
            Text::make('站长验证meta', 'verify_meta')->hideFromIndex(),
            Text::make('文章数', function () {
                return $this->articles()->count();
            }),
            Text::make('视频数', function () {
                return $this->posts()->count();
            }),
            Text::make('电影数', function () {
                return $this->movies()->count();
            }),
            Text::make('今日百度提交', function () {
                $count_movies_pushed   = $this->movies()->where('siteables.baidu_pushed_at', '>', today())->count();
                $count_posts_pushed    = $this->posts()->where('siteables.baidu_pushed_at', '>', today())->count();
                $count_articles_pushed = $this->articles()->where('siteables.baidu_pushed_at', '>', today())->count();
                return $count_movies_pushed + $count_posts_pushed + $count_articles_pushed;
            }),

            MorphedByMany::make('文章', 'articles', SiteArticle::class),
            MorphedByMany::make('视频动态', 'posts', SitePost::class),
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
        return [];
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
