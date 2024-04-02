<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void{
        Schema::create('product_variants',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id');
            $table->unsignedBigInteger('variant_detail_id');
            $table->unsignedFloat('price',8,2);
            $table->timestamps();
            $table->foreign('variant_id')->references('id')->on('variants')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('variant_detail_id')->references('id')->on('variant_details')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down(): void{
        Schema::dropIfExists('product_variants');
    }
};