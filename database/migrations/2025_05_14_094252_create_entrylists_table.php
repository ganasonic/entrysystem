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
    public function up()
    {
        Schema::create('entrylists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // usersテーブルと外部キー制約
            $table->foreignId('competition_id')->constrained()->onDelete('cascade'); // competitionsテーブルと外部キー制約
            $table->string('SAJNO');
            $table->string('status')->default('pending'); // エントリーのステータス（例: pending, confirmed）
            $table->boolean('delete_flg')->default(false); // 論理削除フラグ
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
        Schema::dropIfExists('entrylists');
    }
};
