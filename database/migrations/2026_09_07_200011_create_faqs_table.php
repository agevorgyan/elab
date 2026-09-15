<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->text('question');
            $table->text('answer');
            $table->string('category')->default('general');
            $table->integer('sort_order')->default(0);
            $table->boolean('published')->default(true);
            $table->timestamps();

            $table->index('category');
            $table->index('published');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
