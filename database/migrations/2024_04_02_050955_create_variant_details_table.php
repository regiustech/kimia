<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void{
        Schema::create('variant_details',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('variant_id');
            $table->string('name');
            $table->unsignedInteger('order_no');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('variant_id')->references('id')->on('variants')->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down(): void{
        Schema::dropIfExists('variant_details');
    }
};