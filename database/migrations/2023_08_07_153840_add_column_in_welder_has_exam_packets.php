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
            $table->string('payment')->nullable()->after('status');
            $table->timestamp('validated_at')->nullable()->after('updated_at');
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
                "payment",
                "validated_at",
            ]);
        });
    }
};
