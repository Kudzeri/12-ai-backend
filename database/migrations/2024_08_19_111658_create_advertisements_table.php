<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subcategory_id')->constrained('categories')->nullOnDelete();
            $table->string('brand');
            $table->string('model');
            $table->string('conditions');
            $table->string('authenticity');
            $table->decimal('price', 10, 2);
            $table->boolean('negotiable');
            $table->json('tags');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
