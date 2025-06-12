<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exchange extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'offered_item_id',
        'status',
        'message',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function offeredItem(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'offered_item_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
