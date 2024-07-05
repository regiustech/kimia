<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void{
        Schema::table('carts',function(Blueprint $table){
            $table->string('fedex_courier_name')->nullable()->after('fedex_account');
        });
    }
    public function down(): void{
        Schema::table('carts',function(Blueprint $table){
            $table->dropColumn('fedex_courier_name');
        });
    }
};