<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->timestamps();

        });
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('products', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('brands', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('orders', function (Blueprint $table) {
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
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
}
