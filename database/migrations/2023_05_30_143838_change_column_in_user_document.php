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
        Schema::table('user_experts', function (Blueprint $table) {
            $table->string("certificate_competency")->nullable()->change();
            $table->string("certificate_profession")->nullable()->change();
            $table->string("working_mail")->nullable()->change();
            $table->string("career")->nullable()->change();
        });

        Schema::table('user_welder_members', function (Blueprint $table) {
            $table->string("certificate_school")->after("status")->nullable()->change();
            $table->string("pas_photo")->after("certificate_school")->nullable()->change();
        });
    }
};
