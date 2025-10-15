<?php

namespace App\Observers;

use App\Models\OrderItem;

class OrderItemObserver
{
    public function created(OrderItem $item)
    {
        $item->order->recalculateTotal();
    }

    public function updated(OrderItem $item)
    {
        $item->order->recalculateTotal();
    }

    public function deleted(OrderItem $item)
    {
        if ($item->order) {
            $item->order->recalculateTotal();
        }
    }
}
