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
        Schema::table('user_hubs', function (Blueprint $table) {
            $table->integer('status')->nullable()->default(1)->change();
        });

        Schema::table('user_branches', function (Blueprint $table) {
            $table->integer('status')->nullable()->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_hubs', function (Blueprint $table) {
            $table->integer('status')->change();
        });

        Schema::table('user_branches', function (Blueprint $table) {
            $table->integer('status')->change();
        });
    }
};
