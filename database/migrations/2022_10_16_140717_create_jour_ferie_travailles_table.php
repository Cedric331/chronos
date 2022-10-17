<?php

use App\Models\Hub;
use App\Models\JourFerie;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJourFerieTravaillesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jour_ferie_travailles', function (Blueprint $table) {
            $table->id();
            $table->json('collaborateurs')->nullable();
            $table->foreignIdFor(JourFerie::class);
            $table->foreignIdFor(Hub::class);
            $table->unique(['jour_ferie_id', 'hub_id']);
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
        Schema::dropIfExists('jour_ferie_travailles');
    }
}
