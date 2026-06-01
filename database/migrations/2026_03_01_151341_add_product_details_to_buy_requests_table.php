<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('buy_requests', function (Blueprint $table) {
        $table->string('product_name')->after('product_id');
        $table->decimal('product_price', 10, 2)->after('product_name');
    });
}

public function down()
{
    Schema::table('buy_requests', function (Blueprint $table) {
        $table->dropColumn(['product_name', 'product_price']);
    });
}
};
