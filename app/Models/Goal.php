<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'user_id',
        'goal_name',
        'target_amount',
        'saved_amount',
        'target_date',
        'status',
        'description',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getProgressAttribute()
    {
        if ($this->target_amount <= 0) {
            return 0;
        }

        return min(
            100,
            ($this->saved_amount / $this->target_amount) * 100
        );
    }

    public function isCompleted()
    {
        return $this->saved_amount >= $this->target_amount;
    }
}
