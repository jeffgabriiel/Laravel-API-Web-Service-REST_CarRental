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
        Schema::create('car_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id');
            $table->string('nome', 30);
            $table->string('imagem', 100);
            $table->integer('numero_portas');
            $table->integer('lugares');
            $table->boolean('air_bag');
            $table->boolean('abs');
            $table->timestamps();
    
            //foreign key (constraints)
            $table->foreign('brand_id')->references('id')->on('brands');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('car_templates');
    }
};
