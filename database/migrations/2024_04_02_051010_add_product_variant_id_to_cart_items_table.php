<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void{
        Schema::table('cart_items',function(Blueprint $table){
            $table->unsignedBigInteger('product_variant_id')->nullable()->after('product_id');
            $table->foreign('product_variant_id')->references('id')->on('product_variants');
        });
    }
    public function down(): void{
        Schema::table('cart_items',function(Blueprint $table){
            $table->dropForeign('cart_items_product_variant_id_foreign');
            $table->dropColumn('product_variant_id');
        });
    }
};