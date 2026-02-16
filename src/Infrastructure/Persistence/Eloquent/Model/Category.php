<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static self|null find(mixed $id)
 */
class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function sites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class, 'site_categories');
    }
}
