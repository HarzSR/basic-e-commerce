<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username');
            $table->string('password');
            $table->enum('type', ['Admin', 'Sub Admin'])->default('Admin');
            $table->boolean('categories_view_access');
            $table->boolean('categories_edit_access');
            $table->boolean('categories_full_access');
            $table->boolean('products_view_access');
            $table->boolean('products_edit_access');
            $table->boolean('products_full_access');
            $table->boolean('orders_view_access');
            $table->boolean('orders_edit_access');
            $table->boolean('orders_full_access');
            $table->boolean('users_view_access');
            $table->boolean('users_edit_access');
            $table->boolean('users_full_access');
            $table->boolean('status');
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
        Schema::dropIfExists('admins');
    }
}
