<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;

class Redirection extends Model
{
    use HasFactory,
        SoftDeletes;

    const DEFAULT_HTTP_RESPONSE_CODE = 302;

    protected $fillable = [
        'source',
        'target',
        'http_response_code',
        'comment',
        'is_active',
    ];

    protected $attributes = [
        'http_response_code' => self::DEFAULT_HTTP_RESPONSE_CODE,
        'is_active' => true,
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (self $redirection) {
            $redirection->source = rtrim($redirection->source, "/ \n\r\t\v\x00");

            Artisan::call('route:clear');
            Artisan::call('route:cache');
        });

        static::deleting(function (self $redirection) {
            Artisan::call('route:clear');
            Artisan::call('route:cache');
        });
    }

    public function scopeActive(Builder $query, bool $isActive = true): Builder
    {
        return $query->where('is_active', $isActive);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query
            ->where('source', 'LIKE', "%{$term}%")
            ->orWhere('target', 'LIKE', "%{$term}%")
            ->orWhere('comment', 'LIKE', "%{$term}%");
    }

    public function hits(): HasMany
    {
        return $this->hasMany(RedirectionHit::class);
    }

    public function go(): RedirectResponse
    {
        return response()->redirectTo(
            path: $this->target,
            status: $this->http_response_code ?? self::DEFAULT_HTTP_RESPONSE_CODE,
        );
    }

    public function getLastHitAttribute(): ?RedirectionHit
    {
        return $this->hits()->orderBy('created_at', 'desc')->first();
    }

    public function getLastHitAtAttribute(): ?Carbon
    {
        return $this->last_hit?->created_at;
    }
}
