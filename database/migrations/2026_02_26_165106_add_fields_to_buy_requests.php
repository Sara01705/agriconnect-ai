<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
  public function up()
{
    Schema::table('buy_requests', function (Blueprint $table) {

        if (!Schema::hasColumn('buy_requests', 'quantity')) {
            $table->integer('quantity')->after('buyer_phone');
        }

        if (!Schema::hasColumn('buy_requests', 'total_price')) {
            $table->decimal('total_price', 10, 2)->after('quantity');
        }
    });
}
public function down()
{
    Schema::table('buy_requests', function (Blueprint $table) {

        if (Schema::hasColumn('buy_requests', 'quantity')) {
            $table->dropColumn('quantity');
        }

        if (Schema::hasColumn('buy_requests', 'total_price')) {
            $table->dropColumn('total_price');
        }
    });
}


};
