<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void{
        Schema::table('carts',function(Blueprint $table){
            $table->unsignedFloat('shipping_amount',8,2)->nullable()->after("session_id");
            $table->unsignedFloat('tax_percent',8,2)->nullable()->after("shipping_amount");
        });
    }
    public function down(): void{
        Schema::table('carts',function(Blueprint $table){
            $table->dropColumn(['shipping_amount','tax_percent']);
        });
    }
};