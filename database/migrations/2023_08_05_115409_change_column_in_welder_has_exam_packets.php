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
            $table->renameColumn("practice_value", "grade");
            $table->text("notes")->nullable()->after("key_packet");
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
            $table->renameColumn("grade", "practice_value");
            $table->dropColumn([
                "notes"
            ]);
        });
    }
};
