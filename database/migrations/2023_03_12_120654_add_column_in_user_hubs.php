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
            $table->enum('gender', ['L', 'P'])->after('user_id');
            $table->string('birth_place')->after('gender')->nullable();
            $table->string('date_birth')->after('birth_place')->nullable();
            $table->string('nip')->after('date_birth')->nullable();
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
            $table->dropColumn([
                'gender',
                'birth_place',
                'date_birth',
                'nip',
            ]);
        });
    }
};
