<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Wandxx\Topup\Constants\TopUpStatus;

class CreateTopupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string("code")->unique();
            $table->double("amount");
            $table->double("price");
            $table->uuid("created_by")->nullable();
            $table->json("metadata")->nullable();
            $table->unsignedTinyInteger("status")->default(TopupStatus::PLACED);
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
        Schema::dropIfExists('topups');
    }
}
