<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();

            $table->string('type')->default('video/audio');
            $table->string('full_video_filename');
            $table->string('video_filename')->nullable(true);
            $table->string('audio_filename')->nullable(true);
            $table->string('youtube_id')->unique();
            $table->boolean('uploaded')->default(false);
            $table->string('title')->nullable(true);
            $table->string('description')->nullable(true);

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
        Schema::dropIfExists('files');
    }
}
