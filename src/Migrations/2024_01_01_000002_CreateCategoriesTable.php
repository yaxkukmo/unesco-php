<?php

namespace App\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriesTable
{
    public function up(): void
    {
        Capsule::schema()->create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Capsule::schema()->dropIfExists('categories');
    }
}
