<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cookie_settings', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->integer('version')->default(1);
            $table->boolean('banner_enabled')->default(true);
            $table->boolean('analytics_enabled')->default(true);
            $table->boolean('marketing_enabled')->default(false);
            $table->string('ga_measurement_id')->nullable();
            $table->string('meta_pixel_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cookie_settings');
    }
};
