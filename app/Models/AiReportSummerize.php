<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiReportSummerize extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'report_id',
        'title',
        'body',
        'type',
        'date_start',
        'date_end',
    ];

    /**
     * Get the user that owns the summary.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the report that owns the summary.
     */
    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
