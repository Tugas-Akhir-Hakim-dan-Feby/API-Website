<?php

use App\Models\User\CompanyMember;
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
        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignIdFor(CompanyMember::class);
            $table->foreignIdFor(WelderSkill::class);
            $table->string('slug');
            $table->string('work_type');
            $table->text('description');
            $table->string('placement');
            $table->string('pamphlet');
            $table->string('salary');
            $table->dateTime('deadline');
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_vacancies');
    }
};
