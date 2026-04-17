<?php

namespace App\Http\Controllers\Api;

use App\Helpers\UniqueCodeHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class SkuController extends Controller
{
    #[OA\Get(
        path: '/skus/user/{id}',
        summary: 'Get semua SKU milik vendor (by user ID)',
        tags: ['SKUs'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'User ID (vendor)', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daftar SKU',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer'),
                                    new OA\Property(property: 'name', type: 'string', example: 'VIP'),
                                    new OA\Property(property: 'category', type: 'string', example: 'Premium'),
                                    new OA\Property(property: 'event_id', type: 'integer'),
                                    new OA\Property(property: 'price', type: 'integer', example: 150000),
                                    new OA\Property(property: 'stock', type: 'integer', example: 100),
                                    new OA\Property(property: 'day_type', type: 'string', example: 'weekday'),
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
    public function index($userId)
    {
        $vendor = \App\Models\Vendor::where('user_id', $userId)->get();
        $event = \App\Models\Event::whereIn('vendor_id', $vendor->pluck('id'))->get();
        $sku = \App\Models\Sku::with(['event'])->whereIn('event_id', $event->pluck('id'))->get();
        return response()->json([
            'status' => 'success',
            'data' => $sku
        ]);
    }

    #[OA\Post(
        path: '/sku',
        summary: 'Buat SKU baru (auto-generate tickets)',
        description: 'Membuat tipe tiket baru untuk event. Otomatis generate Ticket records sejumlah stock.',
        tags: ['SKUs'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'price', 'category', 'event_id', 'stock', 'day_type'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'VIP'),
                    new OA\Property(property: 'price', type: 'integer', example: 150000),
                    new OA\Property(property: 'category', type: 'string', example: 'Premium'),
                    new OA\Property(property: 'event_id', type: 'integer', example: 1),
                    new OA\Property(property: 'stock', type: 'integer', example: 50, description: 'Jumlah tiket yang akan di-generate'),
                    new OA\Property(property: 'day_type', type: 'string', enum: ['weekday', 'weekend'], example: 'weekday'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'SKU berhasil dibuat + tickets auto-generated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Sku created successfully'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer'),
                                new OA\Property(property: 'name', type: 'string'),
                                new OA\Property(property: 'price', type: 'integer'),
                                new OA\Property(property: 'category', type: 'string'),
                                new OA\Property(property: 'event_id', type: 'integer'),
                                new OA\Property(property: 'stock', type: 'integer'),
                                new OA\Property(property: 'day_type', type: 'string'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(Request $request, $id)
    {
        $sku = \App\Models\Sku::find($id);
        if (!$sku) {
            return response()->json([
                'status' => 'error',
                'message' => 'SKU not found'
            ], 404);
        }

        $request->validate([
            'name' => 'sometimes|required',
            'price' => 'sometimes|required',
            'category' => 'sometimes|required',
            'stock' => 'sometimes|required',
            'day_type' => 'sometimes|required',
        ]);

        $sku->update($request->only(['name', 'price', 'category', 'stock', 'day_type']));

        return response()->json([
            'status' => 'success',
            'message' => 'Sku updated successfully',
            'data' => $sku
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'category' => 'required',
            'event_id' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'day_type' => 'required',
        ]);

        $data = $request->all();
        $sku = \App\Models\Sku::create($data);
        for ($i = 0; $i < $data['stock']; $i++) {
            $ticket_code = UniqueCodeHelper::generateUniqueCode('tickets', 'ticket_code');
            $ticket = \App\Models\Ticket::create([
                'event_id' => $data['event_id'],
                'sku_id' => $sku->id,
                'ticket_code' => $ticket_code,
                'status' => 'available',
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Sku created successfully',
            'data' => $sku
        ]);
    }
}
