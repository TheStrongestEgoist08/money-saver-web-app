<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiSuggestion extends Model
{
    protected $fillable = [
        'user_id', 'prompt', 'suggestions', 'period', 'metadata'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
