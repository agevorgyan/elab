<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('price_amd');
            $table->string('price_currency')->default('AMD');
            $table->boolean('show_price')->default(true);
            $table->string('price_label')->nullable()->default('Starting from');
            $table->boolean('popular')->default(false);
            $table->string('tagline')->nullable();
            $table->text('description');
            $table->string('icon')->nullable();
            $table->string('cta_text')->nullable()->default('Order Service →');
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('published')->default(true);
            $table->timestamps();

            $table->index('slug');
            $table->index('published');
        });

        Schema::create('service_features', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('service_id');
            $table->string('text');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->index('service_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_features');
        Schema::dropIfExists('services');
    }
};
