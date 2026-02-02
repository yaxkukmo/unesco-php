<?php

namespace App\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateSiteUnescoTable
{
    public function up(): void
    {
        Capsule::schema()->create('site_unesco', function (Blueprint $table) {
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade');
            $table->string('unesco_id', 50);
            $table->foreign('unesco_id')->references('unesco_id')->on('unesco_sites')->onDelete('cascade');

            $table->primary(['site_id', 'unesco_id']);
        });
    }

    public function down(): void
    {
        Capsule::schema()->dropIfExists('site_unesco');
    }
}
