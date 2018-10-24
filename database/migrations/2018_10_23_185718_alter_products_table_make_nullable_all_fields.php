<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductsTableMakeNullableAllFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('title', 100)->nullable()->change();
            $table->string('image')->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->string('url')->nullable()->change();
            $table->integer('price')->unsigned()->nullable()->change();
            $table->integer('amount')->unsigned()->nullable()->change();
        });
        $tableName = DB::getQueryGrammar()->wrapTable('products');
        DB::statement('ALTER TABLE '.$tableName.' CHANGE `first_invoice` `first_invoice` TIMESTAMP NULL DEFAULT NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('title', 100)->change();
            $table->string('image')->change();
            $table->text('description')->change();
            $table->string('url')->change();
            $table->integer('price')->unsigned()->change();
            $table->integer('amount')->unsigned()->change();
        });
        $tableName = DB::getQueryGrammar()->wrapTable('products');
        DB::statement('ALTER TABLE '.$tableName.' CHANGE `first_invoice` `first_invoice` TIMESTAMP NOT NULL;');
    }
}
