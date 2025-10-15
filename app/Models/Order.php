<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'status',
        'total_value',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'total_value' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function recalculateTotal(): void
    {
        $total = $this->items()->get()->reduce(function ($carry, $item) {
            return $carry + (float) $item->line_total;
        }, 0.0);

        $this->total_value = $total;
        $this->saveQuietly(); 
    }

    public function cancel()
    {
        if ($this->status === 'cancelled') {
            return false; 
        }

        $this->status = 'cancelled';
        $this->save();

        return true;
    }
}
