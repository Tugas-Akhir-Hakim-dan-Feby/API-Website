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
        Schema::table('user_welder_members', function (Blueprint $table) {
            $table->string("certificate_school")->after("status");
            $table->string("pas_photo")->after("certificate_school");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_welder_members', function (Blueprint $table) {
            $table->dropColumn([
                "certificate_school",
                "pas_photo"
            ]);
        });
    }
};
