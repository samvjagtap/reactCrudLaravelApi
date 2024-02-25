<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ecom_customizations', function (Blueprint $table) {
            $table->id();
            $table->string('div_name',200);
            $table->string('div_value', 200);
            $table->string('font_color', 100);
            $table->string('font_type', 100);
            $table->integer('font_size');
            $table->string('bg_color', 100);
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
        Schema::dropIfExists('ecom_customizations');
    }
};
