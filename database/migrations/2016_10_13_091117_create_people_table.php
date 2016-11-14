<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('father_id')->unsigned()->nullable();
            $table->integer('mother_id')->unsigned()->nullable();
            $table->string('identifier')->unique()->nullable(); // es. fiscal code
            $table->string('firstname');
            $table->string('surname');
            $table->string('surname_at_birth')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->boolean('living')->nullable();
            // Contact
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            // Bio
            $table->string('birth_place')->nullable();
            $table->string('profession')->nullable();
            $table->string('company')->nullable();
            $table->text('intrests')->nullable();
            $table->text('activities')->nullable();
            $table->text('bio_notes')->nullable();
            $table->timestamps();
            // Indexes
            $table->foreign('father_id')
                    ->references('id')->on('people')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            $table->foreign('mother_id')
                    ->references('id')->on('people')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('people');
    }

}
