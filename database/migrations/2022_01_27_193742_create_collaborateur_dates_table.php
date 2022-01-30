<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollaborateurDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaborateur_dates', function (Blueprint $table) {
            $table->id();
            $table->json('horaire');
            $table->foreignId('hub_id')->constrained('hubs');
            $table->foreignId('collaborateur_id')->constrained('collaborateurs');
            $table->foreignId('date_id')->constrained('dates');
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
        Schema::dropIfExists('collaborateur_dates');
    }
}
