<?php

use App\Models\User;
use App\Models\WelderSkill;
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
        Schema::create('welder_has_skills', function (Blueprint $table) {
            $table->foreignIdFor(User::class)->index();
            $table->foreignIdFor(WelderSkill::class)->index();
        });

        Schema::table('user_welder_members', function (Blueprint $table) {
            $table->dropColumn('welder_skill_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('welder_has_skills');

        Schema::table('user_welder_members', function (Blueprint $table) {
            $table->foreignIdFor(WelderSkill::class)->after('user_id');
        });
    }
};
