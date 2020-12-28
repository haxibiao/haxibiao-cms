<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOwnerToSites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function (Blueprint $table) {
            if (!Schema::hasColumn('sites', 'owner')) {
                $table->string('owner', 20)->nullable()->comment('站长');
                $table->string('toutiao_token', 30)->nullable()->comment('头条站长token');
                $table->string('360_token', 30)->nullable()->comment('360站长token');
                $table->string('sogou_token', 30)->nullable()->comment('搜狗站长token');
                $table->string('shenma_token', 30)->nullable()->comment('神马站长token');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sites', function (Blueprint $table) {
            //
        });
    }
}
