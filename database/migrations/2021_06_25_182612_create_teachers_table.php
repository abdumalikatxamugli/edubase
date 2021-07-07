<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();

            $table->string('first_name');
            $table->string('last_name');
            $table->string('patronym');
            $table->enum('gender', ['m', 'f']);
            $table->string('pass_sery', 2);
            $table->string('pass_number', 7);
            $table->string('pinfl', 14);
            $table->string('phone', 12);
            $table->text('address');
            $table->date('birth_date');
            $table->text('photo');
            $table->unsignedBigInteger('user_id');
            
            $table->unique(['pass_sery', 'pass_number']);
            $table->unique('pinfl');
            $table->foreign('user_id')->references('id')->on('users');


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
        Schema::dropIfExists('teachers');
    }
}
