<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Event;
use App\Models\Vendor;
use App\Services\CancelOrderService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;

class OrderController extends Controller
{
    #[OA\Post(
        path: '/order',
        summary: 'Buat order tiket baru',
        description: 'Membuat order baru, book tiket, dan generate payment URL via Xendit.',
        tags: ['Orders'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['event_id', 'order_details', 'quantity', 'event_date'],
                properties: [
                    new OA\Property(property: 'event_id', type: 'integer', example: 1),
                    new OA\Property(
                        property: 'order_details',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'sku_id', type: 'integer', example: 1),
                            ],
                            type: 'object'
                        )
                    ),
                    new OA\Property(property: 'quantity', type: 'integer', example: 2),
                    new OA\Property(property: 'event_date', type: 'string', format: 'date', example: '2026-04-01'),
                    new OA\Property(property: 'promo_code', type: 'string', nullable: true, example: 'DISKON10', description: 'Kode promo opsional'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Order berhasil dibuat',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Order created successfully'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer'),
                                new OA\Property(property: 'user_id', type: 'integer'),
                                new OA\Property(property: 'event_id', type: 'integer'),
                                new OA\Property(property: 'quantity', type: 'integer'),
                                new OA\Property(property: 'total_price', type: 'integer'),
                                new OA\Property(property: 'event_date', type: 'string'),
                                new OA\Property(property: 'status_payment', type: 'string', example: 'pending'),
                                new OA\Property(property: 'promo_code', type: 'string', nullable: true, example: 'DISKON10'),
                                new OA\Property(property: 'discount_amount', type: 'integer', example: 0),
                                new OA\Property(property: 'payment_url', type: 'string', nullable: true, description: 'Xendit Snap redirect URL'),
                                new OA\Property(property: 'user', type: 'object'),
                                new OA\Property(property: 'orderItems', type: 'array', items: new OA\Items(type: 'object')),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Error (tiket habis, validation error, dll)',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function create(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'order_details' => 'required|array',
            'order_details.*.sku_id' => 'required|exists:skus,id',
            'order_details.*.qty' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1',
            'event_date' => 'required',
            'promo_code' => 'nullable|string',
        ]);

        try {
            $orderService = app(OrderService::class);
            $order = $orderService->createOrder(
                $request->user(),
                $request->event_id,
                $request->order_details,
                $request->event_date,
                $request->quantity,
                $request->promo_code
            );

            $order['user'] = $request->user();
            $order['orderItems'] = $request->order_details;

            return response()->json([
                'status' => 'success',
                'message' => 'Order created successfully',
                'data' => $order,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    #[OA\Put(
        path: '/orders/{id}',
        summary: 'Update status payment order',
        tags: ['Orders'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['status_payment'],
                properties: [
                    new OA\Property(property: 'status_payment', type: 'string', enum: ['pending', 'success', 'cancel'], example: 'success'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Order updated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_payment' => 'required|string|in:pending,success,cancel',
        ]);

        $order = Order::with('orderTickets.ticket.sku')->find($id);
        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 404);
        }

        $newStatus = $request->status_payment;

        // Guard: prevent processing already-cancelled/completed orders
        if ($order->status_payment !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Order sudah diproses sebelumnya (status: ' . $order->status_payment . ')',
            ], 422);
        }

        $order->update(['status_payment' => $newStatus]);

        // Release tickets & restore stock when cancelling a pending/booked order
        if ($newStatus === 'cancel') {
            foreach ($order->orderTickets as $ot) {
                $ticket = $ot->ticket;
                if ($ticket->status === 'booked') {
                    $ticket->update(['status' => 'available']);
                    $ticket->sku->increment('stock');
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Order updated successfully',
            'data' => $order
        ], 200);
    }

    #[OA\Get(
        path: '/orders/user/{id}',
        summary: 'Get riwayat order buyer',
        description: 'Mengambil semua order milik user beserta orderTickets (grouped by ticket_id dengan total_quantity).',
        tags: ['Orders'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'User ID', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daftar order user',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer'),
                                    new OA\Property(property: 'user_id', type: 'integer'),
                                    new OA\Property(property: 'event_id', type: 'integer'),
                                    new OA\Property(property: 'quantity', type: 'integer'),
                                    new OA\Property(property: 'total_price', type: 'integer'),
                                    new OA\Property(property: 'event_date', type: 'string'),
                                    new OA\Property(property: 'status_payment', type: 'string', enum: ['pending', 'success', 'cancel']),
                                    new OA\Property(property: 'payment_url', type: 'string', nullable: true),
                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                                    new OA\Property(
                                        property: 'orderTickets',
                                        type: 'array',
                                        description: 'Grouped by ticket_id dengan total_quantity',
                                        items: new OA\Items(
                                            properties: [
                                                new OA\Property(property: 'id', type: 'integer'),
                                                new OA\Property(property: 'order_id', type: 'integer'),
                                                new OA\Property(property: 'ticket_id', type: 'integer'),
                                                new OA\Property(property: 'total_quantity', type: 'integer', description: 'Jumlah tiket per ticket_id'),
                                                new OA\Property(
                                                    property: 'ticket',
                                                    type: 'object',
                                                    properties: [
                                                        new OA\Property(property: 'id', type: 'integer'),
                                                        new OA\Property(property: 'ticket_code', type: 'string', example: 'TKT20260001'),
                                                        new OA\Property(property: 'status', type: 'string', enum: ['available', 'booked', 'sold', 'redeem']),
                                                        new OA\Property(
                                                            property: 'sku',
                                                            type: 'object',
                                                            properties: [
                                                                new OA\Property(property: 'id', type: 'integer'),
                                                                new OA\Property(property: 'name', type: 'string'),
                                                                new OA\Property(property: 'price', type: 'integer'),
                                                            ]
                                                        ),
                                                    ]
                                                ),
                                            ],
                                            type: 'object'
                                        )
                                    ),
                                    new OA\Property(
                                        property: 'user',
                                        type: 'object',
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer'),
                                            new OA\Property(property: 'name', type: 'string'),
                                            new OA\Property(property: 'email', type: 'string'),
                                        ]
                                    ),
                                    new OA\Property(property: 'event', type: 'object'),
                                ],
                                type: 'object'
                            )
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Unauthorized — bukan pemilik order'),
        ]
    )]
    public function getOrderByUserId($id)
    {
        if (auth()->id() != $id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $orders = Order::with(['user', 'event.vendor', 'orderTickets.ticket.sku'])
            ->where('user_id', $id)
            ->get();

        $orders = $orders->map(function ($order) {
            $groupedTickets = $order->orderTickets->groupBy('ticket_id')->map(function ($details, $ticketId) {
                $firstDetail = $details->first();
                $totalQuantity = $details->count();

                $firstDetail->total_quantity = $totalQuantity;
                return $firstDetail;
            });

            $order->orderTickets = $groupedTickets->values();
            return $order;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Get all order history',
            'data' => $orders
        ]);
    }

    #[OA\Get(
        path: '/orders/user/{id}/vendor',
        summary: 'Get orders untuk vendor (semua event milik vendor)',
        tags: ['Orders'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'User ID (vendor)', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daftar order untuk event vendor',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Unauthorized'),
        ]
    )]
    public function getOrderByVendor($id)
    {
        if (auth()->id() != $id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $vendors = Vendor::where('user_id', $id)->get();
        $eventIds = Event::whereIn('vendor_id', $vendors->pluck('id'))->pluck('id');
        $orders = Order::with(['user', 'event.vendor', 'orderTickets.ticket.sku'])
            ->whereIn('event_id', $eventIds)
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Get all order history by vendor',
            'data' => $orders
        ]);
    }

    #[OA\Get(
        path: '/orders/user/{id}/vendor/total',
        summary: 'Get total revenue vendor',
        tags: ['Orders'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'User ID (vendor)', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Total revenue',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'data', type: 'integer', example: 500000, description: 'Sum total_price semua order'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function getOrderTotalByVendor($id)
    {
        $vendors = Vendor::where('user_id', $id)->get();
        $eventIds = Event::whereIn('vendor_id', $vendors->pluck('id'))->pluck('id');
        $orders = Order::with(['user', 'event.vendor', 'orderTickets.ticket.sku'])
            ->whereIn('event_id', $eventIds)
            ->get();

        $sumTotalOrder = $orders->sum('total_price');

        return response()->json([
            'status' => 'success',
            'message' => 'Get total price order history by vendor',
            'data' => $sumTotalOrder
        ]);
    }

    #[OA\Post(
        path: '/orders/{id}/cancel',
        summary: 'Cancel order yang sudah dibayar (refund request)',
        description: 'Buyer cancel order dengan status success. Batas waktu H-3 sebelum event. Tiket yang belum di-redeem akan di-release.',
        tags: ['Orders'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['cancel_reason'],
                properties: [
                    new OA\Property(property: 'cancel_reason', type: 'string', example: 'Saya tidak bisa hadir karena ada urusan mendadak.'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Order berhasil di-cancel, menunggu refund approval',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation/business error'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function cancelOrder(Request $request, $id)
    {
        $request->validate([
            'cancel_reason' => 'required|string|min:10',
        ]);

        $order = Order::with(['orderTickets.ticket.sku', 'event'])->find($id);
        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 404);
        }

        try {
            $cancelService = app(CancelOrderService::class);
            $order = $cancelService->cancel($order, $request->user(), $request->cancel_reason);

            return response()->json([
                'status' => 'success',
                'message' => 'Order berhasil di-cancel. Menunggu persetujuan refund dari admin.',
                'data' => $order,
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
