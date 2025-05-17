<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('point_lists', function (Blueprint $table) {
            $table->id();
            $table->string('SAJNO')->nullable();
            $table->string('FISNO')->nullable();
            $table->string('氏名R')->nullable();
            $table->string('氏名漢')->nullable();
            $table->string('国名')->nullable();
            $table->string('県連盟')->nullable();
            $table->float('FIS_AEﾎﾟｲﾝﾄ')->nullable();
            $table->float('FIS_HPﾎﾟｲﾝﾄ')->nullable();
            $table->float('FIS_MOﾎﾟｲﾝﾄ')->nullable();
            $table->float('FIS_SXﾎﾟｲﾝﾄ')->nullable();
            $table->float('FIS_SSﾎﾟｲﾝﾄ')->nullable();
            $table->float('FIS_BAﾎﾟｲﾝﾄ')->nullable();
            $table->float('SAJ_AEﾎﾟｲﾝﾄ')->nullable();
            $table->float('SAJ_HPﾎﾟｲﾝﾄ')->nullable();
            $table->float('SAJ_MOﾎﾟｲﾝﾄ')->nullable();
            $table->float('SAJ_SXﾎﾟｲﾝﾄ')->nullable();
            $table->float('SAJ_SSﾎﾟｲﾝﾄ')->nullable();
            $table->float('SAJ_BAﾎﾟｲﾝﾄ')->nullable();
            $table->string('所属')->nullable();
            $table->string('生年月日')->nullable();
            $table->string('ｸﾗｽ')->nullable();
            $table->string('氏名2')->nullable();
            $table->string('学年')->nullable();
            $table->string('競技者ｺｰﾄﾞ')->nullable();
            $table->string('姓')->nullable();
            $table->string('名')->nullable();
            $table->string('ｾｲ')->nullable();
            $table->string('ﾒｲ')->nullable();
            $table->string('sei')->nullable();
            $table->string('mei')->nullable();
            $table->string('連盟ｺｰﾄﾞ')->nullable();
            $table->string('ﾁｰﾑﾖﾐｶﾞﾅ')->nullable();
            $table->string('性別')->nullable();
            $table->unsignedInteger('ランク')->nullable();
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
        Schema::dropIfExists('point_lists');
    }
};
