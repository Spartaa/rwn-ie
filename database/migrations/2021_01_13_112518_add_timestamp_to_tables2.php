<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampToTables2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('order_items', function (Blueprint $table) {
            $table->timestamps();
        });

         Schema::table('content_page_types', function (Blueprint $table) {
            $table->timestamps();
        });

         Schema::table('permission_types', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('product_special_prices', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('provinces', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('seasonal_price_catergories', function (Blueprint $table) {
            $table->timestamps();
        });


        Schema::table('suppliers', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('user_permissions', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('user_types', function (Blueprint $table) {
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
