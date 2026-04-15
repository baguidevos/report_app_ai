<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Agent extends Model
{
    protected $fillable = [
        'name',
        'system_prompt',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Execute the agent on given content.
     * This will be implemented with AI service integration.
     */
    public function execute(string $content): string
    {
        // This will be replaced with actual AI service call
        // For now, return placeholder
        return "Agent '{$this->name}' processing...";
    }

    /**
     * Get all default agents.
     */
    public static function getDefaultAgents()
    {
        return static::where('is_default', true)->get();
    }
}
