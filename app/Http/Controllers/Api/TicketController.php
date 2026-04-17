<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Vendor;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class TicketController extends Controller
{
    #[OA\Get(
        path: '/tickets/user/{id}',
        summary: 'Get semua tiket milik vendor (by user ID)',
        tags: ['Tickets'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'User ID (vendor)', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daftar tiket',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer'),
                                    new OA\Property(property: 'sku_id', type: 'integer'),
                                    new OA\Property(property: 'event_id', type: 'integer'),
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
                                    new OA\Property(
                                        property: 'event',
                                        type: 'object',
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer'),
                                            new OA\Property(property: 'name', type: 'string'),
                                        ]
                                    ),
                                ],
                                type: 'object'
                            )
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function getTickeUser($id)
    {
        $vendors = Vendor::where('user_id', $id)->get();
        $eventIds = Event::whereIn('vendor_id', $vendors->pluck('id'))->pluck('id');
        $tickets = \App\Models\Ticket::with(['sku', 'event'])->whereIn('event_id', $eventIds)->get();
        return response()->json([
            'status' => 'success',
            'data' => $tickets
        ]);
    }

    #[OA\Post(
        path: '/check-ticket',
        summary: 'Validasi & redeem tiket',
        description: 'Cek apakah ticket_code valid dan statusnya available. Jika valid, status diubah ke redeem.',
        tags: ['Tickets'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['ticket_code'],
                properties: [
                    new OA\Property(property: 'ticket_code', type: 'string', example: 'TKT20260001'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tiket valid & berhasil di-redeem',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Ticket redeemed successfully'),
                        new OA\Property(property: 'isValid', type: 'boolean', example: true),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Tiket sudah di-redeem',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string', example: 'Ticket already redeemed'),
                        new OA\Property(property: 'isValid', type: 'boolean', example: false),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Tiket tidak ditemukan',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string', example: 'Ticket not found'),
                        new OA\Property(property: 'isValid', type: 'boolean', example: false),
                    ]
                )
            ),
        ]
    )]
    public function checkTicketValid(Request $request)
    {
        $request->validate([
            'ticket_code' => 'required'
        ]);

        $ticket = \App\Models\Ticket::where('ticket_code', $request->ticket_code)->first();
        if ($ticket) {
            if (in_array($ticket->status, ['available', 'booked', 'sold'])) {
                $ticket->status = 'redeem';
                $ticket->ticket_date = now();
                $ticket->save();
                $ticket->load(['sku', 'event']);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Ticket redeemed successfully',
                    'isValid' => true,
                    'data' => [
                        'ticket_code' => $ticket->ticket_code,
                        'event_name' => $ticket->event->name ?? '-',
                        'sku_name' => $ticket->sku->name ?? '-',
                        'redeemed_at' => now()->toDateTimeString(),
                    ],
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ticket already redeemed',
                    'isValid' => false
                ], 400);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Ticket not found',
                'isValid' => false

            ], 404);
        }
    }

    public function bulkCheck(Request $request)
    {
        $request->validate([
            'ticket_codes' => 'required|array|min:1',
            'ticket_codes.*' => 'required|string',
        ]);

        $user = $request->user();
        $vendor = $user->vendor ?? null;
        $vendorEventIds = $vendor ? $vendor->events()->pluck('id') : collect();

        $results = ['success' => [], 'failed' => []];

        foreach ($request->ticket_codes as $code) {
            $ticket = \App\Models\Ticket::where('ticket_code', $code)->first();

            if (!$ticket) {
                $results['failed'][] = ['code' => $code, 'reason' => 'Not found'];
                continue;
            }

            if ($vendor && !$vendorEventIds->contains($ticket->event_id)) {
                $results['failed'][] = ['code' => $code, 'reason' => 'Not your event'];
                continue;
            }

            if ($ticket->status === 'redeem') {
                $results['failed'][] = ['code' => $code, 'reason' => 'Already redeemed'];
                continue;
            }

            if (!in_array($ticket->status, ['sold', 'booked'])) {
                $results['failed'][] = ['code' => $code, 'reason' => 'Invalid status'];
                continue;
            }

            $ticket->update(['status' => 'redeem', 'ticket_date' => now()]);
            $results['success'][] = $code;
        }

        return response()->json([
            'status' => 'success',
            'data' => $results,
        ]);
    }
}
