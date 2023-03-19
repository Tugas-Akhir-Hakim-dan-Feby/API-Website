<?php

use App\Models\Branch;
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
        Schema::create('user_branches', function (Blueprint $table) {
            $table->id();
            $table->uuid()->nullable();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Branch::class);
            $table->string('position');
            $table->string('phone');
            $table->text('address');
            $table->integer('status');
            $table->string('nip');
            $table->enum('gender', ["L", "P"]);
            $table->dateTime('date_birth');
            $table->string('birth_place');
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
        Schema::dropIfExists('user_branches');
    }
};
