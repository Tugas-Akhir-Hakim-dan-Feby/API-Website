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
        Schema::table('welder_has_exam_packets', function (Blueprint $table) {
            $table->timestamp('finished_at')->nullable()->after('validated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('welder_has_exam_packets', function (Blueprint $table) {
            $table->dropColumn([
                'finished_at'
            ]);
        });
    }
};
