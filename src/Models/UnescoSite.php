<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UnescoSite extends Model
{
    protected $table = 'unesco_sites';
    protected $primaryKey = 'unesco_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'unesco_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function sites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class, 'site_unesco', 'unesco_id', 'site_id');
    }
}
