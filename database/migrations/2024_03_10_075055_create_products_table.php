<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void{
        Schema::create('products',function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('slug',30)->unique();
            $table->string('category')->nullable();
            $table->string('catalog_number')->nullable();
            $table->string('cas_number')->nullable();
            $table->unsignedFloat('price',8,2);
            $table->text('image')->nullable();
            $table->text('description')->nullable();
            $table->longText('specifications')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void{
        Schema::dropIfExists('products');
    }
};