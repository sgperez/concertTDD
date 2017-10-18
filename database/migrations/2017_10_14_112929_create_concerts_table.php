<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConcertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concerts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 50);
            $table->string('subtitle', 250);
            $table->datetime('date');
            $table->integer('ticket_price');
            $table->string('venue', 150);
            $table->string('venue_address', 150);
            $table->string('city', 150);
            $table->string('state', 50);
            $table->string('postcode', 10);
            $table->string('additional_info', 250);
            $table->datetime('published_at')->nullable();
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
        Schema::dropIfExists('concerts');
    }
}
