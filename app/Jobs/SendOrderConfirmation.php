<?php

namespace App\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use App\Models\Order;
use Throwable;

class SendOrderConfirmation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 60;


    public int $timeout = 120;

    protected int $orderId;

    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }


    public function handle(): void
    {
        $order = Order::with(['items', 'user'])->find($this->orderId);

        if (! $order) {
            Log::warning("SendOrderConfirmation: Pedido #{$this->orderId} não encontrado.");
            return;
        }

        $email = $order->user->email ?? 'desconhecido';
        $itemsCount = $order->items->count();
        $total = number_format($order->total_value, 2, ',', '.');

        Log::info(' Enviando e-mail de confirmação de pedido', [
            'order_id' => $order->id,
            'user_email' => $email,
            'total_value' => $total,
            'items' => $itemsCount,
            'timestamp' => now()->toDateTimeString(),
        ]);

        // Aqui você poderia colocar lógica real de envio, ex:
        // Mail::to($email)->send(new OrderConfirmationMail($order));
    }

    public function failed(Throwable $exception): void
    {
        Log::error(' Falha ao enviar confirmação de pedido', [
            'order_id' => $this->orderId,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
