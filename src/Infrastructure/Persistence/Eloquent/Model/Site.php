<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static self|null find(mixed $id)
 */
class Site extends Model
{
    protected $table = 'sites';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'description',
        'latitude',
        'longitude',
        'external_url',
        'country_id',
        'status',
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
