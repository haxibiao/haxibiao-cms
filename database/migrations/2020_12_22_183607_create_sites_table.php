<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('sites')) {
            return;
        }

        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable()->index()->comment('站名');
            $table->string('domain', 50)->nullable()->index()->comment('域名');
            $table->string('theme')->nullable()->comment('主题');
            $table->string('title')->nullable();
            $table->string('keywords')->nullable();
            $table->string('description')->nullable();
            $table->string('ziyuan_token')->nullable()->comment('百度资源站长提交收录的token');
            $table->text('footer_js')->nullable()->comment('底部js 支持如ga，mta，matomo统计等站长权限');
            $table->string('verify_meta')->nullable()->comment('站长验证用meta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sites');
    }
}
