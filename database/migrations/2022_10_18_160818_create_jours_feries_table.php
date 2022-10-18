<?php

use App\Models\Hub;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJoursFeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jours_feries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->year('annee');
            $table->date('date');
            $table->foreignId('hub_id')->constrained('hubs');
            $table->unique(['annee', 'date', 'hub_id']);
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
        Schema::dropIfExists('jours_feries');
    }
}
