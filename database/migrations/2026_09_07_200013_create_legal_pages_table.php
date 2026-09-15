<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_pages', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('slug')->unique();
            $table->string('title');
            $table->longText('content');
            $table->string('last_updated');
            $table->boolean('published')->default(true);
            $table->integer('version')->default(1);
            $table->timestamps();

            $table->index('slug');
            $table->index('published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_pages');
    }
};
