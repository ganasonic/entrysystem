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
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('rank')->nullable();
            $table->string('title')->nullable();
            $table->string('fistitle')->nullable();
            $table->string('season')->nullable();
            $table->string('pref')->nullable();
            $table->string('place')->nullable();
            $table->string('course')->nullable();
            $table->string('association')->nullable();
            $table->string('race_date')->nullable();
            $table->string('category')->nullable();
            $table->string('discipline')->nullable();
            $table->string('sex')->nullable();
            $table->string('codex_sajf')->nullable();
            $table->string('codex_sajm')->nullable();
            $table->string('codex_fisf')->nullable();
            $table->string('codex_fism')->nullable();
            $table->string('entry_fee')->nullable();
            $table->string('minimum_point')->nullable();
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
        Schema::dropIfExists('competitions');
    }
};
