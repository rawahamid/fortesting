<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('desc');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('desc');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('path');
        
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

  

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->text('title');
            $table->string('desc');
            $table->string('status');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('gallery_post', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('gallery_id');


            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('gallery_id')->references('id')->on('galleries')->onDelete('cascade');
        });

        Schema::create('post_tag', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('tag_id');

            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->text('path');
            $table->string('type');
            $table->unsignedBigInteger('post_id');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->text('path');
            $table->unsignedBigInteger('post_id');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('post_tag');
        Schema::dropIfExists('attachments');


    }
}
