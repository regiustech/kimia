<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void{
        Schema::create('email_templates',function(Blueprint $table){
            $table->id();
            $table->string('subject')->nullable();
            $table->enum('type',["contacts","custom_order","new_order","order_change_status"])->nullable();
            $table->longText('email_content')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void{
        Schema::dropIfExists('email_templates');
    }
};