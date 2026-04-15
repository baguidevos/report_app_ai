<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'frequency',
        'parent_report_id',
    ];

    /**
     * Get the user that owns the report.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent report (for master reports).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'parent_report_id');
    }

    /**
     * Get the child reports (source reports for a master).
     */
    public function children(): HasMany
    {
        return $this->hasMany(Report::class, 'parent_report_id');
    }

    /**
     * Scope to filter by frequency.
     */
    public function scopeByFrequency(Builder $query, string $frequency): Builder
    {
        return $query->where('frequency', $frequency);
    }

    /**
     * Scope to get recent reports.
     */
    public function scopeRecent(Builder $query, int $days = 30): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days))
                     ->orderBy('created_at', 'desc');
    }

    /**
     * Check if this is a master report (has children).
     */
    public function isMaster(): bool
    {
        return $this->children()->count() > 0;
    }

    /**
     * Get all children reports.
     */
    public function getChildren()
    {
        return $this->children()->orderBy('created_at', 'asc')->get();
    }
}
