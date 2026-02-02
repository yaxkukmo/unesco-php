<?php

namespace App\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateUnescoSitesTable
{
    public function up(): void
    {
        Capsule::schema()->create('unesco_sites', function (Blueprint $table) {
            $table->string('unesco_id', 50)->primary();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Capsule::schema()->dropIfExists('unesco_sites');
    }
}
