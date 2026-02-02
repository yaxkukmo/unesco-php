<?php

namespace App\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateSitesTable
{
    public function up(): void
    {
        Capsule::schema()->create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('wikidata_id', 200)->unique();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('image_url', 500)->nullable();
            $table->string('wikipedia_url', 500)->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('set null');
            $table->timestamps();

            $table->index('country_id');
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down(): void
    {
        Capsule::schema()->dropIfExists('sites');
    }
}
