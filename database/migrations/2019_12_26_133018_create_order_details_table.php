<?php

// use Illuminate\Support\Facades\Schema;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Database\Migrations\Migration;

// class CreateOrderDetailsTable extends Migration
// {
//     /**
//      * Run the migrations.
//      *
//      * @return void
//      */
//     public function up()
//     {
//         Schema::create('details', function (Blueprint $table) {
//             $table->increments('id');
//             $table->unsignedInteger('order_log_id');
//             $table->foreign('order_log_id')->references('id')->on('order_logs')->onDelete('cascade');
//             $table->string('name');
//             $table->string('image');
//             $table->integer('amount');
//             $table->integer('purchase_price');
//             $table->timestamps();
//         });
//     }

//     /**
//      * Reverse the migrations.
//      *
//      * @return void
//      */
//     public function down()
//     {
//         Schema::dropIfExists('order_details');
//     }
} 
