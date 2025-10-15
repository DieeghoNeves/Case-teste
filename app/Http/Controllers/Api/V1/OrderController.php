<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->middleware('auth:sanctum');
        $this->middleware('throttle:60,1');
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 15);
        $filters = ['status' => $request->get('status')];

        $orders = $this->orderService->index($request->user(), $filters, $perPage);

        if ($orders->isEmpty()) {
            return response()->json([
                'message' => 'Nenhum pedido cadastrado!',
                'data' => [],
            ], Response::HTTP_OK);
        }

        return OrderResource::collection($orders)->additional([
            'meta' => [
                'status_filter' => $request->get('status'),
            ],
        ]);
    }

    public function show(Request $request, $id)
    {
        try {
            $order = $this->orderService->show($request->user(), (int) $id);
            return new OrderResource($order);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Pedido não encontrado!'], Response::HTTP_NOT_FOUND);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Acesso negado ao pedido.'], Response::HTTP_FORBIDDEN);
        }
    }

    public function store(OrderStoreRequest $request)
    {
        $user = $request->user();
        $data = $request->only(['items', 'meta']);
        $order = $this->orderService->store($user, $data);

        $resource = (new OrderResource($order))->response()->setStatusCode(Response::HTTP_CREATED);
        $responseData = $resource->getData(true);

        if ($order->getAttribute('was_merged')) {
            $responseData['message'] = 'Produto já cadastrado, adicionado na quantidade!';
        }

        return response()->json($responseData, Response::HTTP_CREATED);
    }

    public function cancel(Request $request, $id)
    {
        try {
            $order = $this->orderService->cancel((int) $id);

            return response()->json([
                'success' => true,
                'status' => $order->status,
                'message' => 'Pedido cancelado com sucesso.'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido não encontrado!',
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
