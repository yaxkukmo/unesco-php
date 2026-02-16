<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static self|null find(mixed $id)
 */
class Country extends Model
{
    protected $table = 'countries';

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function sites(): HasMany
    {
        return $this->hasMany(Site::class);
    }
}
