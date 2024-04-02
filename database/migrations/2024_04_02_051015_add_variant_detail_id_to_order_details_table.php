<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void{
        Schema::table('order_items',function(Blueprint $table){
            $table->unsignedBigInteger('variant_detail_id')->nullable()->after('product_id');
            $table->foreign('variant_detail_id')->references('id')->on('variant_details');
        });
    }
    public function down(): void{
        Schema::table('order_items',function(Blueprint $table){
            $table->dropForeign('order_items_variant_detail_id_foreign');
            $table->dropColumn('variant_detail_id');
        });
    }
};