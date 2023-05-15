<?php

use App\Models\User;
use App\Models\User\WelderMember;
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
        Schema::create('user_experts', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignIdFor(User::class);
            $table->string("instance");
            $table->string("certificate_competency");
            $table->string("certificate_profession");
            $table->string("working_mail");
            $table->string("career");
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
        Schema::dropIfExists('user_experts');
    }
};
