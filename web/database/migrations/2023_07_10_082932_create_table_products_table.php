<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer("category_id");
            $table->integer("brand_id");
            $table->string("name", 255);
            $table->string("image_url", 255)->nullable();
            $table->float("price", 255)->nullable();
            $table->float("old_price", 255)->nullable();
            $table->text("description")->nullable();
            $table->text("tags")->nullable();
            $table->tinyInteger("is_best_sell");
            $table->tinyInteger("is_new");
            $table->tinyInteger("sort_order")->nullable();
            $table->tinyInteger("active")->default(1);
            $table->integer("amount")->default(100);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
