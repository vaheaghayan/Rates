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
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('currency');
            $table->decimal('buy');
            $table->decimal('sell');
            $table->decimal('buycardsrate');
            $table->decimal('sellcardsrate');
            $table->decimal('buycashrate');
            $table->decimal('sellcashrate');
            $table->dateTime('ratetime');
            $table->decimal('cbrate');
            $table->dateTime('cbratetime');
            $table->decimal('buyrateforcross');
            $table->decimal('sellrateforcross');
            $table->decimal('buyratefortransfer');
            $table->decimal('sellratefortransfer');
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
        Schema::dropIfExists('exchange_rates');
    }
};
