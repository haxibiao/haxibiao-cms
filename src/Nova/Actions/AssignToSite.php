<?php

namespace Haxibiao\Cms\Nova\Actions;

use Haxibiao\Cms\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\Actionable;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Nova;

class AssignToSite extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels, Actionable;

    public $name = '更新到站点';
    public function uriKey()
    {
        return str_slug(Nova::humanize($this));
    }
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if (!isset($fields->site_id)) {
            return Action::danger('必须选中要更新的站点');
        }

        $site = Site::findOrFail($fields->site_id);

        $pushed_count = 0;
        DB::beginTransaction();
        try {
            $urls = [];
            foreach ($models as $model) {
                $model->assignToSite($site->id);
                $urls[] = $model->url;
            }
            //提交百度收录
            if ($site->ziyuan_token) {
                if (push_baidu($urls, $site->ziyuan_token, $site->domain)) {
                    //提交收录成功，记录时间
                    foreach ($models as $model) {
                        $model->update(['baidu_pushed_at' => now()]);
                        ++$pushed_count;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            $msg = '数据批量变更失败，数据回滚';
            if (!is_prod_env()) {
                $msg .= $e->getMessage();
            }
            return Action::danger($msg);
        }
        DB::commit();

        return Action::message('修改成功!,百度提交成功' . $pushed_count . '条');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $siteOptions = [];
        foreach (Site::all() as $site) {
            $siteOptions[$site->id] = $site->name . "(" . $site->domain . ")";
        }
        return [
            Select::make('站点', 'site_id')->options($siteOptions),
            Select::make('收录', 'is_push')->options([
                0 => '仅更新',
                1 => '提交百度',
            ]),
        ];
    }
}
