<?php

use App\Models\Hub;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeRotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_rotations', function (Blueprint $table) {
            $table->id();
            $table->string('type', 3);
            $table->string('hours', 5)->nullable();
            $table->foreignId('hub_id')->constrained('hubs');
            $table->unique(['type', 'hub_id']);
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
        Schema::dropIfExists('type_rotations');
    }
}
