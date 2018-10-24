<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOffersChangeSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->integer('sales')->unsigned()->nullable()->change();
            $table->integer('price')->unsigned()->nullable()->change();
            $table->integer('amount')->unsigned()->nullable()->change();
            $table->string('article')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->integer('sales')->unsigned()->change();
            $table->integer('price')->unsigned()->change();
            $table->integer('amount')->unsigned()->change();
            $table->string('article')->change();
        });
    }
}
