<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void{
        Schema::create('orders',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('slug',30)->unique();
            $table->string('billing_name')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_phone')->nullable();
            $table->string('billing_company')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_street_address')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_zipcode')->nullable();
            $table->string('shipping_name')->nullable();
            $table->string('shipping_company')->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('shipping_street_address')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_zipcode')->nullable();
            $table->longText('additional_notes')->nullable();
            $table->enum('order_status',["pending","processing","hold","unholed","completed","cancelled"])->default("pending");
            $table->string('charge_id')->nullable();
            $table->longText('response')->nullable();
            $table->unsignedFloat('subtotal',8,2);
            $table->unsignedFloat('tax',8,2);
            $table->unsignedFloat('total',8,2);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down(): void{
        Schema::dropIfExists('orders');
    }
};