<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            // Link back to the users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->string('state');
            $table->string('district');
            $table->string('address');
            $table->boolean('is_default')->default(true); // Useful for buyers with multiple addresses
            $table->timestamps();

            // Indexing for performance
            $table->index(['user_id', 'state', 'district','address']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};