<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calls', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('sid')->index();
            $table->string('from_number', 30);
            $table->string('from_name', 50);
            $table->string('from_country', 100);
            $table->string('status', 30);
            $table->float('duration')->default(0);
            $table->timestamp('ended_at');
            $table->integer('phonenumber_id')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('calls');
    }
}
