<?php

namespace App\Http\Controllers\Api;

use App\Helpers\DemoHelper;
use App\Http\Controllers\Controller;
use App\Mail\EticketMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use OpenApi\Attributes as OA;

class XenditWebhookController extends Controller
{
    #[OA\Post(
        path: '/xendit/webhook',
        summary: 'Xendit payment notification webhook',
        description: 'Callback dari Xendit Invoice. PAID → order success & tickets sold. EXPIRED → order cancel & tickets available & stock restored. Endpoint ini TIDAK memerlukan authentication, tapi diverifikasi via X-CALLBACK-TOKEN header.',
        tags: ['Webhook'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'id', type: 'string', example: 'inv_xxx', description: 'Xendit Invoice ID'),
                    new OA\Property(property: 'external_id', type: 'string', example: 'ORDER-1-1234567890', description: 'External ID (format: ORDER-{orderId}-{timestamp})'),
                    new OA\Property(property: 'status', type: 'string', enum: ['PAID', 'EXPIRED', 'PENDING'], example: 'PAID'),
                    new OA\Property(property: 'amount', type: 'number', example: 100000),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Webhook processed',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Invalid webhook token',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string', example: 'Invalid token'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Order not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string', example: 'Order not found'),
                    ]
                )
            ),
        ]
    )]
    public function handle(Request $request)
    {
        // Verify webhook token
        $webhookToken = config('xendit.webhook_token');
        if ($webhookToken && $request->header('X-CALLBACK-TOKEN') !== $webhookToken) {
            return response()->json(['status' => 'error', 'message' => 'Invalid token'], 403);
        }

        $externalId = $request->input('external_id');
        $status = $request->input('status');

        // Parse order_id from external_id (format: ORDER-{id}-{timestamp})
        $parts = explode('-', $externalId);
        $orderId = $parts[1] ?? null;

        $order = Order::with('orderTickets.ticket.sku')->find($orderId);

        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        // Guard: only process if order is still pending (prevents double processing)
        if ($order->status_payment !== 'pending') {
            return response()->json(['status' => 'success', 'message' => 'Already processed']);
        }

        if ($status === 'PAID') {
            $order->update(['status_payment' => 'success']);

            foreach ($order->orderTickets as $ot) {
                $ot->ticket->update(['status' => 'sold']);
            }

            // Send e-ticket email (skip for demo accounts)
            $order->load(['user', 'event.vendor', 'orderTickets.ticket.sku']);
            if (!DemoHelper::isDemoAccount($order->user->email)) {
                Mail::to($order->user->email)->send(new EticketMail($order));
            }
        } elseif ($status === 'EXPIRED') {
            $order->update(['status_payment' => 'cancel']);

            foreach ($order->orderTickets as $ot) {
                $ticket = $ot->ticket;
                if ($ticket->status === 'booked') {
                    $ticket->update(['status' => 'available']);
                    $ticket->sku->increment('stock');
                }
            }
        }

        return response()->json(['status' => 'success']);
    }
}
