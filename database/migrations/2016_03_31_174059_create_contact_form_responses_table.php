<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactFormResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_form_responses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->integer('type_id')->unsigned();
            $table->text('notes');
            $table->string('ip_address');
            $table->string('user_id')->nullable();
            $table->string('user_agent_string');
            $table->text('session'); //_SESSION
            $table->foreign('type_id')->references('id')->on('contact_form_types');
            $table->timestamps();
            //$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contact_form_responses');
    }
}
