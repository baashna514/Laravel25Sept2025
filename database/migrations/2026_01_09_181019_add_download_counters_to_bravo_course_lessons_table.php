<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDownloadCountersToBravoCourseLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bravo_course_lessons', function (Blueprint $table) {
            $table->unsignedInteger('video_download_count')
                ->default(0)
                ->after('downloadable');

            $table->unsignedInteger('file_download_count')
                ->default(0)
                ->after('video_download_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bravo_course_lessons', function (Blueprint $table) {
            $table->dropColumn([
                'video_download_count',
                'file_download_count',
            ]);
        });
    }
}
