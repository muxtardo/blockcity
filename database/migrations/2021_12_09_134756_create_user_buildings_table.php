<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_buildings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('building_id');
            $table->unsignedBigInteger('building_status_id')->default(1);
            $table->string('name');
            $table->tinyInteger('image')->default(1);
            $table->integer('level')->unsigned()->default(1);
            $table->tinyInteger('highlight')->unsigned()->default(1);
            $table->float('earnings', 0)->default(0);
            $table->float('last_claim', 0)->default(0);
            $table->timestamp('last_claim_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_buildings');
    }
}
