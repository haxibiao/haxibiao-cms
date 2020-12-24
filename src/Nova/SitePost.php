<?php

namespace Haxibiao\Cms\Nova;

use App\Nova\User;
use Haxibiao\Cms\Nova\Actions\AssignToSite;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Resource;

class SitePost extends Resource
{
    public static $group = "CMS站群";
    public static function label()
    {
        return '短视频';
    }
    public static $model  = 'Haxibiao\Cms\Post';
    public static $title  = 'title';
    public static $search = [
        'id', 'title',
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
            Text::make('相关文字', function () {
                $text = str_limit($this->description);
                return '<a style="width: 300px" href="articles/' . $this->id . '">' . $text . "</a>";
            })->asHtml()->onlyOnIndex(),

            Text::make('内容', 'content')->hideWhenCreating(),

            BelongsTo::make('上传用户', 'user', User::class)->onlyOnForms(),

            Text::make('点赞数', 'count_likes')->hideWhenCreating(),

            Textarea::make('文章内容', 'description'),

            Select::make('状态', 'status')->options([
                1  => '公开',
                0  => '草稿',
                -1 => '下架',
            ])->displayUsingLabels(),
            BelongsTo::make('作者', 'user', 'App\Nova\User')->exceptOnForms(),

            Text::make('时间', function () {
                return time_ago($this->created_at);
            })->onlyOnIndex(),
            // Number::make('总评论数', 'count_comments')->exceptOnForms()->sortable(),
            Image::make('图片', 'video.cover')->thumbnail(
                function () {
                    return $this->cover;
                }
            )->preview(
                function () {
                    return $this->cover;
                }
            ),
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
        return [
            new AssignToSite,
        ];
    }
}