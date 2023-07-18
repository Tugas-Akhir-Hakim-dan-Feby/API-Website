<?php

use App\Models\User\CompanyMember;
use App\Models\User\Operator;
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
        Schema::table('exam_packets', function (Blueprint $table) {
            $table->renameColumn('schedule', 'exam_schedule');
            $table->date('close_schedule')->nullable();
            $table->string('price')->nullable();
            $table->foreignIdFor(Operator::class);
            $table->foreignIdFor(WelderSkill::class);

            $table->dropColumn(['practice_exam_address', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam_packets', function (Blueprint $table) {
            $table->renameColumn('exam_schedule', 'schedule');
            $table->dropColumn([
                'close_schedule',
                'price',
                'operator_id',
                'welder_skill_id'
            ]);

            $table->string('practice_exam_address');
            $table->string('name');
        });
    }
};
