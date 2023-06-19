<?php

use App\Models\User;
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
            $table->string("practice_exam_address")->nullable()->after('period');
            $table->foreignIdFor(User::class)->default(1)->after('practice_exam_address');
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
            $table->dropColumn(["practice_exam_address", "user_id"]);
        });
    }
};
