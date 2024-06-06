<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void{
        Schema::table('orders',function(Blueprint $table){
            $table->unsignedFloat('shipping_amount',8,2)->nullable()->after("subtotal");
            $table->unsignedFloat('tax_percent',8,2)->nullable()->after("shipping_amount");
            $table->boolean('send_invoice_me')->default(0)->after("total");
        });
    }
    public function down(): void{
        Schema::table('orders',function(Blueprint $table){
            $table->dropColumn(['shipping_amount','tax_percent','send_invoice_me']);
        });
    }
};