<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->bigInteger('author_id');
            $table->string('post_title')->nullable();
            $table->string('post_subtitle')->nullable();
            $table->string('post_name')->nullable();
            $table->string('post_content')->nullable();
            $table->string('post_status')->nullable();
            $table->string('comment_status')->nullable();
            $table->string('post_picture')->default('product.jpg');
            $table->boolean('deleted')->default(false);
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
        Schema::dropIfExists('posts');
    }
}
