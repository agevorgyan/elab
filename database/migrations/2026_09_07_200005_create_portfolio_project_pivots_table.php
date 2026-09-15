<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_project_categories', function (Blueprint $table) {
            $table->string('project_id');
            $table->string('category_id');

            $table->foreign('project_id')->references('id')->on('portfolio_projects')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('portfolio_categories')->onDelete('cascade');
            $table->primary(['project_id', 'category_id']);
        });

        Schema::create('portfolio_project_technologies', function (Blueprint $table) {
            $table->string('project_id');
            $table->string('technology_id');

            $table->foreign('project_id')->references('id')->on('portfolio_projects')->onDelete('cascade');
            $table->foreign('technology_id')->references('id')->on('portfolio_technologies')->onDelete('cascade');
            $table->primary(['project_id', 'technology_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_project_technologies');
        Schema::dropIfExists('portfolio_project_categories');
    }
};
