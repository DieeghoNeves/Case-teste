<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\User;
use App\Jobs\SendOrderConfirmation;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function index(User $user, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $user->orders()->with('items');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function show(User $user, int $id): Order
    {
        $order = Order::with('items')->find($id);

        if (! $order) {
            throw new ModelNotFoundException('Pedido não encontrado.');
        }

        if ($order->user_id !== $user->id) {
            throw new AuthorizationException('Acesso negado ao pedido.');
        }

        return $order;
    }

    public function store(User $user, array $data): Order
    {
        $incomingItems = $data['items'] ?? [];
        $grouped = [];
        $merged = false;

        foreach ($incomingItems as $item) {
            $key = mb_strtolower(trim($item['product_name']));
            $unitPrice = (float) ($item['unit_price'] ?? 0);
            $quantity  = (int) ($item['quantity'] ?? 0);

            if (isset($grouped[$key])) {
                $grouped[$key]['quantity'] += $quantity;
                $merged = true;
            } else {
                $grouped[$key] = [
                    'product_name' => $item['product_name'],
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                ];
            }
        }

        $finalItems = array_values($grouped);
        $meta = $data['meta'] ?? [];

        $order = DB::transaction(function () use ($user, $finalItems, $meta) {
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'meta' => $meta,
            ]);

            foreach ($finalItems as $item) {
                $order->items()->create($item);
            }

            $order->recalculateTotal();

            return $order;
        });

        if ($order instanceof Order) {
            SendOrderConfirmation::dispatch($order->id)->onQueue('emails');
        }

        $order->setAttribute('was_merged', $merged);

        return $order->load('items');
    }

    public function cancel(int $id): Order
    {
        $order = Order::find($id);

        if (! $order) {
            throw new ModelNotFoundException('Pedido não encontrado.');
        }

        if ($order->status !== 'cancelled') {
            $order->update(['status' => 'cancelled']);
        }

        return $order;
    }
}
