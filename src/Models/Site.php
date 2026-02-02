<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Site extends Model
{
    protected $table = 'sites';

    protected $fillable = [
        'wikidata_id',
        'name',
        'description',
        'latitude',
        'longitude',
        'image_url',
        'wikipedia_url',
        'country_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'site_categories');
    }

    public function unescoSites(): BelongsToMany
    {
        return $this->belongsToMany(UnescoSite::class, 'site_unesco', 'site_id', 'unesco_id');
    }
}
