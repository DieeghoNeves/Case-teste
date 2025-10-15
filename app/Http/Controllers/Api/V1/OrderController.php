<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Resources\OrderResource;
use App\Jobs\SendOrderConfirmation;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        
        $this->middleware('auth:sanctum');
        $this->middleware('throttle:60,1'); 
    }


    public function index(Request $request)
    {
        $query = $request->user()->orders()->with('items');

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $perPage = (int) $request->get('per_page', 15);
        $orders = $query->orderByDesc('created_at')->paginate($perPage);

        if ($orders->isEmpty()) {
            return response()->json([
                'message' => 'Nenhum pedido cadastrado!',
                'data' => [],
            ], 200);
        }

        return OrderResource::collection($orders)->additional([
            'meta' => [
                'status_filter' => $request->get('status'),
            ],
        ]);
    }

    public function show(Request $request, $id)
    {
        $order = Order::with('items')->find($id);

        if (! $order) {
            return response()->json([
                'message' => 'Pedido não encontrado!'
            ], 404);
        }

        $user = $request->user();
        if ($order->user_id !== $user->id) {
            return response()->json([
                'message' => 'Acesso negado ao pedido.'
            ], 403);
        }

        return new OrderResource($order);
    }


    public function store(OrderStoreRequest $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Usuário não autenticado.'], 401);
        }

        $incomingItems = $request->get('items', []);
        $grouped = [];
        $merged = false;

        foreach ($incomingItems as $item) {
            $nameKey = mb_strtolower(trim($item['product_name']));

            $unitPrice = isset($item['unit_price']) ? (float) $item['unit_price'] : 0.0;
            $quantity  = isset($item['quantity']) ? (int) $item['quantity'] : 0;

            if (isset($grouped[$nameKey])) {
                $grouped[$nameKey]['quantity'] += $quantity;

                $merged = true;
            } else {
                $grouped[$nameKey] = [
                    'product_name' => $item['product_name'],
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                ];
            }
        }

        $finalItems = array_values($grouped);

        $order = DB::transaction(function () use ($request, $user, $finalItems) {
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'meta' => $request->get('meta', []),
            ]);

            foreach ($finalItems as $item) {
                $order->items()->create([
                    'product_name' => $item['product_name'],
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                ]);
            }

            $order->recalculateTotal();

            return $order;
        });

        if ($order instanceof Order) {
            SendOrderConfirmation::dispatch($order->id)->onQueue('emails');
        }

        $resource = (new OrderResource($order->load('items')))->response()->setStatusCode(201);
        $responseData = $resource->getData(true);

        if ($merged) {
            $responseData['message'] = 'Produto ja cadastrado, adicionado na quantidade!';
        }

        return response()->json($responseData, 201);
    }

    public function cancel(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido não encontrado!',
            ], 404);
        }

        if ($order->status === 'cancelled') {
            return response()->json([
                'success' => true,
                'status' => $order->status,
                'message' => 'Pedido já estava cancelado.'
            ], 200);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'status' => 'cancelled',
            'message' => 'Pedido cancelado com sucesso.'
        ], 200);
    }

}
