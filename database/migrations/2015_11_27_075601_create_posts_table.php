<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('area');
            $table->string('source');
            $table->string('actor');
            $table->string('director');
            $table->string('description');
            $table->string('tag');
            $table->string('type');
            $table->string('resolution');
            $table->string('stream');
            $table->string('format');
            $table->string('framerate');
            $table->string('audiochannel');
            $table->string('thumb');
            $table->string('file');
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
        Schema::drop('posts');
    }
}
