<?php

namespace App\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateSiteCategoriesTable
{
    public function up(): void
    {
        Capsule::schema()->create('site_categories', function (Blueprint $table) {
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');

            $table->primary(['site_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Capsule::schema()->dropIfExists('site_categories');
    }
}
